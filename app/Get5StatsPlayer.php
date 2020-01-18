<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static find($id)
 * @method static create(array $array)
 *
 * @property int $matchid
 * @property int $mapnumber
 * @property string $steamid64
 * @property string $team
 * @property int $rounds_played
 * @property string $name
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property int $flashbang_assists
 * @property int $teamkills
 * @property int $headshot_kills
 * @property int $damage
 * @property int $bomb_plants
 * @property int $bomb_defuses
 * @property int $v1
 * @property int $v2
 * @property int $v3
 * @property int $v4
 * @property int $v5
 * @property int $2k
 * @property int $3k
 * @property int $4k
 * @property int $5k
 * @property int $firstkill_t
 * @property int $firstkill_ct
 * @property int $firstdeath_t
 * @property int $firstdeath_ct
 */

class Get5StatsPlayer extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "get5_stats_players";

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

    ];
}
