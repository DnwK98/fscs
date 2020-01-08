<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static find($id)
 * @method static create(array $array)
 *
 * @property int $matchid
 * @property int $mapnumber
 * @property DateTime $start_time
 * @property DateTime $end_time
 * @property string $winner
 * @property string $mapname
 * @property int $team1_score
 * @property int $team2_score
 */
class Get5StatsMap extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "get5_stats_maps";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
