<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ShareImport;
use App\Model\OptionData;

class OptionController extends Controller
{
  public function optionDataFetch()
  {
    $optionType = ['OPTIDX','OPTSTK'];
    $symbol = 'INFY';
    $expiryDate = '25APR2019';
    $url = "https://www.nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp?segmentLink=&instrument=$optionType[1]&symbol=$symbol&date=$expiryDate";
    $od = new OptionData();
    $od->optionDataFetch($url);
    dd($data);
  }
}
