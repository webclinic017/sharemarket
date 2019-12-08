<?php

namespace App\Model;

use App\Imports\CommonFunctionality;
use App\Imports\ShareImport;
use Illuminate\Database\Eloquent\Model;

class NiftyIndex extends Model
{
    public $si;
    public $cf;

    public function __construct()
    {
        $this->si = new ShareImport();
        $this->cf = new CommonFunctionality();
    }

    public function niftyLivePrice()
    {

        $url = 'https://www.nseindia.com/homepage/Indices1.json';
        $data = $this->si->jsonReturnUrl($url);
        return $data;
    }

    public function indexRatios()
    {
        $lastRawDate = $this->niftyLivePrice();
        $lastDate = $this->cf->convertDateWithFormat($lastRawDate['time'], 'd-m-Y');
        $lastRec = \DB::table('index_ratios')->latest()->first();
        if ($lastRec) {
            //$lastRec = $this->cf->convertDateWithFormat($lastRawDate['time'], 'd-m-Y');
            $lastDate = $lastRec->date;
            $firstDate = $lastDate;
        } else {
            $firstDate = '01-10-2019';
        }

        $dataPart = $this->indexRatiosDataPull('NIFTY%2050', $firstDate, $lastDate);
        // dd($dataPart);
        if (count($dataPart)) {
            $yn = \DB::table('index_ratios')->insert($dataPart);
        } else {
            $yn = false;
        }
        return $yn;
    }

    public function indexRatiosDataPull($indexName, $frmDate, $toDate)
    {
        $url = "https://www.nseindia.com/products/dynaContent/equities/indices/historical_pepb.jsp?indexName=$indexName&fromDate=$frmDate&toDate=$toDate&yield1=undefined&yield2=undefined&yield3=undefined&yield4=all";
        $rawHtmlDataPart = $this->si->get($url);

        $dataById = $this->si->findTag('tr');

        $dummy = $finalData = $rawData = [];

        foreach ($dataById as $tag) {
            $searchChar = ["\t\r", "\r", "\t", " ", "Chart", ","];
            $dummy[] = str_replace($searchChar, '', $tag->nodeValue);
        }
        $counter = count($dummy);
        $i = 0;
        foreach ($dummy as $key => $value) {
            if ($key > 2 && $key < $counter - 1) {
                $rawData[] = array_values(array_filter(explode("\n", $value)));
                $finalData[$i]['date'] = $this->cf->convertDateWithFormat($rawData[$i][0], 'Y-m-d');
                $finalData[$i]['pe'] = $rawData[$i][1];
                $finalData[$i]['pb'] = $rawData[$i][2];
                $finalData[$i]['divyield'] = $rawData[$i][3];
                $i++;
            }
        }
        return $finalData;
    }
}