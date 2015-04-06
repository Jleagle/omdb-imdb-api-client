# omdb-imdb-api-client

A package to retrieve movies and TV information from IMDB using the API at omdbapi.com

Retrieve full movie details, if you know the name or ID of the movie:

```php
$movie = Imdb::retrieve('the matrix');

// If you need to be more specific:
// This will also return Rotten Tomatoes ratings & a longer plot
$movie = Imdb::retrieve('the matrix', Imdb::TYPE_MOVIE, 1999, true, true);
```

Search for a movie:

```php
$movies = Imdb::search('the matrix');

// If you need to be more specific:
$movies = Imdb::search('the matrix', Imdb::TYPE_MOVIE, 1999);
```
