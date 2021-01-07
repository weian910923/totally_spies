<?php

namespace App\Console\Commands;

use InfluxDB\Point;
use App\Helpers\GetMonitor;
use Illuminate\Console\Command;
use App\Helpers\SettingInfluxDB;

class MonitorCpu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:cpu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'monitor CPU';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $influxDBInfo = new SettingInfluxDB;
        $dbInfo = $influxDBInfo->settingMonitorDBInfo();

        $database = $dbInfo['database'];
        $ip_address = $dbInfo['ip_address'];
        $region = $dbInfo['region'] ;

        $getCpu = new GetMonitor();
        $fields = $getCpu->getCpu();

        $point = [
            new Point(
                $ip_address ."_cpu",
                null,
                ['host' => $ip_address, 'region' => $region ."_cpu"],
                $fields,
                exec('date +%s%N')
            )
        ];

        $database->writePoints($point);
    }
}
