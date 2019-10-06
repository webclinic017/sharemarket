<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Pcr;
use App\Imports\CommonFunctionality;
use App\Model\ParticipantOI;
use App\Model\OpenInterest;

class DashboardController extends Controller
{
    public function landingPage()
    {
        $pcr = new Pcr();
        $expiries = $pcr->fnoStocksExpiry();
        $cf = new CommonFunctionality();
        $expiryDate = $cf->convertExpiryToDateFormat($expiries[1]);
        $optionId = $pcr->getExpiryId('NIFTY', $expiryDate);
        $pcr = $pcr->getPcr($optionId);
        $mood = $this->participantOI();
        $oi = new OpenInterest();
        $avgOi = $oi->watchlistStocks(20);
        return view('dashboard', compact('pcr', 'mood', 'avgOi'));
    }

    public function participantOI()
    {
        $po = new ParticipantOI();
        $mood = $po->participantOIMood();
        return $mood;
    }
}