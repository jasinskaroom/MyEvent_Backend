<?php

namespace App\Services;

use AppLocale;
use App\Models\Stage;
use App\Models\Event;

class EventStageService extends BaseService
{
    public function createStage($event, $data)
    {
        $this->cleanUpOrderValue($event);

        $stageData = [
            'game_type' => $data['type'],
            'order' => $this->getLatestOrderValue($event->stages) + 1
        ];

        $locales = AppLocale::getAvailableLocales();
        foreach($locales as $locale) {
            $stageData[$locale->code] = [
                'name' => $data[$locale->code]
            ];
        }

        $stageData['event_id'] = $event->id;
        $stage = Stage::create($stageData);

        return $stage;
    }

    public function updateStage($id, $data)
    {
        $stage = Stage::find($id);

        if (!is_null($stage)) {
            $locales = AppLocale::getAvailableLocales();

            foreach($locales as $locale) {
                $stage->translate($locale->code)->name = $data[$locale->code];
                $stage->save();
            }

            return $stage;
        }
        else {
            return null;
        }
    }

    public function swapStage($stageOriId, $stageToSwapId)
    {
        $stageOri = Stage::find($stageOriId);
        $stageToSwap = Stage::find($stageToSwapId);

        if (is_null($stageOri) || is_null($stageToSwap)) {
            return false;
        }

        $tempOrder = $stageOri->order;
        $stageOri->order = $stageToSwap->order;
        $stageToSwap->order = $tempOrder;

        $stageOri->save();
        $stageToSwap->save();

        return true;
    }

    public function getLatestOrderValue($stages)
    {
        if ($stages->count() > 0) {
            $largestOrderNum = $stages->first()->order;

            for ($i = 1; $i < $stages->count(); $i++) {
                if ($stages->get($i)->order > $largestOrderNum) {
                    $largestOrderNum = $stages->get($i)->order;
                }
            }

            return $largestOrderNum;
        }

        return -1;
    }

    public function cleanUpOrderValue($event)
    {
        $currentOrder = 0;

        $stages = $event->stages()->orderBy('order', 'asc')->get();

        foreach ($stages as $stage) {
            $stage->order = $currentOrder;
            $stage->save();

            $currentOrder++;
        }
    }
}
