<?php

namespace App\Http\Controllers;

use App\Model\NiftyIndex;
use App\Model\OptionData;
use Illuminate\Http\Request;
use App\Model\Pcr;
use App\Imports\CommonFunctionality;
use App\Model\ParticipantOI;
use App\Model\OpenInterest;
use App\Imports\ShareImport;

class DashboardController extends Controller
{
    public function landingPage()
    {
        $ratios = $this->indexRatios();
        $lastRec = \DB::table('index_ratios')->latest()->first();
        $dataLivePrice = $this->niftyLivePrice();
        $pcr = new Pcr();
        $od = new OptionData();
        $expiries = $od->fnoStocksExpiry('FUTIDX');
        $expiryDate  = $expiries['expiries'][0];
        $cf = new CommonFunctionality();
        $expiryDate = $cf->convertExpiryToDateFormat($expiryDate);
        $optionId = $pcr->getExpiryId('NIFTY', $expiryDate);
        $pcr = $pcr->getPcr($optionId);
        $mood = $this->participantOI();
        $oi = new OpenInterest();
        $avgOi = $oi->watchlistStocks(20);
        $partipantData = $this->tradingActivity();
        return view('dashboard', compact('pcr', 'mood', 'avgOi', 'partipantData', 'dataLivePrice', 'lastRec'));
    }

    public function participantOI()
    {
        $po = new ParticipantOI();
        $mood = $po->participantOIMood();
        return $mood;
    }

    public function strongOI()
    {
        $oi = new OpenInterest();
        $avgOi = $oi->watchlistStocks(20);
        return view('averageOI', compact('avgOi'));
    }

    public function tradingActivity()
    {
        $po = new ParticipantOI();
        $partipantData = $po->participantWiseEquityData();
        return $partipantData;
    }

    public function niftyLivePrice()
    {
        $ni = new NiftyIndex();

        $indexprices = $ni->niftyLivePrice();
        return $indexprices['data'];
    }

    public function indexRatios()
    {
        $ni = new NiftyIndex();
        $ratios = $ni->indexRatios();
        return $ratios;
    }
}
