<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static find($id)
 * @method static create(array $array)
 *
 * @property int $id
 * @property int $team_id
 * @property string $steam_id_64
 * @property string $name
 *
 * @property ServerTeam $team
 */

class TeamPlayer extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "team_players";

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

    public function team()
    {
        return $this->belongsTo(ServerTeam::class, 'team_id', 'id');
    }
}
