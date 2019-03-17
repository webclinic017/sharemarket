<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class OptionData extends Model
{
  public $shareImp;
  public $context;
  protected $table = 'participant_oi';

  public function __construct()
  {
      $this->shareImp = new ShareImport();
      $this->context = $this->shareImp->contextValue();
  }

  public function optionDataFetch($url)
  {
    $data = $this->shareImp->get($url);
    $dataById = $this->shareImp->findTag('tr');
    foreach ($dataById as $tag) {
      $searchChar = ["\t\r","\r","\t"," ","Chart"];
      $dummy[] =  str_replace($searchChar,'',$tag->nodeValue);
    }
    foreach ($dummy as $key => $value) {
      $finalData[] = array_values(array_filter(explode("\n", $value)));
    }
      dd($finalData);


    return $dataArray;
  }
}
