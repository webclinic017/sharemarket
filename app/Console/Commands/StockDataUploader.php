<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\StockData;
use App\Model\ShareInfo;
use App\Model\ParticipantOI;
use App\Model\OptionData;
use App\Model\OpenInterest;
use App\Imports\CommonFunctionality;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StockDataUploader extends Command
{
    public $sd;
    public $si;
    public $po;
    public $od;
    public $sim;
    public $oi;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataupload';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All stock data uploaded successfully';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->po = new ParticipantOI();
        $this->sd = new StockData();
        $this->od = new OptionData();
        $this->oi = new OpenInterest();
        $this->si = new ShareInfo();
        $this->cf = new CommonFunctionality();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //delivery
        //OI
        //Bhavcopy
        //participant OI
        echo $this->openInterest();
        echo $this->participantOI();
        echo $this->delivery();
        $this->oi->avgOIAsPerDayWatchlist();
        return true;
    }

    public function participantOI()
    {
        $tableName = 'participant_oi';
        $frmToDates = $this->cf->fromDateToDate($tableName);
        if ($frmToDates === false)
            return "Participant OI data is already updated";
        else
            return $this->po->participantOIData($frmToDates['fromDate'], $frmToDates['toDate']);
    }


    /** report 2 = delivery; 3 = Paricipant OI; 4 = OI */
    public function delivery()
    {
        $tableName = 'stock_data';
        $frmToDates = $this->cf->fromDateToDate($tableName);
        if ($frmToDates === false) {
            return "delivery data is already updated";
        } else {
            return $this->sd->delivery($frmToDates['fromDate'], $frmToDates['toDate']);
        }
    }

    public function openInterest()
    {
        $tableName = 'oi_data';
        $frmToDates = $this->cf->fromDateToDate($tableName);
        if ($frmToDates === false)
            return "Open Interest data is already updated";
        else
            return $this->si->oiPullDates($frmToDates['fromDate'], $frmToDates['toDate']);

    }
}
