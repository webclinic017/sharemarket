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

    public function stockOptionData()
    {
        $expiriesAndUnderlying = $this->fnoStocksExpiry();
        if (isset($expiriesAndUnderlying['expiries'], $expiriesAndUnderlying['underlyings'])) {
            $this->optionChainExpiry($expiriesAndUnderlying, $this->optionType['stock']);
        }
    }

    public function fnoStocksExpiry()
    {
        $fnoData = json_decode(file_get_contents("https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=FUTSTK&u=infy", false, $this->context), true);
        return $fnoData;
    }

    public function optionChainExpiry(array $expiries)
    {
        for ($i = 0; $i < count($expiries['expiries']); $i++) {
            for ($j = 0; $j < count($expiries['underlyings']); $j++) {
                $optionRawData = $this->getOptionData($expiries['underlyings'][$j], $expiries['expiries'][$i], $this->optionType['stock']);
                //$this->optionDataFetch($expiries['underlyings'][$j], $expiries['expiries'][$i]);
                $optionChainExpiryId = $this->getOptionChainExpiryId($expiries['underlyings'][$j], $expiries['expiries'][$i], 'm');
                $this->getOptionChainDataStructure($optionRawData, $optionChainExpiryId);
            }
        }
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

    public function getOptionChainExpiryId($symbol, $expiry, $expiry_type)
    {
        $underlying = DB::table('option_chain_expiry')
            ->where('symbol', $symbol)
            ->where('expirydate', $expiry)
            ->first();

        if ($underlying) {
            return $underlying->id;
        } else {
            $expiry = $this->cf->convertExpiryToDateFormat($expiry);
            $underlying = DB::table('option_chain_expiry')->insertGetId(['symbol' => $symbol, 'expirydate' => $expiry,
                'expiry_type' => $expiry_type]);
        }
        return $underlying;
    }

    public function getOptionChainDataStructure(array $optionRawData, $optionChainExpiryId)
    {
        $strDate = $optionRawData[0][2];
        $strDate = "$strDate[4]$strDate[5]$strDate[6] $strDate[7]$strDate[8] $strDate[9]$strDate[10]$strDate[11]$strDate[12]";
        $fetchDate = date('Y-m-d', strtotime($strDate));

        dd($optionRawData,\Schema::getColumnListing('option_chain'),$optionRawData[4]);
        $optionChainColumns = \Schema::getColumnListing('option_chain');
        for ($k = 5; $k <= 87; $k++) {
            $optionDataStructure = array();
            $optionChainColumns['date'] = $fetchDate;
            $optionChainColumns['oce_id'] = $optionChainExpiryId;
            $optionChainColumns['calloi'] = $optionRawData[$k][0];
            $optionChainColumns['callchnginoi'] = $optionRawData[$k][1];
            $optionChainColumns['callvolume'] = $optionRawData[$k][2];
            $optionChainColumns['calliv'] = $optionRawData[$k][3];
            $optionChainColumns['callltp'] = $optionRawData[$k][4];
            $optionChainColumns['callnetchng'] = $optionRawData[$k][5];
            $optionChainColumns['callbidqty'] = $optionRawData[$k][6];
            $optionChainColumns['callbidprice'] = $optionRawData[$k][7];
            $optionChainColumns['callaskprice'] = $optionRawData[$k][8];
            $optionChainColumns['callaskqty'] = $optionRawData[$k][9];
            $optionChainColumns['strikeprice'] = $optionRawData[$k][10];
            $optionChainColumns['putoi'] = $optionRawData[$k][20];
            $optionChainColumns['putchnginoi'] = $optionRawData[$k][19];
            $optionChainColumns['putvolume'] = $optionRawData[$k][18];
            $optionChainColumns['putiv'] = $optionRawData[$k][17];
            $optionChainColumns['putltp'] = $optionRawData[$k][16]  ;
            $optionChainColumns['putnetchng'] = $optionRawData[$k][15];
            $optionChainColumns['putbidqty'] = $optionRawData[$k][11];
            $optionChainColumns['putbidprice'] = $optionRawData[$k][12];
            $optionChainColumns['putaskprice'] = $optionRawData[$k][13];
            $optionChainColumns['putaskqty'] = $optionRawData[$k][14];
            $optionChainColumns['totalcalloi'] = $optionRawData[$k];
            $optionChainColumns['totalcallvolume'] = $optionRawData[$k];
            $optionChainColumns['totalputoi'] = $optionRawData[$k];
            $optionChainColumns['totalputvolume'] = $optionRawData[$k];
            $optionChainColumns['pcr'] = $optionRawData[$k];
            $optionChainColumns['ivratio'] = $optionRawData[$k];
            $optionChainColumns['expiry'] = $optionRawData[$k];
            $optionChainColumns['watchlist'] = $optionRawData[$k];

            for ($i = 0; $i <= 20; $i++) {
                $key = $optionRawData[4][$i];
                if (isset($optionRawData[$k][$i]) && $optionRawData[$k][$i] == '-')
                    $optionRawData[$k][$i] = 0;
                    $optionDataStructure[$key] = $optionRawData[$k][$i];
                  //  echo "$optionDataStructure[$key]===".$optionRawData[$k][$i];
                    $temp = array($key => $optionRawData[$k][$i]);
                    //var_dump($temp);
                    $optionDataStructure = array_merge($optionDataStructure,$temp);
                    var_dump($optionDataStructure);
            }
            dd($optionRawData[4], $optionRawData[5], $optionDataStructure);
        }
    }
}
