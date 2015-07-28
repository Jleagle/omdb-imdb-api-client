<?php
namespace Jleagle\Imdb\Responses;

abstract class AbstractResponse
{
  public function __construct(array $data)
  {
    foreach($data as $field => $value)
    {
      $this->$field = $value;
    }
  }

  public function toArray()
  {
    return get_object_vars($this);
  }
}
