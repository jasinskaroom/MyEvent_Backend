<?php

namespace App\Models;

use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
 

class RegisterFormField extends Model
{
    use Translatable;
     

    const KEY_PREFIX = 'custom_field';

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'register_form_fields';

    protected $fillable = ['order'];

    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Models\RegisterFormFieldTranslation';
    public $translationForeignKey = 'field_id';

    public function getFormKey()
    {
        return RegisterFormField::KEY_PREFIX . '_' . $this->id;
    }

    public function getAllLocalesString($separator = '|')
    {
        $locales = AppLocale::getAvailableLocales();

        $localeString = '';
        $count = 0;
        foreach($locales as $locale) {
            $localeString = $localeString . $this->translate($locale->code)->name;

            if ($count != count($locales) - 1) {
                $localeString = $localeString . ' ' . $separator . ' ';
            }

            $count++;
        }

        return $localeString;
    }
}
