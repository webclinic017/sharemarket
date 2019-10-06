<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Fibonacci;


class FibonacciController extends Controller
{
    public function fibonacciCalculator()
    {
        $fb = new Fibonacci();
        $fb->fibLevelsDownTrend(419, 241);
        $fb->fibLevelsUpTrend(142, 12);
    }

}
