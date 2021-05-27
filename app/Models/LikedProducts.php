<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 */
class LikedProducts extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liked_products';

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
        'user_id, product_id, product_like'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }
}
