<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fibonacci extends Model
{
    public $stockFibLevel = [];
    public static $FIBLEVELS = [0.236, 0.382, 0.5, 0.618, 0.786, 1.272, 1.414, 1.618, 2, 2.272, 2.414, 2.618,
        3, 3.272, 3.414, 3.618, 4, 4.272, 4.414, 4.236, 4.618];

    public function fibLevelsUpTrend ($high, $low)
    {
        foreach (self ::$FIBLEVELS as $fiblevel) {
            $temp = [];
            $this -> stockFibLevel['0'] = $low;
            $this -> stockFibLevel['1'] = $high;
            if ($high > $low) {
                $diff = $high - $low;
            }
            $this -> stockFibLevel["$fiblevel"] = ($diff * $fiblevel) + $low;

        }
        $fibonacciLevel = json_encode ($this -> stockFibLevel);
        return $fibonacciLevel;
    }

    public function fibLevelsDownTrend ($high, $low)
    {
        foreach (self ::$FIBLEVELS as $fiblevel) {
            $temp = [];
            $this -> stockFibLevel['0'] = $high;
            $this -> stockFibLevel['1'] = $low;
            if ($high > $low) {
                $diff = $high - $low;
            }
            $this -> stockFibLevel["$fiblevel"] = $high - ($diff * $fiblevel);

        }
        $fibonacciLevel = json_encode ($this -> stockFibLevel);
        return $fibonacciLevel;
    }

    /**
     * @param $fibLevel
     * @param $rangeNumber
     * @return array
     */
    public function rangeCalculator ($fibLevel, $rangeNumber)
    {
        $fibonacciLevel = json_decode ($fibLevel, 1);
        $fibooLevelWithRange = [];
        foreach ($fibonacciLevel as $fiboNumber => $fiboLevel) {
            $range = $fiboLevel * $rangeNumber / 100; //calculate range as per rangeNumber and fiboolevel
            $fibooLevelWithRange[$fiboNumber] = ['level' => $fiboLevel, 'range' => $range,
                'upperRange' => $fiboLevel + $range, 'lowerRange' => $fiboLevel - $range];
        }

        return $fibooLevelWithRange;
    }

    public function checkStockOHLCWithFibooRange ($stockOHLC, $fibooLevelWithRange)
    {
        foreach ($fibooLevelWithRange as $fiboLevel => $rangeData) {
            if ($stockOHLC['close'] <= $rangeData['upperRange'] && $stockOHLC['close'] >= $rangeData['lowerRange']) {
                return [$fiboLevel, $stockOHLC['close']];
            } elseif ($stockOHLC['high'] <= $rangeData['upperRange'] && $stockOHLC['high'] >= $rangeData['lowerRange']) {
                return [$fiboLevel, $stockOHLC['high']];
            } elseif ($stockOHLC['low'] <= $rangeData['upperRange'] && $stockOHLC['low'] >= $rangeData['lowerRange']) {
                return [$fiboLevel, $stockOHLC['low']];
            } elseif ($stockOHLC['open'] <= $rangeData['upperRange'] && $stockOHLC['open'] >= $rangeData['lowerRange']) {
                return [$fiboLevel, $stockOHLC['open']];
            }
        }
        return false;
    }
}
