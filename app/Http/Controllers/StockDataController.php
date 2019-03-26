<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\StockData;
use App\Imports\ShareImport;
use DB;

class StockDataController extends Controller
{
    public $sD;

    public function __construct()
    {
        $this->sD = new StockData();
        $this->shareImp = new ShareImport();
    }

    public function shareData()
    {
        $this->sD->shareDataPull();
    }

    public function bhavCopyDataPull()
    {
        $data = $this->sD->bhavCopyDataPull();
        $bhavcopy = $this->shareImp->convertPlainTextLineByLineToArray($data);
        dd($bhavcopy);
    }



}
