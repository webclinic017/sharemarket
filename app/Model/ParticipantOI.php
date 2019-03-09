<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Imports\ShareImport;

class ParticipantOI extends Model
{
    public $shareImp;
    public $context;
    protected $table = 'participant_oi';

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
        $j = 0; $dataDelivery = null;
        for ($i = 2; $i <= 5; $i++) {
            if (isset($oiArray[$i][0]) && count($oiArray) > 0) {
                $dataDelivery[$j]['client_type'] = $oiArray[$i][0] ?? null;
                $dataDelivery[$j]['future_index_long'] = is_numeric($oiArray[$i][1]) ? $oiArray[$i][1] : 0;
                $dataDelivery[$j]['future_index_short'] = is_numeric($oiArray[$i][2]) ? $oiArray[$i][2] : 0;
                $dataDelivery[$j]['option_index_call_long'] = is_numeric($oiArray[$i][5]) ? $oiArray[$i][5] : 0;
                $dataDelivery[$j]['option_index_put_long'] = is_numeric($oiArray[$i][6]) ? $oiArray[$i][6] : 0;
                $dataDelivery[$j]['option_index_call_short'] = is_numeric($oiArray[$i][7]) ? $oiArray[$i][7] : 0;
                $dataDelivery[$j]['option_index_put_short'] = is_numeric($oiArray[$i][8]) ? $oiArray[$i][8] : 0;
                $dataDelivery[$j]['future_stock_long'] = is_numeric($oiArray[$i][3]) ? $oiArray[$i][3] : 0;
                $dataDelivery[$j]['future_stock_short'] = is_numeric($oiArray[$i][4]) ? $oiArray[$i][4] : 0;
                $dataDelivery[$j]['option_stock_call_long'] = is_numeric($oiArray[$i][9]) ? $oiArray[$i][9] : 0;
                $dataDelivery[$j]['option_stock_put_long'] = is_numeric($oiArray[$i][10]) ? $oiArray[$i][10] : 0;
                $dataDelivery[$j]['option_stock_call_short'] = is_numeric($oiArray[$i][11]) ? $oiArray[$i][11] : 0;
                $dataDelivery[$j]['option_stock_put_short'] = is_numeric($oiArray[$i][12]) ? $oiArray[$i][12] : 0;
                if(isset($oiArray[$i][1]) && isset($oiArray[6][1])) {
                  $dataDelivery[$j]['index_long_per'] = (is_numeric($oiArray[$i][1]) && is_numeric($oiArray[6][1])) ? ($oiArray[$i][1]/$oiArray[6][1])*100 : 0;
                }
                else {
                  $dataDelivery[$j]['index_long_per'] = 0;
                }

                if (isset($oiArray[$i][2]) && isset($oiArray[6][2])) {
                  $dataDelivery[$j]['index_short_per'] = (is_numeric($oiArray[$i][2]) && is_numeric($oiArray[6][2])) ? ($oiArray[$i][2]/$oiArray[6][2])*100 : 0;
                } else {
                  $dataDelivery[$j]['index_short_per'] = 0;
                }

                if (isset($oiArray[$i][3]) && isset($oiArray[6][3])) {
                  $dataDelivery[$j]['stock_long_per'] = (is_numeric($oiArray[$i][3]) && is_numeric($oiArray[6][3])) ? ($oiArray[$i][3]/$oiArray[6][3])*100 : 0;
                } else {
                  $dataDelivery[$j]['stock_long_per'] = 0;
                }

                if (isset($oiArray[$i][4]) && isset($oiArray[6][4])) {
                  $dataDelivery[$j]['stock_short_per'] = (is_numeric($oiArray[$i][4]) && is_numeric($oiArray[6][4])) ? ($oiArray[$i][4]/$oiArray[6][4])*100 : 0;
                } else {
                  $dataDelivery[$j]['stock_short_per'] = 0;
                }

                $dataDelivery[$j]['date'] = "$date[4]$date[5]$date[6]$date[7]-$date[2]$date[3]-$date[0]$date[1]";
                $dataDelivery[$j]['created_at'] = date('Y-m-d H:i:s');
                $j++;
            }
        }
        return $dataDelivery;
    }

    public function insertData(array $dataPOI)
    {
        return ParticipantOI::insert($dataPOI);
    }
}
