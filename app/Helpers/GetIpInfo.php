<?php

namespace App\Helpers;

class GetIpInfo
{
    /**
     * @description:取得IP Address
     *
     * @return: ＄ipAddress
     */
    public static function getIpAddress()
    {
        $ipAddress = null;
        $ipAddress2 = null;

        array_map(

            function ($key) use (&$ipAddress, &$ipAddress2) {

                $ip = trim(exec("/sbin/ip -4 a |grep {$key} |grep inet |awk '{print $2}'|cut -d/ -f1"));
                $preg="/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";

                if (preg_match($preg, $ip)) {

                    ($ipAddress == null && substr($ip, 0, 6) == '172.17') and $ipAddress = $ip;
                    ($ipAddress2 == null) and $ipAddress2 = $ip;
                }
            },

            array('eth1', 'eth0', 'bond0.17')
        );

        $ipAddress = ($ipAddress == null) ? $ipAddress2 : $ipAddress;

        return $ipAddress;
    }

    /**
     * @description:取得 地區
     * @param: string  $ipAddress   ipAddress
     *
     * @return: $region
     */
    public static function getRegion($ipAddress)
    {
        preg_match_all('/\d{1,3}/', $ipAddress, $_preg);

        if ( $_preg[0][2] == 10 ) {

            $region = "台灣";

        } else {

            $region = "region";
        }

        return $region;
    }
}