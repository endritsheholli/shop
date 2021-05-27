<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCard extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shopping_card';

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
        'user_id',
        'quantity',
        'status',
        'comment'
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }
}
