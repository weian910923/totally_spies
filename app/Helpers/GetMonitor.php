<?php

namespace App\Helpers;

class GetMonitor
{
    /**
     * @description:取得Cpu 資訊
     *
     * @return: $info
     */
    public static function getCpu()
    {
        $output = '';
        exec('vmstat 5 3', $output);

        if (sizeof($output) != 5) {

            exit(0);
        }

        $result = $output[4];
        $matches = array();
        preg_match_all('/(\d+)/', $result, $matches);;

        if (sizeof($matches[1]) != 17) {

            exit(0);
        }

        $info = array();
        $info['user'] = (int)$matches[1][12];
        $info['system'] = (int)$matches[1][13];
        $info['total'] = $info['user'] + $info['system'];

        return $info;
    }

    /**
     * @description:取得Memory 資訊
     *
     * @return: $info
     */
    public static function getMemory()
    {
        $volid = null;
        $memory = $_preg = array();
        $info = array();
        $info['$memInfo'] = 0;
        $info['$swapInfo'] = 0;

        if ( ! file_exists('/proc/meminfo')) {
            exit;
        }

        if ($_ = fopen('/proc/meminfo', 'r')) {
            while ( ! feof($_)) {
                $volid .= fgets($_);
            }
            fclose($_);
        }

        if ( ! $volid) {
            return false;
        }

        $__ = preg_split("/\n/", $volid, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($__ as $_) {
            if (preg_match('/^MemTotal:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['MemTotal'] = (int)$_preg[1];
            }

            if (preg_match('/^MemFree:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['MemFree'] = (int)$_preg[1];
            }

            if (preg_match('/^Buffers:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['Buffers']= (int)$_preg[1];
            }

            if (preg_match('/^Cached:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['Cached']  = (int)$_preg[1];
            }

            if (preg_match('/^SwapTotal:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['SwapTotal']= (int)$_preg[1];
            }

            if (preg_match('/^SwapFree:\s+(.*)\s*kB/i', $_, $_preg)) {
                $memory['SwapFree'] = (int)$_preg[1];
            }

            if (isset($memory['MemTotal']) && isset($memory['MemFree']) && isset($memory['Cached'])  && isset($memory['Buffers'])){
                $memInfo = round(($memory['MemTotal'] - $memory['MemFree'] - $memory['Buffers'] - $memory['Cached'])/ ($memory['MemTotal'])*100);

            }

            if (isset($memory['SwapTotal']) && isset( $memory['SwapFree'] )) {
                $swapInfo = round(($memory['SwapTotal'] - $memory['SwapFree']) /$memory['SwapTotal']* 100);
            }
        }

        $info['$memInfo'] = (int)$memInfo;
        $info['$swapInfo'] = (int)$swapInfo;

        return $info;
    }

}