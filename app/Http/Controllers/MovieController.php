<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;

/**
 * Controller for the movie related API endpoints
 */
class MovieController extends Controller
{
    public function upcoming(Request $request, MovieService $movieService)
    {
        try {
            $movies = $movieService->upcoming();

            return response()->json($movies);

        } catch (\Exception $e) {
            
            return response()->json('An error has ocorred while processing your request', 500);
        }
    }

    /**
     * Action for the API endpoint that shows the details
     * of a particular movie by it's TMDb id.
     *
     * @param mixed $id
     * @param Request $request
     * @param MovieService $movieService
     * @return void
     */
    public function movie($id, Request $request, MovieService $movieService)
    {
        try {

            $movie = $movieService->getDetails($id);
        
            return response()->json($movie);


        } catch (\Exception $e) {

            return response()->json('An error has ocorred while processing your request', 500);
        }

        
    }
}
