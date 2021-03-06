<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;

/**
 * Controller for the movie related API endpoints
 */
class MovieController extends Controller
{
    /**
     * Action for the /movie/upcoming API endpoint that 
     * returns a list of upcoming movies.
     * 
     * Uses a MovieService class instance that is injected by Lumen's 
     * reflection based depency injection system.
     *
     * @param Request       $request        Lumen request
     * @param MovieService  $movieService   Service class instance
     * 
     * @return JsonResponse The json that contains the returned information
     */
    public function upcoming(Request $request, MovieService $movieService)
    {
        try {

            $movies = $movieService->getUpcomingMovies();

            return response()->json($movies);

        } catch (\Exception $e) {
            
            return response()->json('An error has ocorred while processing your request', 500);
        }
    }

    /**
     * Action for the /movie/{id} API endpoint that returns the details
     * of a particular movie by it's TMDb id.
     * 
     * Uses a MovieService class instance that is injected by Lumen's 
     * reflection based depency injection system.
     *
     * @param mixed         $id             The movie's TMDb id
     * @param Request       $request        Lumen Request
     * @param MovieService  $movieService   Service class instance
     * 
     * @return JsonResponse The json that contains the returned information
     */
    public function movie($id, Request $request, MovieService $movieService)
    {
        try {

            $movie = $movieService->getMovieDetails($id);
        
            return response()->json($movie);


        } catch (\Exception $e) {

            return response()->json('An error has ocorred while processing your request', 500);
        }        
    }

    /**
     * Action for the /movie/search endpoint that returns the
     * result of the search based on the movie title provided.
     * 
     * Uses a MovieService class instance that is injected by Lumen's 
     * reflection based depency injection system. 
     *
     * @param Request $request
     * @param MovieService $movieService
     * 
     * @return JsonResponse The json that contains the returned information
     */
    public function search (Request $request, MovieService $movieService)
    {
        try {

            $title = $request->input('title');            

            $movie = $movieService->searchMovies($title);

            return response()->json($movie);

        } catch (\Exception $e) {
            
            return response()->json('An error has ocorred while processing your request', 500);
        }
    }
}
