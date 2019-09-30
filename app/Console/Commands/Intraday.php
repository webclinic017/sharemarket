<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\StockData;
use App\Model\ShareInfo;
use App\Model\ParticipantOI;
use App\Model\OptionData;
use App\Model\OpenInterest;
use App\Imports\CommonFunctionality;
use App\Model\OiSpurt;

class Intraday extends Command
{
    public $sd;
    public $si;
    public $po;
    public $od;
    public $sim;
    public $oi;
    public $os;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intraday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->os = new OiSpurt();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo $this->optionChainData();
    }

    public function optionChainData()
    {
        $insertYN = false;
        $this->od->indexOptionData($insertYN);
        $this->od->stockOptionData($insertYN);
    }
}
