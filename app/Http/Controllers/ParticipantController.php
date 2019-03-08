<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ParticipantOI;

class ParticipantController extends Controller
{
    public $sD;

    public function __construct()
    {
        $this->pOi = new ParticipantOI();
    }

    public function participantOIData()
    {
        $from = new \DateTime('2012-01-01 00:00:00');
        $to = new \DateTime('2019-03-08 00:00:00');

        for ($i = 0; $from != $to; $i++) {
            if (in_array($from->format('D'), ['Sat', 'Sun'])) {
                $from = $from->modify('+1 day');
            } else {
                $dateOfPOI = $from->format('d') . $from->format('m') . $from->format('Y');
                $dataPOI = $this->pOi->participantOIDataPull($dateOfPOI);
                $this->pOi->tableDataStructure($dataPOI, $dateOfPOI);
                if ($dataPOI) {
                    $yn = false;
                    $yn = $this->pOi->insertData($dataPOI);
                    if ($yn) {
                        $DelDate = $from->format('Y-m-d');
                        DB::table('dateinsert_report')->insert(['report' => 'POI', 'date' => $DelDate]);
                    }
                }
                $from = $from->modify('+1 day');
            }
        }
        echo "all Participant Open Interest done\n";
        return "true";

    }
}
