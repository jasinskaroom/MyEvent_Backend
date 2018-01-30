<?php

namespace App\Services;

use App\Models\Locale;
use Illuminate\Support\Collection;

class LocaleService extends BaseService
{

    public function getAvailableLocales()
    {
        return $this->localeAsCollections();
    }

    public function localeAsCollections()
    {
        $plainLocales = config('app.available_locales');

        $locales = new Collection();
        foreach ($plainLocales as $locale) {
            $locales->push(
                $this->localeAsModel($locale)
            );
        }

        return $locales;
    }

    public function localeAsModel($plainLocale)
    {
        return new Locale($plainLocale['name'], $plainLocale['code']);
    }

}
