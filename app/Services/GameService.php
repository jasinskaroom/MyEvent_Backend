<?php

namespace App\Services;

use Storage;
use AppLocale;
use App\Models\Game;
use App\Models\Stage;
use App\Models\RuleGame;
use App\Models\InputGame;
use App\Models\InputGameField;

class GameService extends BaseService
{
    public function createGame($names, $stageId, $type, $number_of_scan=1)
    {
        $this->cleanUpGameOrderValue($stageId);

        $gameData = $names;
        $gameData['stage_id'] = $stageId;
        $gameData['type'] = $type;
        $gameData['number_of_scan'] = $number_of_scan;
        $gameData['order'] = $this->getLatestGameOrderValue($stageId) + 1;

        $game = Game::create($gameData);

        return $game;
    }

    public function updateGame($game, $names)
    {
        if (!is_null($game)) {
            $locales = AppLocale::getAvailableLocales();

            foreach($locales as $locale) {
                $game->translate($locale->code)->name = $names[$locale->code]['name'];
                $game->save();
            }

            return $game;
        }
        else {
            return null;
        }
    }

    public function swapGame($gameOriId, $gameToSwapId, $stageId)
    {
        $this->cleanUpGameOrderValue($stageId);

        $gameOri = Game::find($gameOriId);
        $gameToSwap = Game::find($gameToSwapId);

        if (is_null($gameOri) || is_null($gameToSwap)) {
            return false;
        }

        $tempOrder = $gameOri->order;
        $gameOri->order = $gameToSwap->order;
        $gameToSwap->order = $tempOrder;

        $gameOri->save();
        $gameToSwap->save();

        return true;
    }

    public function getLatestGameOrderValue($stageId)
    {
        $games = Game::where('stage_id', $stageId)->get();

        if ($games->count() > 0) {
            $largestOrderNum = $games->first()->order;

            for ($i = 1; $i < $games->count(); $i++) {
                if ($games->get($i)->order > $largestOrderNum) {
                    $largestOrderNum = $games->get($i)->order;
                }
            }

            return $largestOrderNum;
        }

        return -1;
    }

    public function cleanUpGameOrderValue($stageId)
    {
        $currentOrder = 0;

        $games = Game::where('stage_id', $stageId)->orderBy('order', 'asc')->get();

        foreach ($games as $game) {
            $game->order = $currentOrder;
            $game->save();

            $currentOrder++;
        }
    }

    //////////////////////////////////////////////////////////// Input Game /////////////////////////////////////////////////
    public function createInputGame($stage, $data)
    {
        $locales = AppLocale::getAvailableLocales();
        // Create game
        $names = [];
        foreach($locales as $locale) {
            $names[$locale->code] = [
                'name' => $data['game_'.$locale->code]
            ];
        }
        $game = $this->createGame($names, $stage->id, Game::TYPE_TEXT_INPUT,$data['number_of_scan']);

        $inputGame = InputGame::create([
            'game_id' => $game->id
        ]);

        return $inputGame;
    }

    public function updateInputGame($id, $data)
    {
        $game = Game::find($id);

        if (!is_null($game)) {
            $locales = AppLocale::getAvailableLocales();

            foreach($locales as $locale) {
                $game->translate($locale->code)->name = $data['game_'.$locale->code];
                $game->number_of_scan = $data['number_of_scan'];
                $game->save();
            }

            return $game;
        }
        else {
            return null;
        }
    }

    public function createInputGameField($game, $data)
    {
        $this->cleanUpInputGameFieldOrderValue($game);

        $locales = AppLocale::getAvailableLocales();
        // Create field
        $fieldData = [
            'input_game_id' => $game->inputGame->id,
            'order' => $this->getLatestInputGameFieldOrderValue($game)
        ];
        foreach($locales as $locale) {
            $fieldData[$locale->code] = [
                'name' => $data['field_'.$locale->code]
            ];
        }

        $field = InputGameField::create($fieldData);

        return $field;
    }

    public function updateInputGameField($id, $data)
    {
        $field = InputGameField::find($id);

        if (!is_null($field)) {
            $locales = AppLocale::getAvailableLocales();

            foreach($locales as $locale) {
                $field->translate($locale->code)->name = $data['field_'.$locale->code];
                $field->save();
            }

            return $field;
        }
        else {
            return null;
        }
    }

    public function swapInputGameField($fieldOriId, $fieldToSwapId)
    {
        $fieldOri = InputGameField::find($fieldOriId);
        $fieldToSwap = InputGameField::find($fieldToSwapId);

        $this->cleanUpInputGameFieldOrderValue($fieldOri->inputGame->game);

        if (is_null($fieldOri) || is_null($fieldToSwap)) {
            return false;
        }

        $tempOrder = $fieldOri->order;
        $fieldOri->order = $fieldToSwap->order;
        $fieldToSwap->order = $tempOrder;

        $fieldOri->save();
        $fieldToSwap->save();

        return true;
    }

    public function getLatestInputGameFieldOrderValue($game)
    {
        $fields = $game->inputGame->fields;

        if ($fields->count() > 0) {
            $largestOrderNum = $fields->first()->order;

            for ($i = 1; $i < $fields->count(); $i++) {
                if ($fields->get($i)->order > $largestOrderNum) {
                    $largestOrderNum = $fields->get($i)->order;
                }
            }

            return $largestOrderNum;
        }

        return -1;
    }

    public function cleanUpInputGameFieldOrderValue($game)
    {
        $currentOrder = 0;

        $fields = $game->inputGame->fields()->orderBy('order', 'asc')->get();
        foreach ($fields as $field) {
            $field->order = $currentOrder;
            $field->save();

            $currentOrder++;
        }
    }
    //////////////////////////////////////////////////////////////// END ///////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////// Rule Game //////////////////////////////////////////////////////////////
    public function createRuleGame($stage, $data)
    {
        $locales = AppLocale::getAvailableLocales();
        // Create game
        $names = [];
        foreach($locales as $locale) {
            $names[$locale->code] = [
                'name' => $data['game_'.$locale->code]
            ];
        }
        $game = $this->createGame($names, $stage->id, Game::TYPE_IMAGE_RULE,$data['number_of_scan']);

        $gameData = [
            'game_id' => $game->id
        ];
        // Save image file
        if (isset($data['preview_image'])) {
            $fileMeta = $this->saveRuleGameImageFile($data['preview_image']);

            $gameData['preview_image_filename'] = $fileMeta['filename'];
            $gameData['preview_image_mime'] = $fileMeta['mime'];
        }
        else {
            $eventData['preview_image_filename'] = '';
            $eventData['preview_image_mime'] = '';
        }

        // Save image
        foreach($locales as $locale) {
            $gameData[$locale->code] = [
                'rule' => $data['rule_'.$locale->code]
            ];
        }

        $ruleGame = RuleGame::create($gameData);
        return $ruleGame;
    }

    public function updateRuleGame($id, $data)
    {
        $game = Game::find($id);

        if (!is_null($game)) {
            $locales = AppLocale::getAvailableLocales();

            // Update game
            $names = [];
            foreach($locales as $locale) {
                $names[$locale->code] = [
                    'name' => $data['game_'.$locale->code]
                ];
            }

            $game->number_of_scan = $data['number_of_scan'];
            $game->save();
            $this->updateGame($game, $names);

            $ruleGame = $game->ruleGame;
            // Update rule
            foreach($locales as $locale) {
                $ruleGame->translate($locale->code)->rule = $data['rule_'.$locale->code];
                $ruleGame->save();
            }

            // update preview image if found changes
            if (isset($data['preview_image'])) {
                // Remove current logo
                $this->removeRuleGameImageFile($game->preview_image_filename);

                // Create new file
                $fileMeta = $this->saveRuleGameImageFile($data['preview_image']);

                $ruleGame->update([
                    'preview_image_filename' => $fileMeta['filename'],
                    'preview_image_mime' => $fileMeta['mime']
                ]);
            }

            return $ruleGame;
        }
        else {
            return null;
        }
    }

    public function saveRuleGameImageFile($uploadFile)
    {
        // Save photo
        $path = $uploadFile->store('game');

        // Get filename
        $filename = explode('/', $path)[1];
        $mime = $uploadFile->getMimeType();

        return [
            'filename' => $filename,
            'mime' => $mime
        ];
    }

    public function removeRuleGameImageFile($filename)
    {
        if (Storage::disk('game')->exists($filename)) {
            Storage::disk('game')->delete($filename);
        }
    }
    ///////////////////////////////////////////////////////////////// END /////////////////////////////////////////////////////////////////
}
