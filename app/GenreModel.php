<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model class for the movie genres
 */
class GenreModel extends Model
{
    use SoftDeletes;

    protected $table = 'genre';
    protected $fillable = array('*');
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
