<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\OpenInterest;
use App\Model\OiSpurt;

class OpenInterestController extends Controller
{
    public $oi;

    public function __construct()
    {
        $this->oi = new OpenInterest();
    }

    public function avgOIPerDay()
    {
        $day = 19;
        $latestDate = $this->oi->getLatestDate();
        $curOi = $this->oi->currentOI($latestDate);
        $avgOi = $this->oi->avgOIPerDay($day, $latestDate);
        $finalList = $this->oi->comparisonWithCurrentToAvg($curOi, $avgOi);
        $this->oi->addWatchlist($finalList);
    }

    public function oiSpurts()
    {
        $oiSpurts = new OiSpurt();
        $oiSpurts->riseInPriceRiseInOI();
        $oiSpurts->slideInPriceRiseInOI();
    }
}
