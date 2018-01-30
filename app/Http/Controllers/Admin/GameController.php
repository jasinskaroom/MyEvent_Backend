<?php

namespace App\Http\Controllers\Admin;

use Auth;
use AppLocale;
use App\Models\Game;
use App\Models\Event;
use App\Models\Stage;
use App\Models\RuleGame;
use App\Models\InputGame;
use Illuminate\Http\Request;
use App\Services\GameService;
use App\Models\InputGameField;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function viewGames($stageId, Request $request)
    {
        $stage = Stage::findOrFail($stageId);
        $locales = AppLocale::getAvailableLocales();

        $resultPerPage = 15;
        $games = $this->getListGamesPaginate($stage, $request->get('query'), $resultPerPage);
        if ($stage->game_type == Game::TYPE_TEXT_INPUT) {
            // If this stage has not created a game,
            // we need to force them to create,
            // Input game is special, It can only has a game
            if ($games->count() <= 0) {
                return redirect()->action('Admin\GameController@showCreateInputGameForm', ['stageId' => $stageId]);
            }
            else {
                return view('admin.event.game.list_input', [
                    'stage' => $stage,
                    'game' => $games->first(),
                    'locales' => $locales
                ]);
            }
        }
        else if ($stage->game_type == Game::TYPE_IMAGE_RULE) {
            return view('admin.event.game.list_rule', [
                'stage' => $stage,
                'games' => $games,
                'locales' => $locales
            ]);
        }
        else {
            abort(404);
        }
    }

    public function reorderGame($id, Request $request, GameService $service)
    {
        $this->validate($request, [
            'swapWithGameId' => 'required'
        ]);

        $gameToSwap = $request->get('swapWithGameId');

        $game = Game::findOrFail($id);
        $status = $service->swapGame($id, $gameToSwap, $game->stage->id);

        if ($status) {
            return response()->json([
                'data' => [
                    'message' => 'Successfully swap'
                ]
            ]);
        }
        else {
            return response()->json([
                'error' => [
                    'message' => 'Not able to swap. Game not found'
                ]
            ]);
        }
    }

    ////////////////////////////////////////////////////////////////// Input Game ///////////////////////////////////////////////////////////////////////////
    public function showCreateInputGameForm($stageId)
    {
        $stage = Stage::findOrFail($stageId);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.create_input_game_form', [
            'stage' => $stage,
            'locales' => $locales
        ]);
    }

    public function showEditInputGameForm($id)
    {
        $game = Game::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.edit_input_game_form', [
            'game' => $game,
            'locales' => $locales
        ]);
    }

    public function createInputGame($stageId, Request $request, GameService $service)
    {
        $stage = Stage::findOrFail($stageId);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations['game_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->createInputGame($stage, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $stageId]);
    }

    public function updateInputGame($id, Request $request, GameService $service)
    {
        $game = Game::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations['game_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->updateInputGame($id, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $game->stage_id]);
    }

    public function deleteInputGame($id)
    {
        $game = Game::findOrFail($id);
        $eventId = $game->stage->event->id;

        $game->delete();

        return redirect()->action('Admin\EventStageController@viewEventStages', ['eventId' => $eventId]);
    }

    public function showCreateInputGameFieldForm($gameId)
    {
        $game = Game::findOrFail($gameId);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.create_input_game_field_form', [
            'game' => $game,
            'locales' => $locales
        ]);
    }

    public function showEditInputGameFieldForm($id)
    {
        $field = InputGameField::findOrFail($id);
        $game = $field->inputGame->game;
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.edit_input_game_field_form', [
            'field' => $field,
            'game' => $game,
            'locales' => $locales
        ]);
    }

    public function createInputGameField($gameId, Request $request, GameService $service)
    {
        $game = Game::findOrFail($gameId);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations['field_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->createInputGameField($game, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $game->stage->id]);
    }

    public function updateInputGameField($id, Request $request, GameService $service)
    {
        $field = InputGameField::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations['field_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->updateInputGameField($id, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $field->inputGame->game->stage->id]);
    }

    public function deleteInputGameField($id)
    {
        $field = InputGameField::findOrFail($id);
        $stageId = $field->inputGame->game->stage->id;

        $field->delete();

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $field->inputGame->game->stage->id]);
    }

    public function reorderInputGameField($id, Request $request, GameService $service)
    {
        $this->validate($request, [
            'swapWithGameId' => 'required'
        ]);

        $fieldToSwap = $request->get('swapWithGameId');

        $status = $service->swapInputGameField($id, $fieldToSwap);

        if ($status) {
            return response()->json([
                'data' => [
                    'message' => 'Successfully swap'
                ]
            ]);
        }
        else {
            return response()->json([
                'error' => [
                    'message' => 'Not able to swap. Field not found'
                ]
            ]);
        }
    }
    ///////////////////////////////////////////////////////////////////// END /////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////// Rule Game ///////////////////////////////////////////////////////////////////////
    public function showCreateRuleGameForm($stageId)
    {
        $stage = Stage::findOrFail($stageId);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.create_rule_game_form', [
            'stage' => $stage,
            'locales' => $locales
        ]);
    }

    public function showEditRuleGameForm($id)
    {
        $game = Game::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        return view('admin.event.game.edit_rule_game_form', [
            'game' => $game,
            'locales' => $locales
        ]);
    }

    public function createRuleGame($stageId, Request $request, GameService $service)
    {
        $stage = Stage::findOrFail($stageId);
        $locales = AppLocale::getAvailableLocales();

        $validations = [
            'preview_image' => 'required|image'
        ];
        foreach ($locales as $locale) {
            $validations['game_'.$locale->code] = 'required';
            $validations['rule_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->createRuleGame($stage, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $stageId]);
    }

    public function updateRuleGame($id, Request $request, GameService $service)
    {
        $game = Game::findOrFail($id);
        $locales = AppLocale::getAvailableLocales();

        $validations = [];
        foreach ($locales as $locale) {
            $validations['game_'.$locale->code] = 'required';
            $validations['rule_'.$locale->code] = 'required';
        }

        $this->validate($request, $validations);

        $service->updateRuleGame($id, $request->all());

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $game->stage_id]);
    }

    public function deleteRuleGame($id)
    {
        $game = Game::findOrFail($id);
        $stageId = $game->stage_id;
        $game->delete();

        return redirect()->action('Admin\GameController@viewGames', ['stageId' => $stageId]);
    }
    /////////////////////////////////////////////////////////////////////// END /////////////////////////////////////////////////////////////////////////

    private function getListGamesPaginate($stage, $query, $resultPerPage)
    {
        if (!is_null($query)) {
            $games = $stage->games()->search($query)->distinct();
        }
        else {
            $games = $stage->games();
        }

        return $games->orderBy('order')->paginate($resultPerPage);
    }
}
