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

    public function participantOIData($from, $to)
    {
        //$from = new \DateTime('2014-03-09 00:00:00');
        //$to = new \DateTime('2019-03-13 00:00:00');
        $fm = $from->format('Y-m-d');//for printing purpose only
        for ($i = 0; $from <= $to; $i++) {
            if (in_array($from->format('D'), ['Sat', 'Sun'])) {
                $from = $from->modify('+1 day');
            } else {
                $dateOfPOI = $from->format('d') . $from->format('m') . $from->format('Y');
                $dataPOI = $this->participantOIDataPull($dateOfPOI);
                if ($dataPOI) {
                    $poiDataStructure = $this->tableDataStructure($dataPOI, $dateOfPOI);
                    //  dd($poiDataStructure, $dataPOI);
                    if ($poiDataStructure) {
                        $yn = false;
                        $yn = $this->insertData($poiDataStructure);
                        if ($yn) {
                            $oiDate = $from->format('Y-m-d');
                            \DB::table('dateinsert_report')->insert(['report' => 3, 'date' => $oiDate]);
                        }
                    }
                }
                $from = $from->modify('+1 day');
            }
        }

        $td = $to->format('Y-m-d');
        return "All Participant Open Interest done from $fm to $td\n";
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
        $dataDelivery = null;
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
                if (isset($oiArray[$i][1]) && isset($oiArray[6][1])) {
                    $dataDelivery[$j]['index_long_per'] = (is_numeric($oiArray[$i][1]) && is_numeric($oiArray[6][1])) ? ($oiArray[$i][1] / $oiArray[6][1]) * 100 : 0;
                } else {
                    $dataDelivery[$j]['index_long_per'] = 0;
                }

                if (isset($oiArray[$i][2]) && isset($oiArray[6][2])) {
                    $dataDelivery[$j]['index_short_per'] = (is_numeric($oiArray[$i][2]) && is_numeric($oiArray[6][2])) ? ($oiArray[$i][2] / $oiArray[6][2]) * 100 : 0;
                } else {
                    $dataDelivery[$j]['index_short_per'] = 0;
                }

                if (isset($oiArray[$i][3]) && isset($oiArray[6][3])) {
                    $dataDelivery[$j]['stock_long_per'] = (is_numeric($oiArray[$i][3]) && is_numeric($oiArray[6][3])) ? ($oiArray[$i][3] / $oiArray[6][3]) * 100 : 0;
                } else {
                    $dataDelivery[$j]['stock_long_per'] = 0;
                }

                if (isset($oiArray[$i][4]) && isset($oiArray[6][4])) {
                    $dataDelivery[$j]['stock_short_per'] = (is_numeric($oiArray[$i][4]) && is_numeric($oiArray[6][4])) ? ($oiArray[$i][4] / $oiArray[6][4]) * 100 : 0;
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


    public function participantOIMood()
    {
        $participantOIData = [];
        $client_type = ['Client', 'FII', 'PRO', 'DII'];
        foreach ($client_type as $type) {
            $lastRow = $this->where('client_type', $type)->latest('date')->first();
            $participantOIData[$type]['indexFuture'] = $lastRow->future_index_long - $lastRow->future_index_short;
            $participantOIData[$type]['indexOptionCall'] = $lastRow->option_index_call_long - $lastRow->option_index_call_short;
            $participantOIData[$type]['indexOptionPut'] = $lastRow->option_index_put_long - $lastRow->option_index_put_short;
            $participantOIData[$type]['stockFuture'] = $lastRow->future_stock_long - $lastRow->future_stock_short;
            $participantOIData[$type]['index_long_per'] = $lastRow->index_long_per;
            $participantOIData[$type]['index_short_per'] = $lastRow->index_short_per;
            $participantOIData[$type]['index_short_per'] = $lastRow->index_short_per;
            $participantOIData[$type]['stock_long_per'] = $lastRow->stock_long_per;
            $participantOIData[$type]['stock_short_per'] = $lastRow->stock_short_per;
        }

        return $participantOIData;
    }

    public function perSegParticipantOI($segment, $limit)
    {
        //  SELECT date, (`future_index_long`- `future_index_short`)INDEX_futm, (`option_index_call_long`-`option_index_call_short`) option_call, (`option_index_put_long`-`option_index_put_short`) option_put FROM `participant_oi` WHERE `client_type` = 'pro' ORDER by date DESC
        $result = \DB::select("SELECT date, (`future_index_long`- `future_index_short`)index_fut, (`option_index_call_long`-`option_index_call_short`) option_call, (`option_index_put_long`-`option_index_put_short`) option_put FROM `participant_oi` WHERE `client_type` = ? ORDER by date DESC LIMIT ?", [$segment, $limit]);
        return $result;
    }

    public function participantWiseEquityData()
    {
      $partipants = ['fii','Dii']; $partipantsData = [];
      foreach ($partipants as $partipant) {
        $url = 'https://www.nseindia.com/products/dynaContent/equities/equities/htms/'.$partipant.'EQ.htm';
        $rawHtmlDataPart = $this->shareImp->get($url);
        $exptractedDataPart = $this->shareImp->findClass('alt');
        $dataPart = $this->shareImp->getNodeValue($exptractedDataPart);
        $dataPart = $this->shareImp->convertWholeLineToArray($dataPart);
        $partipantsData[$partipant] = $dataPart[0];
      }
      return $partipantsData;
    }
}
