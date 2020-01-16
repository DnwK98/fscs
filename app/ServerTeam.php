<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static find($id)
 * @method static create(array $array)
 *
 * @property int $id
 * @property int $server_id
 * @property int $team_number
 * @property string $name
 * @property string $tag
 *
 * @property Server $server
 * @property TeamPlayer[] $players
 */

class ServerTeam extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "server_teams";

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

    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'id');
    }


    public function players()
    {
        return $this->hasMany(TeamPlayer::class, 'team_id', 'id');
    }
}
