<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model class for the relational table between movies and genres
 */
class MovieGenreModel extends Model
{
    use SoftDeletes;

    protected $table = 'movie_genre';
    protected $fillable = array('*');
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
