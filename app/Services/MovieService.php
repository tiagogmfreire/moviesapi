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
            
            $movies = json_decode($response->getBody(), true);;

            $movieList = [];

            foreach ($movies['results'] as $i => $movie) {
                $movieList[$i] = [];

                $movieList[$i]['id'] = $movie['id'];
                $movieList[$i]['title'] = $movie['title'];
                $movieList[$i]['release_date'] = $movie['release_date'];
                $movieList[$i]['poster_path'] = $movie['poster_path'];
                $movieList[$i]['backdrop_path'] = $movie['backdrop_path'];                
            }
            
            return $movieList;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    public function search(string $title)
    {

    }

    public function getDetails($id)
    {
        $endpoint = "https://api.themoviedb.org/3/movie/upcoming";
        $client = new \GuzzleHttp\Client();
        //$id = 5;
        //$value = "ABC";

        $response = $client->request('GET', $endpoint, ['query' => [
            'api_key' => '1f54bd990f1cdfb230adb312546d765d'
        ]]);

        return $response;
    }
}