<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $table_name
 * @property string $record_id
 * @property string $name
 * @property string $path
 * @property string $created_at
 * @property string $updated_at
 */
class Document extends Model {

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'document';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $fillable = ['table_name', 'record_id', 'file_name', 'path', 'user_id'];

}
