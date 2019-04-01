<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD

class OpenInterestController extends Controller
{
    //
=======
use App\Model\OpenInterest;

class OpenInterestController extends Controller
{
    public $oi;

    public function __construct()
    {
        $this->oi = new OpenInterest();
    }

    public function avgOIPerDay()
    {
        $day = 19;
        $curOi = $this->oi->currentOI();
        $avgOi = $this->oi->avgOIPerDay($day);
        $this->oi->comparisonWithCurrentToAvg($curOi, $avgOi);
    }
>>>>>>> 9d43eba4ec3d8c05301d84819f795c93308ea024
}
