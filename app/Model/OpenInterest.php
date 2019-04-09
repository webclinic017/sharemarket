<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OpenInterest extends Model
{
    protected $table = 'oi_data';

    /** RAW QUERY :- select avg(open_interest),symbol,date from (SELECT open_interest,symbol,date FROM share.oi_data
     * where date > DATE_SUB(curdate(), INTERVAL 4 DAY )) x group by symbol;
     */

    /**
     * avearage OI for number of provided days.
     * @param $day
     * @return mixed
     */

    public function avgOIAsPerDayWatchlist()
    {
        $day = 19;
        $latestDate = $this->getLatestDate();
        $curOi = $this->currentOI($latestDate);
        $avgOi = $this->avgOIPerDay($day, $latestDate);
        $finalList = $this->comparisonWithCurrentToAvg($curOi, $avgOi);
        $watchList = $this->addWatchlist($finalList);
        return $watchList;
    }

    public function getLatestDate()
    {
<<<<<<< HEAD
        $avgOI = \DB::select("select avg(open_interest) open_interest,symbol from (SELECT open_interest,symbol,date FROM
                          share.oi_data where (date > DATE_SUB(curdate(), INTERVAL $day DAY) and date < '".$latestDate."'))  x group by symbol");
        return $avgOI;
=======
        $latestDate = $this::latest('date')->first(['date']);
        return $latestDate->date;
>>>>>>> dd1e37ef23f1599e959a69f1447075aa4ea9c136
    }

    /**
     * Function is for latest date OI.
     * @return mixed
     */
    public function currentOI($latestDate)
    {
        $latestData = $this::select('id', 'date', 'open_interest', 'symbol')
            ->where('date', $latestDate)
            ->orderBy('symbol')
            ->get()
            ->toArray();
        return $latestData;
    }

    public function avgOIPerDay($day, $latestDate)
    {
        $avgOI = \DB::select("select avg(open_interest) open_interest,symbol from (SELECT open_interest,symbol,date FROM
                          share.oi_data where (date > DATE_SUB(curdate(), INTERVAL $day DAY) and date < '$latestDate'))  x group by symbol");
        return $avgOI;
    }

    public function comparisonWithCurrentToAvg($curOi, $avgOi)
    {
        $j = 0;
        $finalList = [];

        foreach ($curOi as $keyCurOi => $valCurOi) {
          //  dd($keyCurOi, $valCurOi, $avgOi[$keyCurOi]);
            if (isset($valCurOi['symbol'], $avgOi[$keyCurOi]->symbol) && $valCurOi['symbol'] === $avgOi[$keyCurOi]->symbol) {
                if ($valCurOi['open_interest'] > $avgOi[$keyCurOi]->open_interest) {
                    $finalList[$j]['id'] = $valCurOi['id'];
                    $finalList[$j]['symbol'] = $avgOi[$keyCurOi]->symbol;
                    $finalList[$j]['open_interest'] = (($valCurOi['open_interest'] - $avgOi[$keyCurOi]->open_interest) / $avgOi[$keyCurOi]->open_interest) * 100;
                    $finalList[$j]['cur_open_interest'] = $valCurOi['open_interest'];
                    $finalList[$j]['avg_open_interest'] = $avgOi[$keyCurOi]->open_interest;
                    $j++;
                }
            }
        }
      //  dd($finalList);
        return $finalList;
    }

    public function addWatchlist($finalList)
    {
        $ids = array_column($finalList, 'id');
        $yn = $this::whereIn('id', $ids)->update(['watchlist' => 1]);
        $watchlist = $this::where('watchlist', 1)->get()->toArray();
        dd($watchlist);
        return $watchlist;
    }
}
