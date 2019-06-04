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
        $movie = $movieService->getDetails($id);
        
        return $movie;
    }
}
