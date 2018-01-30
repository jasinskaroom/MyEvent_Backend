<?php

namespace App\Services;

use AppLocale;
use App\Models\Event;
use App\Models\EventBanner;

class EventBannerService extends BaseService
{
    public function createBanner($event, $data)
    {
        $this->cleanUpOrderValue($event);

        $bannerData = [
            'event_id' => $event->id,
            'order' => $this->getLatestOrderValue($event->banners) + 1
        ];

        $locales = AppLocale::getAvailableLocales();
        // Save image
        foreach($locales as $locale) {
            $bannerData[$locale->code] = $this->saveBannerFile($data[$locale->code]);
            $bannerData[$locale->code]['name'] = $data['name_'.$locale->code];
        }

        $banner = EventBanner::create($bannerData);
        return $banner;
    }

    public function updateBanner($banner, $data)
    {
        $locales = AppLocale::getAvailableLocales();
        // Check whether has new image chosen
        // If exist we remove the current one and add in the new one
        foreach($locales as $locale) {

            if (isset($data[$locale->code])) {
                // Remove current file
                $banner->deleteTranslations($locale->code);

                // Save new file
                $newFileMeta = $this->saveBannerFile($data[$locale->code]);

                $bannerTranslation = $banner->getNewTranslation($locale->code);
                $bannerTranslation->fill($newFileMeta);
                $bannerTranslation->banner_id = $banner->id;
                $bannerTranslation->name = $data['name_'.$locale->code];
                $bannerTranslation->save();
            }
            else {
                $banner->translate($locale->code)->name = $data['name_'.$locale->code];
                $banner->save();
            }
        }

        return EventBanner::find($banner->id);
    }

    public function swapBanner($bannerOriId, $bannerToSwapId)
    {
        $bannerOri = EventBanner::find($bannerOriId);
        $bannerToSwap = EventBanner::find($bannerToSwapId);
        
        if (is_null($bannerOri) || is_null($bannerToSwap)) {
            return false;
        }

        $tempOrder = $bannerOri->order;
        $bannerOri->order = $bannerToSwap->order;
        $bannerToSwap->order = $tempOrder;

        $bannerOri->save();
        $bannerToSwap->save();

        return true;
    }

    public function getLatestOrderValue($banners)
    {
        if ($banners->count() > 0) {
            $largestOrderNum = $banners->first()->order;

            for ($i = 1; $i < $banners->count(); $i++) {
                if ($banners->get($i)->order > $largestOrderNum) {
                    $largestOrderNum = $banners->get($i)->order;
                }
            }

            return $largestOrderNum;
        }

        return -1;
    }

    public function cleanUpOrderValue($event)
    {
        $currentOrder = 0;

        $banners = $event->banners()->orderBy('order', 'asc')->get();

        foreach ($banners as $banner) {
            $banner->order = $currentOrder;
            $banner->save();

            $currentOrder++;
        }
    }

    public function saveBannerFile($uploadFile)
    {
        // Save photo
        $path = $uploadFile->store('event_banner');

        // Get filename
        $filename = explode('/', $path)[1];
        $mime = $uploadFile->getMimeType();

        return [
            'filename' => $filename,
            'mime' => $mime
        ];
    }
}
