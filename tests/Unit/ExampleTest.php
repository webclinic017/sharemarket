<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Model\Fibonacci;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest ()
    {
        $this -> assertTrue (true);
    }

    public function testFibLevelsUpTrend ()
    {
        $fib = new Fibonacci();
        $stockData = ['fibHigh' => 1000, 'fibLow' => 500, 'open' => 188, 'high' => 200, 'low' => 2680, 'close' => 494];
        $fibLevels = $fib -> fibLevelsUpTrend ($stockData['fibHigh'], $stockData['fibLow']);
        $rangeData = $fib -> rangeCalculator ($fibLevels, 1);
        $finalData = $fib -> checkStockOHLCWithFibooRange ($stockData, $rangeData);
        dd ($fibLevels, $rangeData, $finalData);
    }
}
