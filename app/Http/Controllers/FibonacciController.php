<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Fibonacci;
use App\Imports\ShareImport;

class FibonacciController extends Controller
{
    public function fibonacciCalculator()
    {
        $fb = new Fibonacci();
        $fb->fibLevelsDownTrend(419, 241);
        $fb->fibLevelsUpTrend(142, 12);
    }

    public function fiiData()
    {
        $dataNumber = [];
        $si = new ShareImport();
        $url = 'https://www.nseindia.com/products/dynaContent/equities/equities/htms/fiiEQ.htm';
        $data = $si->get($url);
        $number = $si->findClass('number');
        foreach ($number as $tag) {
            $dataNumber[] = $tag->nodeValue;
        }
        dd($dataNumber, $data);
        $context = $si->contextValue();
        $file = @file_get_contents("https://www.nseindia.com/products/dynaContent/equities/equities/htms/fiiEQ.htm", false, $context);
        dd($file);
    }
}