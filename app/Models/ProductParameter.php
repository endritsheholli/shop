<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $parameter_id
 * @property string $value
 */
class ProductParameter extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_parameter';

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
        'parameter_id',
    ];
}
