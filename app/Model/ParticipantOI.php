<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class ParticipantOI extends Model
{
    public $shareImp;
    public $context;
    protected $table = 'stock_data';

    public function __construct()
    {
        $this->shareImp = new ShareImport();
        $this->context = $this->shareImp->contextValue();
    }

    public function participantOIDataPull($date)
    {
        $dataArray = null;
        $url = "https://www.nseindia.com/content/nsccl/fao_participant_oi_$date.csv";
        $data = $this->shareImp->pullDataFromRemote($url);
        if ($data) {
            $dataArray = $this->shareImp->convertPlainTextLineByLineToArray($data);
        }
        return $dataArray;
    }

    public function tableDataStructure($oiArray, $date)
    {
        $j = 0;
        for ($i = 2; $i < count($oiArray) - 1; $i++) {
            if (count($oiArray) > 0 && isset($shareArray[$i][2])) {
                $dataDelivery[$j]['client_type'] = $oiArray[$i][2] ?? null;
                $dataDelivery[$j]['series'] = $oiArray[$i][3] ?? null;
                $dataDelivery[$j]['future_index_long'] = $oiArray[$i][4] ?? null;
                $dataDelivery[$j]['date'] = "$date[4]$date[5]$date[6]$date[7]-$date[2]$date[3]-$date[0]$date[1]";
                $j++;
            }
        }
        return $dataDelivery;
    }

    public function insertData(array $dataPOI)
    {
        return StockData::insert($dataPOI);
    }
}