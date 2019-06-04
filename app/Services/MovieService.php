<?php

namespace App\Services;

use \GuzzleHttp\Client;

/**
 * Service class to abstract logic regarding retrieving movie information
 * from the TMDb API.
 */
class MovieService
{
    public function upcoming()
    {
        $url = env('TMDB_API');

        $endpoint = $url . "/movie/upcoming";
        $client = new Client();
        
        $response = $client->request('GET', $endpoint, ['query' => [
            'api_key' => env('TMDB_KEY')
        ]]);

        return $response;
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