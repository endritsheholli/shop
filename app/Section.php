<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Section extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'section';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'key,image,content,image_position'
    ];
}
