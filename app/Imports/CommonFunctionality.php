<?php
/**
 * Created by PhpStorm.
 * User: Hemraj Solanki
 * Date: 2/19/2019
 * Time: 4:00 PM
 */

namespace App\Imports;


class CommonFunctionality
{

    /**
     * @param string $tableName
     * @return array|bool
     */
    public function fromDateToDate($tableName = 'participant_oi')
    {
        $frmToDates = [];
        $fdResult = \DB::table($tableName)->latest('date')->first();
        $currDate = date('Y-m-d');
        if (isset($fdResult->date) && $currDate == $fdResult->date) {
            return false;
        }

        if (isset($fdResult->date) && $fdResult->date) {
            $fromDate = new \DateTime($fdResult->date);
            $fromDate = $fromDate->modify('+1 day');
        } else {
            $fromDate = new \DateTime('2019-04-11');
        }

        $toDate = new \DateTime();
        $frmToDates = ['fromDate' => $fromDate, 'toDate' => $toDate];
        return $frmToDates;
    }

    public function convertExpiryToDateFormat($date)
    {
        $expiryDate = date('Y-m-d', strtotime($date));
        return $expiryDate;
    }
}
