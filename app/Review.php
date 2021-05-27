<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Review extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'review';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $fillable = [
        'message,product_id'
    ];
}
