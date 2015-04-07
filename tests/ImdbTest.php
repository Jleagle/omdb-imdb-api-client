<?php
use Jleagle\Imdb\Imdb;

class ImdbTest extends PHPUnit_Framework_TestCase
{
  /**
   * @group medium
   */
  public function testRetrieve()
  {
    $imdb = Imdb::search('the matrix');

    $this->assertTrue(is_array($imdb));
    $this->assertEquals('Jleagle\Imdb\Responses\Result', get_class($imdb[0]));
  }

  /**
   * @group medium
   */
  public function testSearch()
  {
    foreach(['the matrix', 'tt0133093'] as $search)
    {
      $imdb = Imdb::retrieve($search);

      $this->assertEquals('Jleagle\Imdb\Responses\Movie', get_class($imdb));
      $this->assertEquals('The Matrix', $imdb->title);

      $array = $imdb->toArray();
      $this->assertTrue(is_array($array));
    }

  }
}
