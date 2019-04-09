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
    protected $table = 'participant_oi';

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
        //dd($optionRawData[6][6]);
        for ($k = 5; $k <= 87; $k++) {
            $optionDataStructure = [];
            for ($i = 0; $i <= 20; $i++) {
                echo($optionRawData[$k][$i] . "==" . $optionRawData[4][$i] . "<br>");
                $key = $optionRawData[4][$i];
                if (isset($optionRawData[$k][$i]) && $optionRawData[$k][$i] == '-') {
                    $optionRawData[$k][$i] = 0;
                    $optionDataStructure[$key] = $optionRawData[$k][$i];
                } else {
                    $optionDataStructure[$key] = $optionRawData[$k][$i];
                }
            }
            dd($optionRawData[4], $optionRawData[5], $optionDataStructure);
        }
    }
}
