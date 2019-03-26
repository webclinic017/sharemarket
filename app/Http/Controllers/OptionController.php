<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ShareImport;
use App\Model\OptionData;

class OptionController extends Controller
{
<<<<<<< HEAD
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

  #todo
  //TO GET ALL WEEKLY EXPIRIES OF INDEX
  //API https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=OPTIDX&u=NIFTY&e=&o=&k=

  //TO GET ALL MONTHLY EXPIRIES AND FNO STOCKS
  //API https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=FUTSTK&u=INFY&e=&o=&k=


=======
    public function optionDataFetch()
    {
        $optionType = ['OPTIDX', 'OPTSTK'];
        $symbol = 'NIFTY';
        $expiryDate = '25APR2019';
        $url = "https://www.nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp?segmentLink=&instrument=$optionType[0]&symbol=$symbol&date=$expiryDate";
        $od = new OptionData();
        $od->optionDataFetch($url);
        dd($data);
    }
>>>>>>> 0a0ed4a15fc2513c31a210bfcd1b2eff64a1f855
}
