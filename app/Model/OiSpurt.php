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
        $url = 'https://www.nseindia.com/live_market/dynaContent/live_analysis/oi_spurts/riseInPriceRiseInOI.json';
        $riseInPriceRiseInOI = $this->si->jsonReturnUrl($url);
        $oiSpurtsDataStructure = $this->oiSpurtsDataStructure($riseInPriceRiseInOI, 1);
        $yn = $this->insert($oiSpurtsDataStructure);
        //dd($yn);
        return $yn;
    }

    public function slideInPriceRiseInOI()
    {
        $url = 'https://www.nseindia.com/live_market/dynaContent/live_analysis/oi_spurts/slideInPriceRiseInOI.json';
        $slideInPriceRiseInOI = $this->si->jsonReturnUrl($url);
        $oiSpurtsDataStructure = $this->oiSpurtsDataStructure($slideInPriceRiseInOI, 2);
        $yn = $this->insert($oiSpurtsDataStructure);
        dd($yn);
        return $yn;
    }

    public function oiSpurtsDataStructure($oiData, $type)
    {
        foreach ($oiData['data'] as $columnName => $columnValue) {
            $moredate = ['date' => date('Y-m-d', strtotime($oiData['time'])), 'type' => $type];
            unset($columnValue['isFO']);
            $oiData['data'][$columnName] = array_merge($columnValue, $moredate);
            $oiData['data'][$columnName]['expiry'] = date('Y-m-d', strtotime($columnValue['expiry']));
            $oiData['data'][$columnName]['strike'] = str_replace(",", "", $columnValue['strike']);
            $oiData['data'][$columnName]['latestOI'] = str_replace(",", "", $columnValue['latestOI']);
            $oiData['data'][$columnName]['previousOI'] = str_replace(",", "", $columnValue['previousOI']);
            $oiData['data'][$columnName]['prevClose'] = str_replace(",", "", $columnValue['prevClose']);
            $oiData['data'][$columnName]['ltp'] = str_replace(",", "", $columnValue['ltp']);
            $oiData['data'][$columnName]['previousOI'] = str_replace(",", "", $columnValue['previousOI']);
            $oiData['data'][$columnName]['oiChange'] = str_replace(",", "", $columnValue['oiChange']);
            $oiData['data'][$columnName]['volume'] = str_replace(",", "", $columnValue['volume']);
            $oiData['data'][$columnName]['valueInCrores'] = str_replace(",", "", $columnValue['valueInCrores']);
            $oiData['data'][$columnName]['premValueInCrores'] = str_replace(",", "", $columnValue['premValueInCrores']);
            $oiData['data'][$columnName]['underlyValue'] = str_replace(",", "", $columnValue['underlyValue']);
           // dd([$columnName], $columnValue, $oiData['data'][$columnName]);
        }
        return $oiData['data'];
    }
}
