<?php

namespace App\Services;

use \GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use App\GenreModel;
use App\MovieModel;
use App\MovieGenreModel;

/**
 * Service class to abstract logic regarding retrieving movie information
 * from the TMDb API.
 */
class MovieService
{
    /**
     * Method to get a list of the upcoming movies. 
     * 
     * Uses the /movie/upcoming endpoint of the TMDb API.
     * 
     * The $startPage and $endPage are used to call the TMBd endpoint several
     * times and combine the returned values
     * 
     * The response of the service is decoded from json and passed as an array 
     * to the formatMovieList() method to rearrange the array containing the movie objects.
     *
     * @param integer $startPage    (optional)The first page number. Default: 1.
     * @param integer $endPage      (optional)The last page number. Default: 3.
     * 
     * @return array  an array containg the movies found
     */
    public function upcoming($startPage = 1, $endPage = 3)
    {
        try {

            $url = env('TMDB_API');

            $endpoint = $url . "/movie/upcoming";
            $client = new Client();

            $moviesList = [];

            for ($i = $startPage; $i <= $endPage; $i++) {

                $response = $client->request('GET', $endpoint, ['query' => [
                    'api_key' => env('TMDB_KEY'),
                    'page' => $i,
                ]]);
    
                $statusCode = $response->getStatusCode();
                
                $movies = json_decode($response->getBody(), true);
    
                $movies = $this->formatMovieList($movies);

                array_push($moviesList, ...$movies);
            }

            return $moviesList;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to get an array of movie genres from the TMDb API.
     *
     * @return array an array containg all the genres returned
     */
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

    /**
     * Method to rearrange the array of genres into an associative
     * array indexed by the genre's id
     *
     * @return void the rearrenged array
     */
    public function getGenreDictionary()
    {
        $genres = $this->genres();

        $genreList = [];

        foreach ($genres as $genre) {

            $genreList[$genre['id']] = $genre['name'];
        }

        return $genreList;
    }

    /**
     * Method to search for a movie by it's title using the /search/movie TMDb API endpoint.
     *
     * The response of the service is passed is decoded from json and passed as an array 
     * to the formatMovieList() method to rearrange the array containing the movie objects.
     * 
     * @param string    $title  The movie's title used in the search
     * @param integer   $page   (optional)The page number. Default: 1
     * @param boolean   $adult  (optional)An flag to show adult content. Default: false.
     * 
     * @return void
     */
    public function search($title, $page = 1 , $adult = false)
    {
        try {

            $url = env('TMDB_API');

            $endpoint = $url . "/search/movie";
            $client = new Client();
            
            $response = $client->request('GET', $endpoint, ['query' => [
                'api_key' => env('TMDB_KEY'),
                'query' => $title,
                'page' => $page,
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

    /**
     * Method to get the details of a movie by it's id using the TMDb API /movie/{id} endpoint.
     *
     * @param [type] $id
     * @return void
     */
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
            $movie['poster_path'] = env('TMDB_IMG_PATH') . $movieResponse['poster_path'];
            $movie['genres'] = $movieResponse['genres'];
            
            return $movie;

        }  catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * An method to format an array contaning a list of movies.
     * 
     * uses the method getGenreDictionary() to get the genres names 
     * by id.
     * 
     * filters information not used by the app.
     *
     * @param [type] $movies
     * 
     * @return array An array containg the treated information of the movies
     */
    public function formatMovieList($movies)
    {
        $genreList = $this->getGenreDictionary();

        $movieList = [];

        foreach ($movies['results'] as $i => $movie) {
            $movieList[$i] = [];

            $movieList[$i]['id'] = $movie['id'];
            $movieList[$i]['title'] = $movie['title'];
            $movieList[$i]['overview'] = $movie['overview'];
            $movieList[$i]['release_date'] = $movie['release_date'];
            $movieList[$i]['poster_path'] = env('TMDB_IMG_PATH') . $movie['poster_path'];
            $movieList[$i]['backdrop_path'] = env('TMDB_IMG_PATH') . $movie['backdrop_path']; 

            foreach ($movie['genre_ids'] as $j => $genre) {

                $movieList[$i]['genres'][$j]['id'] = $genre;
                $movieList[$i]['genres'][$j]['name'] = $genreList[$genre];
            }
        }
        
        return $movieList;
    }

    public function saveGenres()
    {
        $genres = $this->genres();

        foreach ($genres as $genre) {

            //checking if the genre has been saved before
            $genreModel = GenreModel::where('tmbd_id', $genre['id'])->first();

            if (empty($genreModel)) {
                $genreModel = new GenreModel();

                $genreModel->tmbd_id = $genre['id'];
            }

            $genreModel->name = $genre['name'];

            $genreModel->save();
        }
    }

    public function saveMovies()
    {
        $this->saveGenres();

        $movies = $this->upcoming(1,3);

        foreach ($movies as $movie) {

            $movieModel = MovieModel::where('tmbd_id', $movie['id'])->first();

            if (empty($movieModel)) {
                $movieModel = new MovieModel();

                $movieModel->tmbd_id = $movie['id'];
            }

            $movieModel->title = $movie['title'];
            $movieModel->overview = $movie['overview'];
            $movieModel->poster_path = $movie['poster_path'];
            $movieModel->backdrop_path = $movie['backdrop_path'];
            $movieModel->release_date = $movie['release_date'];
            
            $movieModel->save();

            //saving the movies genres
            foreach ($movie['genres'] as $genre) {

                $genreModel = GenreModel::where('tmbd_id', $genre['id'])->first();

                //checking if the genre has been saved before 
                $movieGenreModel = MovieGenreModel::where('movie_id', $movieModel->id)
                                                    ->where('genre_id', $genreModel->id)
                                                    ->first();

                //if not then save it
                if (empty($movieGenreModel)) {
                    $movieGenreModel = new MovieGenreModel();

                    $movieGenreModel->movie_id = $movieModel->id;
                    $movieGenreModel->genre_id = $genreModel->id;

                    $movieGenreModel->save();
                }

            }

        }
    }

    public function getUpcomingMovies()
    {
        $movies = MovieModel::with('genres')->get();

        foreach ($movies as $i => $movie) {

            $movie->genres = $movie->genres();
        }

        return $movies;
    }
}