<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static find($id)
 * @method static create(array $array)
 *
 * @property int $id
 * @property int $match_id
 * @property string $map
 * @property int $port
 * @property string $status
 * @property ServerTeam[] $teams
 */

class Server extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "servers";

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

    public static function Boot()
    {
        parent::Boot();

        self::creating(function ($server) {
            if (!isset($server->match_id)) {
                $server->match_id = (int)time();
            }
        });
    }

    public function teams()
    {
        return $this->hasMany(ServerTeam::class, 'server_id', 'id');
    }
}
