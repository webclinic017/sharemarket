<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\StockData;
use DB;

class StockDataController extends Controller
{
    public $sD;

    public function __construct()
    {
        $this->sD = new StockData();
    }

    public function shareData()
    {
        $this->sD->shareDataPull();
    }

    public function delivery()
    {
        $from = new \DateTime('2011-01-01 00:00:00');
        $to = new \DateTime('2019-03-06 00:00:00');

        for ($i = 0; $from != $to; $i++) {
            if (in_array($from->format('D'), ['Sat', 'Sun'])) {
                $from = $from->modify('+1 day');
            } else {
                $dateOfDelivery = $from->format('d') . $from->format('m') . $from->format('Y');
                $dataDelivery = $this->sD->delivery($dateOfDelivery);
                if ($dataDelivery) {
                    $yn = false;
                    $yn = $this->sD->insertData($dataDelivery);
                    if ($yn) {
                        $DelDate = $from->format('Y-m-d');
                        DB::table('dateinsert_report')->insert(['report' => 'delivery', 'date' => $DelDate]);
                    }
                }
                $from = $from->modify('+1 day');
            }
        }
        echo "all delivery done";
        return "true";
    }
}
