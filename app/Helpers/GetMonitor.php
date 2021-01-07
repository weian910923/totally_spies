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

}