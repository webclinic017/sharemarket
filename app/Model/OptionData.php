<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class OptionData extends Model
{
    public $shareImp;
    public $context;
    protected $table = 'participant_oi';

    public function __construct()
    {
        $this->shareImp = new ShareImport();
        $this->context = $this->shareImp->contextValue();
    }

    public function optionDataFetch($url)
    {
        //dd($this->fnoStocksExpiry());
        $data = $this->shareImp->get($url);
        $dataById = $this->shareImp->findTag('tr');
        $dummy = $finalData = [];
        foreach ($dataById as $tag) {
            $searchChar = ["\t\r", "\r", "\t", " ", "Chart"];
            $dummy[] = str_replace($searchChar, '', $tag->nodeValue);
        }
      //  dd($dummy,$data,$tag);
        foreach ($dummy as $key => $value) {
            $finalData[] = array_values(array_filter(explode("\n", $value)));
        }
      //  dd($finalData);

        return $finalData;
    }

    public function fnoStocksExpiry()
    {
        $fnoData = json_decode(file_get_contents("https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxFOGetQuoteDataTest.jsp?i=FUTSTK&u=infy", false, $this->context), true);
        return $fnoData;
    }

    public function stockOptionData()
    {
        $fnodata = $this->fnoStocksExpiry();

    }
}
