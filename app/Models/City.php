<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property OrderDetail[] $orderDetails
 * @property UserDetail[] $userDetails
 */
class City extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
