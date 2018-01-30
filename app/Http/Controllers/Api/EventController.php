<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Event;
use App\Models\Stage;
use App\Models\QRCode;
use App\Models\RuleGame;
use App\Models\InputGame;
use Illuminate\Http\Request;
use App\Models\ParticipatedGame;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ParticipatedGameInput;

class EventController extends Controller
{
    public function getLiveEventDetail()
    {
        $event = Event::activeOnly()->first();

        $eventDetail = [];
        // banners
        $eventDetail['banners'] = $this->getEventBanners($event);

        // activities
        $eventDetail['activities'] = $this->getActivityList($event);

        // games
        $eventDetail['games'] = $this->getGames($event);
        $eventDetail['title'] = $event->getActivityTitle()?$event->getActivityTitle()->title:'Activities';
        $eventDetail['id']    = $event->id;

        return response()->json([
            'success' => true,
            'content' => $eventDetail
        ]);
    }

    public function retrieveParticipantScore()
    {
        $participant = Auth::guard('api')->user();

        return response()->json([
            'success' => true,
            'content' => [
                'userId' => $participant->draw_id,
                'drawId' => $participant->draw_id,
                'score' => $participant->totalPoints()
            ]
        ]);
    }

    public function markGameComplete(Request $request)
    {
        $this->validate($request, [
            'score_code' => 'required',
            'stage_id' => 'required',
            'game_id' => 'required',
            'game_type' => 'required'
        ]);

        $event = Event::activeOnly()->first();
        $qrCode = $event->qrcodes()->where('secret', $request->get('score_code'))->first();
        $participant = Auth::guard('api')->user();
        $gameId = $request->get('game_id');
        $gameModel = Game::find($gameId);
        $gameType = $request->get('game_type');
        $fields = $request->get('fields');


        if (is_null($qrCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QRCode'
            ]);
        }
        else
        {
            if ($participant->hasParticipatedInGame($gameId)) {

               // $participatedGame =  $participant->hasParticipatedInGameModel($gameId);
                if($participant->hasParticipatedInGames($gameId)>=$gameModel->number_of_scan){
                    return response()->json([
                        'success' => false,
                        'completed'=>true,
                        'message' => 'You\'ve have already reached maximum number of scans for this game'
                     ]);
                }
                else{
                    // Record participated
                    $participatedGame = ParticipatedGame::create([
                        'participant_id' => $participant->id,
                        'game_id' => $gameId,
                        'score' => $qrCode->point
                    ]);
                }

            }
            else{
                // Record participated
                $participatedGame = ParticipatedGame::create([
                    'participant_id' => $participant->id,
                    'game_id' => $gameId,
                    'score' => $qrCode->point
                ]);
            }

            // Record down input
            if ($gameType == Game::TYPE_TEXT_INPUT) {
                foreach ($fields as $field) {
                    $input = ParticipatedGameInput::create([
                        'participated_game_id' => $participatedGame->id,
                        'field_id' => $field['id'],
                        'value' => $field['value']
                    ]);
                }
            }

            // Increase participant point
            $participant->score += $qrCode->point;
            $participant->save();

            return response()->json([
                'success' => true,
                'completed'=>$participant->isParticipationInGameCompleted($gameId),
                'content' => [
                    'added_score' => $qrCode->point,
                    'new_score' => $participant->totalPoints()
                ]
            ]);
        }


    }

    /**
    * Helpers
    */
    private function getEventBanners($event)
    {
        $banners = $event->banners()->orderBy('order', 'asc')->get();

        $bannerResponse = [];
        foreach ($banners as $banner) {
            array_push($bannerResponse, [
                'title' => $banner->name,
                'image_url' => $banner->getImageUrl('en')
            ]);
        }

        return $bannerResponse;
    }

    private function getActivityList($event)
    {
        $stages = $event->stages()->orderBy('order')->get();

        $activityList = [];
        foreach ($stages as $stage) {
            $games = [];

            if ($stage->games->count() <= 0) {
                continue;
            }

            foreach ($stage->games as $game) {
                if ($stage->game_type == Game::TYPE_TEXT_INPUT) {
                    foreach ($game->inputGame->fields as $field) {
                        array_push($games, [
                            'id' => $game->id,
                            'title' => $field->name,
                            'field_name' => $field->name,
                            'game_type' => Game::TYPE_TEXT_INPUT
                        ]);
                    }
                }
                else {
                    array_push($games, [
                        'id' => $game->id,
                        'title' => $game->name,
                        'rule' => $game->ruleGame->rule,
                        'game_type' => Game::TYPE_IMAGE_RULE
                    ]);
                }
            }

            array_push($activityList, [
                'title' => $stage->name,
                'games' => $games
            ]);
        }

        return $activityList;
    }

    private function getGames($event)
    {
        $participant = Auth::guard('api')->user();
        $stages = $event->stages()->orderBy('order')->get();

        $games = [];
        foreach ($stages as $stage) {

            foreach ($stage->games as $game) {
                if ($game->type == Game::TYPE_TEXT_INPUT) {
                    $fields = $game->inputGame->fields;
                    if (count($fields) <= 0) continue;

                    $inputGameFields = [];
                    foreach ($fields as $field) {
                        array_push($inputGameFields, [
                            'id' => $field->id,
                            'name' => $field->name
                        ]);
                    }

                    array_push($games, [
                        'stage' => [
                            'id' => $stage->id,
                            'title' => $stage->name
                        ],
                        'game' => [
                            'id' => $game->id,
                            'name' => $game->name,
                            'fields' => $inputGameFields
                        ],
                        'completed' => $participant->isParticipationInGameCompleted($game->id),
                        'game_type' => Game::TYPE_TEXT_INPUT,
                    ]);
                }
                else {
                    array_push($games, [
                        'stage' => [
                            'id' => $stage->id,
                            'title' => $stage->name
                        ],
                        'game' => [
                            'id' => $game->id,
                            'title' => $game->name,
                            'rule' => $game->ruleGame->rule,
                            'image_url' => $game->ruleGame->getPreviewImageUrl(),
                        ],
                        'completed' => $participant->isParticipationInGameCompleted($game->id),
                        'game_type' => Game::TYPE_IMAGE_RULE
                    ]);
                }
            }
        }

        return $games;
    }
}
