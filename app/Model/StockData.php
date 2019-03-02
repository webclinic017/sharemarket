<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class StockData extends Model
{
    public $shareImp;
    public $context;
    protected $table = 'stock_data';

    public function __construct()
    {
        $this->shareImp = new ShareImport();
        $this->context = $this->shareImp->contextValue();
    }

    public function shareDataPull()
    {
        $url = "https://www.nseindia.com/products/dynaContent/common/productsSymbolMapping.jsp?symbol=infy&segmentLink=3&symbolCount=1&series=ALL&dateRange=24month&fromDate=&toDate=&dataType=PRICEVOLUMEDELIVERABLE";
        $url = "https://www.nseindia.com/products/dynaContent/common/productsSymbolMapping.jsp?symbol=INFY&segmentLink=3&symbolCount=1&series=ALL&dateRange=24month&fromDate=&toDate=&dataType=PRICEVOLUMEDELIVERABLE";
        $html = $this->shareImp->get($url);
        $nodes = $this->shareImp->findClass('download-data-link');

        dd($html, $nodes->click);
        foreach ($nodes as $tag) {
            $tag_value[] = explode("\n", $tag->nodeValue);
        }

        dd($tag_value);
        $json = json_decode(file_get_contents("https://www.nseindia.com/products/dynaContent/common/productsSymbolMapping.jsp?symbol=infy&segmentLink=3&symbolCount=1&series=ALL&dateRange=24month&fromDate=&toDate=&dataType=PRICEVOLUMEDELIVERABLE", false, $this->context), true);
        //dd($json);
    }
}
