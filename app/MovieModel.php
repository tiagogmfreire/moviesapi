<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovieModel extends Model
{
    use SoftDeletes;

    protected $table = 'movie';
    protected $fillable = array('*');
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function genres()
    {
        return $this->belongsToMany('App\GenreModel', 'movie_genre', 'movie_id', 'genre_id');        
    }
}
