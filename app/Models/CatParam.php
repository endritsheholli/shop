<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $category_id
 * @property int $param_id
 */
class CatParam extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cat_param';

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
        'category_id',
        'parameter_id',
    ];
}

