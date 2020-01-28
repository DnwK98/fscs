<?php


namespace App;


use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventEntity
 *
 * @property string name
 * @property string int_index
 * @property int int_1
 * @property int int_2
 * @property int int_3
 * @property string string_index
 * @property string string_1
 * @property string content
 * @property DateTime created
 *
 */
class EventEntity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "events";

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
        'created' => 'datetime',
    ];
}
