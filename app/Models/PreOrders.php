<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class PreOrders extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preorders';

    protected $appends = ['company_id'];

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
        'buyer_name',
        'phone_number',
        'address',
        'buyer_email'
    ];

    public function preorders_details()
    {
        return $this->hasMany('App\Models\PreOrdersDetails', 'preorder_id', 'id');
    }
    public function getCompanyIdAttribute()
    {
        return 0;
    }
}
