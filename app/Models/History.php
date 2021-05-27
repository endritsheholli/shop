<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'histories';

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
        'product_id',
        'buyer_id',
        'seller_id',
        'status',
        'order_type',
        'date',
    ];
}
