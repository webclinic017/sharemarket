<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ParticipantOI;
use DB;

class ParticipantController extends Controller
{
    public $sD;

    public function __construct()
    {
        $this->pOi = new ParticipantOI();
    }

    public function participantOIData()
    {
        $from = new \DateTime('2014-03-09 00:00:00');
        $to = new \DateTime('2019-03-13 00:00:00');

        for ($i = 0; $from <= $to; $i++) {
            if (in_array($from->format('D'), ['Sat', 'Sun'])) {
                $from = $from->modify('+1 day');
            } else {
                $dateOfPOI = $from->format('d') . $from->format('m') . $from->format('Y');
                $dataPOI = $this->pOi->participantOIDataPull($dateOfPOI);
                $poiDataStructure = $this->pOi->tableDataStructure($dataPOI, $dateOfPOI);
                //  dd($poiDataStructure, $dataPOI);
                if ($poiDataStructure) {
                    $yn = false;
                    $yn = $this->pOi->insertData($poiDataStructure);
                    if ($yn) {
                        $oiDate = $from->format('Y-m-d');
                        DB::table('dateinsert_report')->insert(['report' => 3, 'date' => $oiDate]);
                    }
                }
                $from = $from->modify('+1 day');
            }
        }
        $fm = $from->format('Y-m-d');
        $td = $to->format('Y-m-d');
        return "All Participant Open Interest done from $fm to $td";
    }

    public function perSegment()
    {
        $segment = ['CLIENT', 'PRO', 'FII', 'DII'];
        $segmentWiseData = [];
        $limit = 10;
        foreach ($segment as $segName) {
            $segmentWiseData[$segName] = $this->pOi->perSegParticipantOI($segName, $limit);
        }
        return view('participant', compact('segmentWiseData', 'limit'));
    }

}
