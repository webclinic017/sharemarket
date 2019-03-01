<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\StockData;

class StockDataController extends Controller
{
    public function shareData()
    {
        $sd = new StockData();
        $sd->shareDataPull();
    }
}
