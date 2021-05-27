<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class PreOrdersDetails extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preorder_details';

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
        'preorders_id',
        'product_id',
        'quantity',
        'comment'
    ];

    public function preorders()
    {
        return $this->belongsTo('App\Models\PreOrders');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Products');
    }
}
