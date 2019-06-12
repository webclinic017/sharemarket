<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProbabilityController extends Controller
{
    public function viewProb()
    {
        return view('screener.probability');
    }

    public function calculateProbability()
    {

    }
}
