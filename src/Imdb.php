<?php
namespace Jleagle\Imdb;

use Jleagle\CurlWrapper\Curl;
use Jleagle\CurlWrapper\Exceptions\CurlException;
use Jleagle\Imdb\Exceptions\ImdbException;
use Jleagle\Imdb\Responses\Movie;
use Jleagle\Imdb\Responses\Result;

class Imdb
{
  const TYPE_MOVIE = 'movie';
  const TYPE_SERIES = 'series';
  const TYPE_EPISODE = 'episode';

  /**
   * @param string $movie
   * @param string $type
   * @param int    $year
   * @param bool   $tomatoes
   * @param bool   $longPlot
   *
   * @return Movie
   *
   * @throws ImdbException
   */
  public static function retrieve(
    $movie, $type = null, $year = null, $tomatoes = false, $longPlot = false
  )
  {
    $params = [
      'type'     => $type,
      'y'        => $year,
      'plot'     => $longPlot ? 'full' : 'short',
      'tomatoes' => $tomatoes ? 'true' : 'false',
    ];

    if(self::isValidId($movie))
    {
      $params['i'] = $movie;
    }
    else
    {
      $params['t'] = $movie;
    }

    $data = self::_get($params);

    return new Movie(
      [
        'title'      => $data['Title'],
        'year'       => $data['Year'],
        'rated'      => $data['Rated'],
        'released'   => $data['Released'],
        'runtime'    => $data['Runtime'],
        'genre'      => $data['Genre'],
        'director'   => $data['Director'],
        'writer'     => $data['Writer'],
        'actors'     => $data['Actors'],
        'plot'       => $data['Plot'],
        'language'   => $data['Language'],
        'country'    => $data['Country'],
        'awards'     => $data['Awards'],
        'poster'     => $data['Poster'],
        'metascore'  => $data['Metascore'],
        'imdbRating' => $data['imdbRating'],
        'imdbVotes'  => $data['imdbVotes'],
        'imdbId'     => $data['imdbID'],
        'type'       => $data['Type'],
        'response'   => $data['Response'],
      ]
    );
  }

  /**
   * @param string $search
   * @param string $movieType
   * @param string $year
   *
   * @return Result[]
   *
   * @throws ImdbException
   */
  public static function search($search, $movieType = null, $year = null)
  {
    $data = self::_get(
      [
        's'    => $search,
        'type' => $movieType,
        'y'    => $year
      ]
    );

    $return = [];
    foreach($data['Search'] as $result)
    {
      $return[] = new Result(
        [
          'title'     => $result['Title'],
          'year'      => $result['Year'],
          'imdbId'    => $result['imdbID'],
          'movieType' => $result['Type']
        ]
      );
    }
    return $return;
  }

  /**
   * @param $string
   *
   * @return bool
   */
  protected static function isValidId($string)
  {
    return preg_match("/tt\\d{7}/", $string) > 0;
  }

  /**
   * @param array $params
   *
   * @return array
   *
   * @throws ImdbException
   */
  protected static function _get($params)
  {
    $params = array_filter($params);

    $params['r'] = 'json';
    $params['v'] = '1';

    try
    {
      $response = Curl::get('http://www.omdbapi.com/', $params)
        ->run()->getJson();
    }
    catch(CurlException $e)
    {
      throw new ImdbException($e->getResponse()->getErrorMessage());
    }

    if(isset($response['Response']) && $response['Response'] == 'False')
    {
      throw new ImdbException($response['Error']);
    }

    return $response;
  }
}
