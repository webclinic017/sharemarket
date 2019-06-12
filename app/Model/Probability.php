<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Probability extends Model
{
    public function normsdist($x)
    {
        // Returns the standard normal cumulative distribution
        // ---------------------------------------
        // Load tabulated values in an array
       // include "ndist_tabulated.php";
        // Discriminate upon the absolute value, then the sign of $x
        $x = number_format($x, 2);
        if (abs($x) >= 3.09) {
            $output = 0;
        } elseif ($x == 0) {
            $output = 0.5;
        } elseif ($x < 0) {
            // find higher boundary (next highest value with 2 decimals)
            $x2 = number_format(ceil(100 * $x) / 100, 2);
            $x2 = (string)$x2;
            // find lower boundary
            $x1 = number_format($x2 - 0.01, 2);
            $x1 = (string)$x1;
            // linear interpolate
            $output = $values[$x1] + ($values[$x2] - $values[$x1]) / 0.01 * ($x - $x1);
        } else {
            // if x>0
            $output = 1 - normsdist(-$x);
        }
        return number_format($output, 4);
    }

}
