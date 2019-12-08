<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;
use App\Imports\CommonFunctionality;
use DB;

class OptionData extends Model
{
    public $shareImp;
    public $cf;
    public $context;
    public $optionType;
    protected $table = 'option_chain';

    public function __construct()
    {
        $this->shareImp = new ShareImport();
        $this->cf = new CommonFunctionality();
        $this->context = $this->shareImp->contextValue();
        $this->optionType = ['index' => 'OPTIDX', 'stock' => 'OPTSTK'];
    }

    public function stockOptionData($insertYN)
    {
        $expiries = $this->fnoStocksExpiry('FUTIDX');
        $expiriesAndUnderlying = $this->fnoStocksExpiry('FUTSTK');

        if (isset($expiries['expiries'], $expiriesAndUnderlying['underlyings'])) {
            $this->optionChainExpiry($expiries['expiries'], $expiriesAndUnderlying, $this->optionType['stock'], $insertYN);
        }
    }

    public function fnoStocksExpiry($optionType)
    {
        $fnoData = json_decode(file_get_contents("https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=$optionType&u=infy", false, $this->context), true);
        return $fnoData;
    }

    public function optionChainExpiry(array $expiryDate, array $expiries, $optionType, $insertYN)
    {
        $pcrData = array();

        for ($i = 0; $i < count($expiryDate); $i++) {
            if (!$insertYN && $i > 1 && $optionType === 'OPTSTK') {
                dd($insertYN, $optionType, $i);
                break;
            }
            for ($j = 0; $j < count($expiries['underlyings']); $j++) {
                DB::transaction(function () use ($expiryDate, $expiries, $optionType, $i, $j, $insertYN) {
                    $optionRawData = array();
                    $optionRawData = $this->getOptionData($expiries['underlyings'][$j], $expiryDate[$i], $optionType);
                    if (isset($optionRawData[0][2])) {
                        $fetchDate = $this->optionPullDataDate($optionRawData[0][2]);
                        $optionChainExpiryId = $this->getOptionChainExpiryId($expiries['underlyings'][$j], $expiryDate[$i], 'm', $fetchDate, $insertYN);
                        if (isset($fetchDate) && $optionChainExpiryId) {
                            $yn = $this->getOptionChainDataStructure($fetchDate, $expiries['underlyings'][$j], $optionRawData, $optionChainExpiryId, $i, $insertYN);
                            $pcrData[] = $this->pcrData($fetchDate, $optionRawData[count($optionRawData) - 2], $optionChainExpiryId);
                            if ($insertYN) {
                                $this->dataInsert('pcr', $pcrData);
                                echo $expiries['underlyings'][$j] . " of " . $expiryDate[$i] . " option chain and PCR inserted\n";
                            }
                        } else {
                            if ($insertYN)
                                echo $expiries['underlyings'][$j] . " of " . $expiryDate[$i] . " option chain and PCR already present\n";
                        }
                    }
                    //dd($optionRawData);
                });
            }
        }
        echo "Total Stocks" . count($expiries['underlyings']) . "\n";
        return "All Stock Option chain inserted\n";
    }

    public function getOptionData($symbol, $expiryDate, $optionType)
    {
        $url = "https://www.nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp?segmentLink=&instrument=$optionType&symbol=$symbol&date=$expiryDate";
        $data = $this->optionDataFetch($url);
        return $data;
    }

    public function optionDataFetch($url)
    {
        //dd($this->fnoStocksExpiry());
        $data = $this->shareImp->get($url);
        $dataById = $this->shareImp->findTag('tr');
        $dummy = $finalData = [];
        foreach ($dataById as $tag) {
            $searchChar = ["\t\r", "\r", "\t", " ", "Chart", ","];
            $dummy[] = str_replace($searchChar, '', $tag->nodeValue);
        }

        foreach ($dummy as $key => $value) {
            $finalData[] = array_values(array_filter(explode("\n", $value)));
        }
        //dd($finalData);
        return $finalData;
    }

    public function optionPullDataDate($strDate)
    {
        $strDate = "$strDate[4]$strDate[5]$strDate[6] $strDate[7]$strDate[8] $strDate[9]$strDate[10]$strDate[11]$strDate[12]";
        $fetchDate = date('Y-m-d', strtotime($strDate));
        return $fetchDate;
    }

    public function getOptionChainExpiryId($symbol, $expiry, $expiry_type, $fetchdate, $insertYN)
    {
        $expiry = $this->cf->convertExpiryToDateFormat($expiry);

        $underlying = DB::table('option_chain_expiry')
            ->where('symbol', $symbol)
            ->where('expirydate', $expiry)
            ->first();

        if (isset($underlying->id)) {
            $underlyingData = DB::table('option_chain')
                ->where('oce_id', $underlying->id)
                ->where('date', $fetchdate)
                ->first();

            //checks record already present or not if yes then return 0 otherwise return option_chain_expiry id
            if (isset($underlyingData->id) && $underlyingData->id && $insertYN) {
                return 0;
            } else {
                return $underlying->id;
            }
        } else {
            // if option chain expiry fetches record 1st time then it must enter 1st
            $underlyingId = DB::table('option_chain_expiry')->insertGetId(['symbol' => $symbol, 'expirydate' => $expiry,
                'expiry_type' => $expiry_type]);
        }
        return $underlyingId;
    }

    public function getOptionChainDataStructure($fetchDate, $underlying, array $optionRawData, $optionChainExpiryId, $expiryNumber, $insertYN)
    {
        if (isset($fetchDate)) {
            $limit = count($optionRawData);
            $optionChain = array();
            for ($k = 5; $k < $limit - 2; $k++) {
                $optionChainColumns = array();
                $optionChainColumns['date'] = $fetchDate;
                $optionChainColumns['oce_id'] = $optionChainExpiryId;
                $optionChainColumns['calloi'] = is_numeric($optionRawData[$k][0]) ? $optionRawData[$k][0] : 0;
                $optionChainColumns['callvolume'] = is_numeric($optionRawData[$k][2]) ? $optionRawData[$k][2] : 0;
                $optionChainColumns['callchnginoi'] = is_numeric($optionRawData[$k][1]) ? $optionRawData[$k][1] : 0;
                $optionChainColumns['calliv'] = is_numeric($optionRawData[$k][3]) ? $optionRawData[$k][3] : 0;
                $optionChainColumns['callltp'] = is_numeric($optionRawData[$k][4]) ? $optionRawData[$k][4] : 0;
                $optionChainColumns['callnetchng'] = is_numeric($optionRawData[$k][5]) ? $optionRawData[$k][5] : 0;
                $optionChainColumns['callbidqty'] = is_numeric($optionRawData[$k][6]) ? $optionRawData[$k][6] : 0;
                $optionChainColumns['callbidprice'] = is_numeric($optionRawData[$k][7]) ? $optionRawData[$k][7] : 0;
                $optionChainColumns['callaskprice'] = is_numeric($optionRawData[$k][8]) ? $optionRawData[$k][8] : 0;
                $optionChainColumns['callaskqty'] = is_numeric($optionRawData[$k][9]) ? $optionRawData[$k][9] : 0;
                $optionChainColumns['strikeprice'] = is_numeric($optionRawData[$k][10]) ? $optionRawData[$k][10] : 0;
                $optionChainColumns['putoi'] = is_numeric($optionRawData[$k][20]) ? $optionRawData[$k][20] : 0;
                $optionChainColumns['putchnginoi'] = is_numeric($optionRawData[$k][19]) ? $optionRawData[$k][19] : 0;
                $optionChainColumns['putvolume'] = is_numeric($optionRawData[$k][18]) ? $optionRawData[$k][18] : 0;
                $optionChainColumns['putiv'] = is_numeric($optionRawData[$k][17]) ? $optionRawData[$k][17] : 0;
                $optionChainColumns['putltp'] = is_numeric($optionRawData[$k][16]) ? $optionRawData[$k][16] : 0;
                $optionChainColumns['putnetchng'] = is_numeric($optionRawData[$k][15]) ? $optionRawData[$k][15] : 0;
                $optionChainColumns['putbidqty'] = is_numeric($optionRawData[$k][11]) ? $optionRawData[$k][11] : 0;
                $optionChainColumns['putbidprice'] = is_numeric($optionRawData[$k][12]) ? $optionRawData[$k][12] : 0;
                $optionChainColumns['putaskprice'] = is_numeric($optionRawData[$k][13]) ? $optionRawData[$k][13] : 0;
                $optionChainColumns['putaskqty'] = is_numeric($optionRawData[$k][14]) ? $optionRawData[$k][14] : 0;
                $optionChainColumns['expiry'] = $expiryNumber;
                $ivRatio = $this->getIVRatio($optionChainColumns['putiv'], $optionChainColumns['calliv']);
                $optionChainColumns['ivRatio'] = $ivRatio;
                $changeInOIDataConfig = $this->getOptionChangeInOIDataConfig($underlying);
                $watchList = $this->getWatchList($optionChainColumns, $expiryNumber, $changeInOIDataConfig);

                if ($insertYN == false && $watchList == 1) {
                    echo $underlying . " expiry $expiryNumber Strike price " . $optionChainColumns['strikeprice'] . " chng in oi call " .
                        $optionChainColumns['callchnginoi'] . " chng in OI Put " . $optionChainColumns['putchnginoi'] .
                        "iv RATIO " . $ivRatio . " Call LTP " . $optionChainColumns['callltp']. " Put LTP ". $optionChainColumns['putltp']. "\n";
                }
                $optionChainColumns['watchlist'] = $watchList;
                $optionChain[] = $optionChainColumns;
            }

            if ($insertYN) {
                $yn = $this->dataInsert('option_chain', $optionChain);
                return $yn;
            }
        }
        return $insertYN;
    }

    public function getIVRatio($putiv, $calliv)
    {
        $ivRatio = 0;
        if (isset($putiv, $calliv) &&
            is_numeric($putiv) && is_numeric($calliv)) {
            if ($calliv > 0) {
                $ivRatio = $putiv / $calliv;
            }
        }
        return $ivRatio;
    }

    public function getOptionChangeInOIDataConfig($underlying)
    {
        $changeInOIDataConfig = array();
        if ($underlying === 'BANKNIFTY') {
            $changeInOIDataConfig = array(0 => 100000, 1 => 10000, 2 => 1000);
        } else {
            $changeInOIDataConfig = array(0 => 1000000, 1 => 100000, 2 => 10000);
        }
        return $changeInOIDataConfig;
    }

    public function getWatchList($optionChainColumns, $expiryNumber, $changeInOIDataConfig)
    {
        $watchList = 0;
        if ($optionChainColumns['ivRatio'] > 0 &&
            ($optionChainColumns['ivRatio'] >= 3 || $optionChainColumns['ivRatio'] < 0.5)) {
            $watchList = 1;
        }
        if (($optionChainColumns['callchnginoi'] > $changeInOIDataConfig[$expiryNumber] ||
            $optionChainColumns['putchnginoi'] > $changeInOIDataConfig[$expiryNumber])) {
            $watchList = 1;
        }
        return $watchList;
    }

    public function dataInsert($tableName, $tableData)
    {
        $yn = DB::table($tableName)->insert($tableData);
        return $yn;
    }

    public function pcrData($fetchDate, $pcrRawData, $optionChainExpiryId)
    {
        $pcrData['date'] = $fetchDate;
        $pcrData['oce_id'] = $optionChainExpiryId;
        $pcrData['totalcalloi'] = is_numeric($pcrRawData[1]) ? $pcrRawData[1] : 0;
        $pcrData['totalcallvolume'] = is_numeric($pcrRawData[2]) ? $pcrRawData[2] : 0;
        $pcrData['totalputvolume'] = is_numeric($pcrRawData[3]) ? $pcrRawData[3] : 0;
        $pcrData['totalputoi'] = is_numeric($pcrRawData[4]) ? $pcrRawData[4] : 0;
        if ($pcrData['totalcalloi'] > 0) {
            $pcrData['pcr'] = $pcrData['totalputoi'] / $pcrData['totalcalloi'];
        }
        return $pcrData;
    }

    public function indexOptionData($insertYN)
    {
        $expiriesAndUnderlying = $this->fnoStocksExpiry('FUTIDX');
        if (isset($expiriesAndUnderlying['expiries'], $expiriesAndUnderlying['underlyings'])) {
            $this->optionChainExpiry($expiriesAndUnderlying['expiries'], $expiriesAndUnderlying, $this->optionType['index'], $insertYN);
        }
    }

    public function jabardastAction()
    {
        $action = \DB::table('option_chain')->join('option_chain_expiry AS oc', 'oce_id', '=', 'oc.id')
            ->WHERE('callchnginoi', '>', '1000000')
            ->orWhere('putchnginoi', '>', '1000000')
            ->orderBy('date', 'desc')
            ->select('DATE', 'oc.expirydate', 'strikeprice', 'callchnginoi', 'putchnginoi', 'calliv',
                'putiv', 'ivratio', 'oc.symbol', 'callltp', 'putltp', 'oce_id'
            )
            ->paginate(10);
        return $action;
    }

    public function jabardastActionWatchlist()
    {
        $action = \DB::table('option_chain')->join('option_chain_expiry AS oc', 'oce_id', '=', 'oc.id')
            ->WHERE('watchlist', '=', '1')
            ->orderBy('date', 'desc')
            ->select('DATE', 'oc.expirydate', 'strikeprice', 'callchnginoi', 'putchnginoi', 'calliv',
                'putiv', 'ivratio', 'oc.symbol', 'callltp', 'putltp', 'oce_id'
            )
            ->paginate(10);
        return $action;
    }

    public function moreThanHundredIV()
    {
        $action = \DB::table('option_chain')->join('option_chain_expiry AS oc', 'oce_id', '=', 'oc.id')
            ->WHERE('calliv', '>', 100)
            ->orWHERE('putiv', '>', 100)
            ->orderBy('date', 'desc')
            ->select('DATE', 'oc.expirydate', 'strikeprice', 'callchnginoi', 'putchnginoi', 'calliv',
                'putiv', 'ivratio', 'oc.symbol', 'callltp', 'putltp', 'oce_id'
            )
            ->paginate(10);
        return $action;
    }

    public function jabardastIV()
    {
        $action = \DB::select("SELECT DATE,oc.expirydate,strikeprice,callchnginoi,putchnginoi,calliv,
                putiv,ivratio,oc.symbol,callltp, putltp, oce_id FROM option_chain JOIN option_chain_expiry oc ON
                oce_id = oc.id WHERE ivratio > 3
                ORDER BY `date` DESC");
        return $action;
    }

    public function niftyExpiryWise($expiryNumber)
    {
        $action = \DB::select("SELECT DATE,oc.expirydate,strikeprice,callchnginoi,putchnginoi,calliv,
                putiv,ivratio,oc.symbol,callltp, putltp, oce_id FROM option_chain JOIN option_chain_expiry oc ON
                oce_id = oc.id WHERE expiry = ? AND oc.symbol = 'NIFTY' AND watchlist = 1
                ORDER BY `date` DESC", [$expiryNumber]);
        return $action;
    }

    public function latestPremiums()
    {
        $action = \DB::select("SELECT
                                oce_id,
                                    date,
                                    oce.expirydate,
                                    strikeprice,
                                    callchnginoi,
                                    callvolume,
                                    putchnginoi,
                                    putvolume,
                                    calliv,
                                    putiv,
                                    ivratio,
                                    callltp,
                                    putltp,
                                    oce.symbol
                                FROM
                                    `option_chain` oc 
                                        JOIN
                                    option_chain_expiry oce ON oce_id = oce.id and date = oce.expirydate
                                order by date desc;");
        return $action;
    }

    public function latestPremiumDataStructure(array $premiumRawData)
    {
        $premiumData = [];
        for ($i = 0; $i < count($premiumRawData); $i++) {
            $premiumData[$premiumRawData[$i]->oce_id][$premiumRawData[$i]->strikeprice] = $premiumRawData[$i];
        }
        return $premiumData;
    }

}
