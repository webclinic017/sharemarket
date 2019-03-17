<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\StockData;

class ShareInfo extends Model
{
    protected $table = 'share_detail';

    public function oiDataPull($date)
    {
        $file = @fopen("extract-here\combineoi_$date.csv", 'r+');
        //dd($file, fgetcsv($file));
        $oi = array();
        while (($line = fgetcsv($file)) !== false) {
            array_push($oi, $line);
        }
        return $oi;
    }

    public function oiDataStructure($oi,$date)
    {
        $j=0; $stockOi = null;
        for($i=1; $i<count($oi);$i++){
            $stockOi[$j]['date'] = $date->format('Y-m-d');
            $stockOi[$j]['symbol'] = $oi[$i][3] ?? null;
            $stockOi[$j]['mwpl'] = (isset($oi[$i][4]) && is_numeric($oi[$i][4])) ? $oi[$i][4] : 0;
            $stockOi[$j]['open_interest'] = (isset($oi[$i][5]) && is_numeric($oi[$i][5])) ? $oi[$i][5] : 0;
            $stockOi[$j]['limitNextDay'] = (isset($oi[$i][6]) && is_numeric($oi[$i][6])) ? $oi[$i][6] : 0;
            $stockOi[$j]['created_at'] = $date->format('Y-m-d h:m:s');
            $j++;
        }
        return $stockOi;
    }

    public function oiDetail($date)
    {
        $str = "\\extract-here";
        $path = public_path() . $str;
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
                    'timeout' => 10000
                ),
            )
        );
        $f = @file_put_contents("my-zip.zip", fopen("https://www.nseindia.com/archives/nsccl/mwpl/combineoi_$date.zip", 'r', 0, $context), LOCK_EX, $context);
        if (0 === $f) {
            return false;
        }
        $zip = new \ZipArchive;
        $res = $zip->open('my-zip.zip');
        if ($res === true) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        }
        else{
            return false;
        }
    }

    public function oiPullDates()
    {
        $from = new \DateTime('2019-03-02 00:00:00');
        $to = new \DateTime('2019-03-13 00:00:00');

        for ($i = 0; $from != $to; $i++) {
            if (in_array($from->format('D'), ['Sat', 'Sun'])) {
                $from = $from->modify('+1 day');
            } else {
                $dateOfOI = $from->format('d') . $from->format('m') . $from->format('Y');
                $dataDelivery = $this->oiDetail($dateOfOI);
                if ($dataDelivery) {
                    $oiDataPull = $this->oiDataPull($dateOfOI);
                    $oiDataStore = $this->oiDataStructure($oiDataPull,$from);
                    //dd($oiDataStore);
                    $yn = false;
                    $yn = $this->insertData($oiDataStore);
                    if ($yn) {
                        $DelDate = $from->format('Y-m-d');
                        \DB::table('dateinsert_report')->insert(['report' => '4', 'date' => $DelDate]);
                    }
                }
                $from = $from->modify('+1 day');
            }
        }
        echo "all OI done";
        return "true";
    }

    public function insertData(array $oiDataStore)
    {
        return \DB::table('oi_data')->insert($oiDataStore);
    }
}
