# Upcoming movies API

https://movies.tiagofreire.dev

The API was built using PHP 7.2 and Laravel's micro framework: Lumen 5.8.

This API uses the The Movie Database API version 3 to gather information about upcoming movie titles.

The architeture is simple and only relies on 1 controller class, 1 service class , 3 model classes and 2 more classes for the php artisan commands.

## Installation

> **You need a API key from [The Movie Database](https://developers.themoviedb.org/3/getting-started/introduction)**

0. Set up your [PHP enviroment for Laravel/Lumen (5.8)](https://laravel.com/docs/5.8/installation)
1. Clone this repo
2. run ```composer install```
3. Create the .env file: ```cp .env.example .env```
4. Create the app key: ```php artisan key:generate```
5. Create the database
6. Add the database configuration to the .env file
7. Run the database migrations: ```php artisan migrate```
8. Point your domain to the /public folder
9. Get the movie data using: ```php artisan movie:getmovies```

## Getting data using artisan

The logic to store the data of the upcoming movies is avaible in two custom php artisan commands:

> movie:getgenres

Saves the information about movie genres and:

> movie:getmovies

Saves information of the next 100 upcoming movies by calling the TMDb's API 5 times (pages 1 to 5)

At the momment this process is manual. But Laravel/Lumen artisan commands can be schedulled using the task scheduller and a linux cronjob so the updating of the data can be done automatically whithout changes in the codebase.

## Endpoints

There are 3 endpoints:

- /movie/upcoming To show the upcoming movies
- /movie/{id} to show the details of a movie
- movie/search?title={title} to search for a movie

## Database

The database tables were made using database migrations provided by the php artisan migrate tool.

Only three tables where needed to store movie and genre data.

## Development

The eloquent and facade features have been enabled.

It uses the Flipbox\LumenGenerator package installed via the composer package manager to provide all the php artisan command line tooling avaible in the full Laravel framework.

It also uses the Guzzle, PHP HTTP client library to make requests do the TMBd's API. Guzzle requires the symfony/psr-http-message-bridge composer package to function properly.

The barryvdh/laravel-cors composer package has been added to provide CORS headers in the requests so the frontend client and the backend API could be deployed in different domains. 

This API uses the The Movie Database API version 3 to gather information about upcoming movie titles.

It has methods mapped to the TMBd's API endpoints that return upcoming movies, movie gender list, search results and details about a movie.

But those methods are only used internally to gather data of the upcoming movies (100 of them at the moment) and save that information in a local PostgreSQL database.

By doing that the frontend client is not limited by the TMDb's rate limit and the API can work reliably even if the external API is offline.

Because of this the endpoints provided to the frontend app only return information about the movies stored locally.


