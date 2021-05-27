<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LikedProducts;

class Products extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $appends = ['is_like'];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'title',
        'description',
        'price',
        'previous_price',
        'image_urls',
        'status',
        'isImportant',
        'slug',
        'parms',
        'weight',
        'likes',
        'quantity',
        'posta_free',
        'discount'
    ];

    public function preorders_details()
    {
        return $this->belongsTo('App\Models\PreOrdersDetails', 'id', 'product_id');
    }

    protected $casts = [
        'id' => 'int',
        'image_urls' => 'array'
    ];

    public function getIsLikeAttribute()
    {
        $user_id = 0;
        if (\Auth::user()) {
            $user_id = \Auth::user()->id;
        }

        $like = LikedProducts::where('user_id', $user_id)->where('product_id', $this->id)->first();
        $bool = false;
        if ($like) {
            $bool = true;
        }
        return $bool;
    }
}
