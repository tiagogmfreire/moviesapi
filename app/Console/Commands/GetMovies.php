<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MovieService;

class GetMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:getmovies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the movie list in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $movieService = new MovieService();

            $movieService->saveMovies();
            

        } catch (\Exception $e) {
            $this->error("An error occurred while updating the movies");
        }
    }
}
