<?php

namespace App\Helpers;

use InfluxDB;

class SettingInfluxDB
{
    /**
     * @description: 設定InfluxDB Monitor
     *
     * @return: $database
     */
    public static function settingMonitorDBInfo()
    {
        $host = env('LARAVEL_INFLUX_PROVIDER_HOST');
        $database = env('LARAVEL_INFLUX_PROVIDER_DATABASE');

        $client = new InfluxDB\Client($host, 8086);
        $database = $client->selectDB($database);

        $getIpInfo = new GetIpInfo;
        $ipAddress = $getIpInfo->getIpAddress();
        $region = $getIpInfo->getRegion($ipAddress);

        $dbInfo = array();
        $dbInfo['database'] = $database;
        $dbInfo['ip_address'] = $ipAddress;
        $dbInfo['region'] = $region;

        return $dbInfo;
    }

}