<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class OiSpurt extends Model
{
    public $si;
    protected $table = 'oi_spurt';

    public function __construct()
    {
        $this->si = new ShareImport();
    }

    public function riseInPriceRiseInOI()
    {
        $yn = false;
        $url = 'https://www1.nseindia.com/live_market/dynaContent/live_analysis/oi_spurts/riseInPriceRiseInOI.json';
        $riseInPriceRiseInOI = $this->si->jsonReturnUrl($url);
        $fetchDate = $this->lastDateData($riseInPriceRiseInOI['time'], 1);
        if ($fetchDate) {
            $oiSpurtsDataStructure = $this->oiSpurtsDataStructure($riseInPriceRiseInOI, 1, $fetchDate);
            $yn = $this->insert($oiSpurtsDataStructure);
        }
        //dd($yn);
        return $yn;
    }

    /**
     * This function checks if last record date same as fetch record date or not
     * @param $oiDate
     * @return bool|false|string
     */
    public function lastDateData($oiDate, $type)
    {
        $fetchDate = date('Y-m-d', strtotime($oiDate));
        $fdResult = $this->where('type', $type)->latest('date')->first();
        if (isset($fdResult->date) && $fdResult->date === $fetchDate) {
            return false;
        } else {
            return $fetchDate;
        }
    }

    public function oiSpurtsDataStructure($oiData, $type, $fetchDate)
    {
        $oiSpurtData = array();
        foreach ($oiData['data'] as $columnName => $columnValue) {
            $oiSpurtData['data'][$columnName]['date'] = $fetchDate;
            $oiSpurtData['data'][$columnName]['type'] = $type;
            $oiSpurtData['data'][$columnName]['symbol'] = str_replace(",", "", $columnValue['symbol']);
            $oiSpurtData['data'][$columnName]['instrument'] = str_replace(",", "", $columnValue['instrument']);
            $oiSpurtData['data'][$columnName]['expiry'] = date('Y-m-d', strtotime($columnValue['expiry']));
            $oiSpurtData['data'][$columnName]['optionType'] = str_replace(",", "", $columnValue['optionType']);
            $oiSpurtData['data'][$columnName]['percLtpChange'] = str_replace(",", "", $columnValue['percLtpChange']);
            $oiSpurtData['data'][$columnName]['strike'] = str_replace(",", "", $columnValue['strike']);
            $oiSpurtData['data'][$columnName]['ltp'] = str_replace(",", "", $columnValue['ltp']);
            $oiSpurtData['data'][$columnName]['latestOI'] = str_replace(",", "", $columnValue['latestOI']);
            $oiSpurtData['data'][$columnName]['previousOI'] = str_replace(",", "", $columnValue['previousOI']);
            $oiSpurtData['data'][$columnName]['prevClose'] = str_replace(",", "", $columnValue['prevClose']);
            $oiSpurtData['data'][$columnName]['previousOI'] = str_replace(",", "", $columnValue['previousOI']);
            $oiSpurtData['data'][$columnName]['oiChange'] = str_replace(",", "", $columnValue['oiChange']);
            $oiSpurtData['data'][$columnName]['volume'] = str_replace(",", "", $columnValue['volume']);
            $oiSpurtData['data'][$columnName]['valueInCrores'] = str_replace(",", "", $columnValue['valueInCrores']);
            $oiSpurtData['data'][$columnName]['premValueInCrores'] = str_replace(",", "", $columnValue['premValueInCrores']);
            $oiSpurtData['data'][$columnName]['underlyValue'] = str_replace(",", "", $columnValue['underlyValue']);
        }
        return $oiSpurtData['data'];
    }

    public function slideInPriceRiseInOI()
    {
        $yn = false;
        $url = 'https://www1.nseindia.com/live_market/dynaContent/live_analysis/oi_spurts/slideInPriceRiseInOI.json';
        $slideInPriceRiseInOI = $this->si->jsonReturnUrl($url);
        $fetchDate = $this->lastDateData($slideInPriceRiseInOI['time'], 2);
        if ($fetchDate) {
            $oiSpurtsDataStructure = $this->oiSpurtsDataStructure($slideInPriceRiseInOI, 2, $fetchDate);
            $yn = $this->insert($oiSpurtsDataStructure);
        }
        //  dd($yn);
        return $yn;
    }
}
