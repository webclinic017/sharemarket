<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OpenInterest extends Model
{
    protected $table = 'oi_data';

    public function previous15DaysOI()
    {
       $this::where('symbol',$symbol)
              ->
    }
}
