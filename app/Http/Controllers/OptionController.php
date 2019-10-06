<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ShareImport;
use App\Model\OptionData;

class OptionController extends Controller
{
    public $od;

    public function __construct()
    {
        $this->od = new OptionData();
    }

    public function optionDataFetch()
    {
        dd($this->od->fnoStocksExpiry());
        $optionType = ['OPTIDX', 'OPTSTK'];
        $symbol = 'NIFTY';
        $expiryDate = '25APR2019';
        $url = "https://www.nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp?segmentLink=&instrument=$optionType[0]&symbol=$symbol&date=$expiryDate";
        //dd($url);
        $data = $this->od->optionDataFetch($url);
        dd($data);
    }

    public function stockOptionChain()
    {
        $underlyingExpiries = $this->od->stockOptionData();
    }

    public function indexOptionChain()
    {
        $underlyingExpiries = $this->od->indexOptionData();
    }
    public function jabraAction()
    {
      $action = $this->od->jabardastAction();
      return view ('jabraaction',compact('action'));
    }
    public function jabraIV()
    {
      $action = $this->od->jabardastIV();
      return view ('jabraIV',compact('action'));
    }
    public function niftyExpiryWise($expiry)
    {
      $action = $this->od->niftyExpiryWise($expiry);
      return view ('jabraIV',compact('action'));
    }
}
