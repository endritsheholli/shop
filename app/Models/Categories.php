<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Categories extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
        'parentId',
        'isImportant',
        'image_urls',
        'slug'
    ];

    public function children()
    {
        return $this->hasMany(static::class, 'parentId');
    }

    public function children_LevelOne()
    {
        return $this->menu_item()->where('parent_id',NULL);
    }

}
