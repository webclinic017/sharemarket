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
        $expiriesAndUnderlying = $this->fnoStocksExpiry('FUTSTK');
        if (isset($expiriesAndUnderlying['expiries'], $expiriesAndUnderlying['underlyings'])) {
            $this->optionChainExpiry($expiriesAndUnderlying, $this->optionType['stock']);
        }
    }

    public function indexOptionData()
    {
        $expiriesAndUnderlying = $this->fnoStocksExpiry('FUTIDX');
        if (isset($expiriesAndUnderlying['expiries'], $expiriesAndUnderlying['underlyings'])) {
            $this->optionChainExpiry($expiriesAndUnderlying, $this->optionType['index']);
        }
    }

    public function fnoStocksExpiry($optionType)
    {
        $fnoData = json_decode(file_get_contents("https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=$optionType&u=infy", false, $this->context), true);
        return $fnoData;
    }

    public function optionChainExpiry(array $expiries,$optionType)
    {
        for ($i = 0; $i < count($expiries['expiries']); $i++) {
            for ($j = 0; $j < count($expiries['underlyings']); $j++) {
                $optionRawData = array();
                $optionRawData = $this->getOptionData($expiries['underlyings'][$j], $expiries['expiries'][$i], $optionType);
                //$this->optionDataFetch($expiries['underlyings'][$j], $expiries['expiries'][$i]);
                $optionChainExpiryId = $this->getOptionChainExpiryId($expiries['underlyings'][$j], $expiries['expiries'][$i], 'm');
                $yn = $this->getOptionChainDataStructure($optionRawData, $optionChainExpiryId,$i);
                echo $expiries['underlyings'][$j]." option chain inserted<br/>";
            }
        }
        echo "Total Stocks".count($expiries['underlyings'])."<br/>";
        return "All Stock Option chain inserted<br>";
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
        $expiry = $this->cf->convertExpiryToDateFormat($expiry);
        $underlying = DB::table('option_chain_expiry')
            ->where('symbol', $symbol)
            ->where('expirydate', $expiry)
            ->first();

        if ($underlying) {
            return $underlying->id;
        } else {
            $underlying = DB::table('option_chain_expiry')->insertGetId(['symbol' => $symbol, 'expirydate' => $expiry,
                'expiry_type' => $expiry_type]);
        }
        return $underlying;
    }

    public function getOptionChainDataStructure(array $optionRawData, $optionChainExpiryId, $expiryNumber)
    {
      if(isset($optionRawData[0][2])) {
        $strDate = $optionRawData[0][2];
        $strDate = "$strDate[4]$strDate[5]$strDate[6] $strDate[7]$strDate[8] $strDate[9]$strDate[10]$strDate[11]$strDate[12]";
        $fetchDate = date('Y-m-d', strtotime($strDate));

        //dd($optionRawData,\Schema::getColumnListing('option_chain'),$optionRawData[4]);
//        $optionChainColumns = \Schema::getColumnListing('option_chain');
        $limit = count($optionRawData);
        $optionChain = array();
        for ($k = 5; $k < $limit-2; $k++) {
            $optionChainColumns = array();
            $optionChainColumns['date'] = $fetchDate;
            $optionChainColumns['oce_id'] = $optionChainExpiryId;
            $optionChainColumns['calloi'] = is_numeric($optionRawData[$k][0]) ? $optionRawData[$k][0] : 0;
            $optionChainColumns['callvolume'] = is_numeric($optionRawData[$k][2]) ? $optionRawData[$k][2] : 0;
            $optionChainColumns['callchnginoi'] =  is_numeric($optionRawData[$k][1]) ? $optionRawData[$k][1] : 0;
            $optionChainColumns['calliv'] = is_numeric($optionRawData[$k][3]) ? $optionRawData[$k][3] : 0;
            $optionChainColumns['callltp'] = is_numeric($optionRawData[$k][4]) ? $optionRawData[$k][4] : 0 ;
            $optionChainColumns['callnetchng'] = is_numeric($optionRawData[$k][5])  ? $optionRawData[$k][5] : 0;
            $optionChainColumns['callbidqty'] = is_numeric($optionRawData[$k][6]) ? $optionRawData[$k][6] : 0;
            $optionChainColumns['callbidprice'] = is_numeric($optionRawData[$k][7]) ? $optionRawData[$k][7] : 0;
            $optionChainColumns['callaskprice'] = is_numeric($optionRawData[$k][8]) ? $optionRawData[$k][8] : 0;
            $optionChainColumns['callaskqty'] = is_numeric($optionRawData[$k][9]) ? $optionRawData[$k][9] : 0;
            $optionChainColumns['strikeprice'] = is_numeric($optionRawData[$k][10]) ? $optionRawData[$k][10] : 0;
            $optionChainColumns['putoi'] = is_numeric($optionRawData[$k][20]) ? $optionRawData[$k][20] : 0;
            $optionChainColumns['putchnginoi'] = is_numeric($optionRawData[$k][19]) ? $optionRawData[$k][19] : 0;
            $optionChainColumns['putvolume'] = is_numeric($optionRawData[$k][18]) ? $optionRawData[$k][18] : 0;
            $optionChainColumns['putiv'] = is_numeric($optionRawData[$k][17]) ? $optionRawData[$k][17] : 0;
            $optionChainColumns['putltp'] = is_numeric($optionRawData[$k][16]) ? $optionRawData[$k][16] : 0  ;
            $optionChainColumns['putnetchng'] = is_numeric($optionRawData[$k][15]) ? $optionRawData[$k][15] : 0;
            $optionChainColumns['putbidqty'] = is_numeric($optionRawData[$k][11]) ? $optionRawData[$k][11] : 0;
            $optionChainColumns['putbidprice'] = is_numeric($optionRawData[$k][12]) ? $optionRawData[$k][12] : 0;
            $optionChainColumns['putaskprice'] = is_numeric($optionRawData[$k][13]) ? $optionRawData[$k][13] : 0;
            $optionChainColumns['putaskqty'] = is_numeric($optionRawData[$k][14]) ? $optionRawData[$k][14] : 0;
            //$optionChainColumns['totalcalloi'] = $optionRawData[$k];
            //$optionChainColumns['totalcallvolume'] = $optionRawData[$k];
            //$optionChainColumns['totalputoi'] = $optionRawData[$k];
            //$optionChainColumns['totalputvolume'] = $optionRawData[$k];
            //$optionChainColumns['pcr'] = $optionRawData[$k];
            $ivRatio = $this->getIVRatio($optionChainColumns['putiv'],$optionChainColumns['calliv']);
            $optionChainColumns['ivRatio'] = $ivRatio;
            $watchList = $this->getWatchList($optionChainColumns,$expiryNumber);
            $optionChainColumns['watchlist'] = $watchList;

            $optionChain[] = $optionChainColumns;
        }
            $yn = $this->dataInsert($optionChain);
            return $yn;
      }
    }

    public function getIVRatio($putiv,$calliv)
    {
      $ivRatio = 0;
      if(isset($putiv,$calliv) &&
      is_numeric($putiv) && is_numeric($calliv)) {
        if($calliv > 0)
          $ivRatio = $putiv/$calliv;
      }
      return $ivRatio;
    }

    public function getWatchList($optionChainColumns,$expiryNumber)
    {
      $watchList = 0;
      if($optionChainColumns['ivRatio'] > 0 &&
      ($optionChainColumns['ivRatio'] > 2 || $optionChainColumns['ivRatio'] < 0.5)) {
        $watchList = 1;
      }
      if(($optionChainColumns['callchnginoi'] > 1000000 ||
          $optionChainColumns['putchnginoi'] > 1000000) && $expiryNumber == 0)
      {
        $watchList = 1;
      }
      if (($optionChainColumns['callchnginoi'] > 100000  ||
          $optionChainColumns['putchnginoi'] > 100000) && $expiryNumber == 1) {
        $watchList = 1;
      }
      if(($optionChainColumns['callchnginoi'] > 10000  ||
          $optionChainColumns['putchnginoi'] > 10000) && $expiryNumber == 2) {
        $watchList = 1;
      }
      return $watchList;
    }

    public function dataInsert($optionChainDataStructure)
    {
      $yn = $this->insert($optionChainDataStructure);
      return $yn;
    }
}
