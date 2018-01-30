<?php

namespace App\Models;

use Storage;
use AppLocale;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
 

class RuleGame extends Model
{
    use Translatable;
    use SearchableTrait;
     

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'rule_games';

    protected $fillable = ['game_id', 'preview_image_filename', 'preview_image_mime', 'order'];

    public $translatedAttributes = ['rule'];
    public $translationModel = 'App\Models\RuleGameTranslation';
    public $translationForeignKey = 'rule_game_id';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'rule_game_translations.rule' => 3
        ],
        'joins' => [
            'rule_game_translations' => ['rule_games.id', 'rule_game_translations.rule_game_id']
        ]
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove image files
            $model->deleteFile();
        });
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }

    public function getPreviewImageUrl()
    {
        return action('ImageViewController@viewImage', [
            'storage' => 'game',
            'filename' => $this->preview_image_filename
        ]);
    }

    public function deleteFile()
    {
        if (Storage::disk('game')->exists($this->preview_image_filename)) {
            Storage::disk('game')->delete($this->preview_image_filename);
        }
    }
}
