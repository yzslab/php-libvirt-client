<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:44
 */

namespace YunInternet\Libvirt\Test;


use YunInternet\Libvirt\Connection;

trait LibvirtTestConnection
{
    public function connection()
    {
        static $connection;
        if (is_null($connection))
            $connection = new Connection("test:///default");
        return $connection;
    }
}