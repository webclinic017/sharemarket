<?php

namespace App\Model;

use App\Model\OptionData;
use Illuminate\Database\Eloquent\Model;
use App\Imports\CommonFunctionality;

class Pcr extends Model
{
    protected $table = 'pcr';

    public function getPcr($expiryId)
    {
        $pcr = $this->where('oce_id', $expiryId)->orderBy('date', 'DESC')->first();
        return $pcr->pcr;
    }


    public function fnoStocksExpiry()
    {
        $od = new OptionData();
        $expiries = $od->fnoStocksExpiry('FUTSTK');
        return $expiries['expiries'];
    }

    public function getExpiryId($symbol, $expiryDate)
    {
        $underlyingData = \DB::table('option_chain_expiry')
            ->where('symbol', $symbol)
            ->where('expirydate', $expiryDate)
            ->first();
        return $underlyingData->id;
    }
}
