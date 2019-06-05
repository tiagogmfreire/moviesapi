<?php

namespace App\Services;

use \GuzzleHttp\Client;
use function GuzzleHttp\json_decode;

/**
 * Service class to abstract logic regarding retrieving movie information
 * from the TMDb API.
 */
class MovieService
{
    public function upcoming()
    {
        try {

            $url = env('TMDB_API');

            $endpoint = $url . "/movie/upcoming";
            $client = new Client();
            
            $response = $client->request('GET', $endpoint, ['query' => [
                'api_key' => env('TMDB_KEY')
            ]]);

            $statusCode = $response->getStatusCode();
            
            $movies = json_decode($response->getBody(), true);

            $movies = $this->formatMovieList($movies);
            
            return $movies;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    public function genres()
    {
        try {

            $url = env('TMDB_API');

            $endpoint = $url . "/genre/movie/list";
            $client = new Client();
            
            $response = $client->request('GET', $endpoint, ['query' => [
                'api_key' => env('TMDB_KEY')
            ]]);

            $statusCode = $response->getStatusCode();
            
            $genres = json_decode($response->getBody(), true);

            if (isset($genres['genres'])) {
                $genres = $genres['genres'];
            }
                       
            return $genres;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    public function getGenreDictionary()
    {
        $genres = $this->genres();

        $genreList = [];

        foreach ($genres as $genre) {

            $genreList[$genre['id']] = $genre['name'];
        }

        return $genreList;
    }

    public function search($title, $adult = false)
    {
        try {

            $url = env('TMDB_API');

            $endpoint = $url . "/search/movie";
            $client = new Client();
            
            $response = $client->request('GET', $endpoint, ['query' => [
                'api_key' => env('TMDB_KEY'),
                'query' => $title,
                'include_adult' => $adult
            ]]);

            $statusCode = $response->getStatusCode();
            
            $movies = json_decode($response->getBody(), true);

            $movies = $this->formatMovieList($movies);

            return $movies;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    public function getDetails($id)
    {
        try {

            $genreList = $this->getGenreDictionary();

            $url = env('TMDB_API');

            $endpoint = $url . "/movie/" . $id;
            $client = new Client();
            
            $response = $client->request('GET', $endpoint, ['query' => [
                'api_key' => env('TMDB_KEY')
            ]]);

            $statusCode = $response->getStatusCode();
            
            $movieResponse = json_decode($response->getBody(), true);

            $movie = [];
            $movie['id'] = $movieResponse['id'];
            $movie['imdb_id'] = $movieResponse['imdb_id'];
            $movie['title'] = $movieResponse['title'];
            $movie['release_date'] = $movieResponse['release_date'];
            $movie['overview'] = $movieResponse['overview'];
            $movie['poster_path'] = $movieResponse['poster_path'];
            $movie['genres'] = $movieResponse['genres'];
            
            return $movie;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    public function formatMovieList($movies)
    {
        $genreList = $this->getGenreDictionary();

        $movieList = [];

        foreach ($movies['results'] as $i => $movie) {
            $movieList[$i] = [];

            $movieList[$i]['id'] = $movie['id'];
            $movieList[$i]['title'] = $movie['title'];
            $movieList[$i]['release_date'] = $movie['release_date'];
            $movieList[$i]['poster_path'] = $movie['poster_path'];
            $movieList[$i]['backdrop_path'] = $movie['backdrop_path']; 

            foreach ($movie['genre_ids'] as $j => $genre) {

                $movieList[$i]['genres'][$j]['id'] = $genre;
                $movieList[$i]['genres'][$j]['name'] = $genreList[$genre];
            }
        }
        
        return $movieList;
    }
}