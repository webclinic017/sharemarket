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
        $this->od->fnoStocksExpiry();
    }
}
