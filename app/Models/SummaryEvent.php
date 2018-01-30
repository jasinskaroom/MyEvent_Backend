<?php

namespace App\Models;

use AppLocale;
use Illuminate\Database\Eloquent\Model;

class SummaryEvent extends Model
{
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'summary_events';

    protected $fillable = ['event_name', 'event_id', 'num_participant', 'num_stage', 'num_game'];
}
