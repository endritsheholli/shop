<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $name
 */
class Parameters extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parameters';

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
        'name',
        'parent_id',
        'general'
    ];

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}
