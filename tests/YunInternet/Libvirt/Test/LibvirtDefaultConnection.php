<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-16
 * Time: 下午4:34
 */

namespace YunInternet\Libvirt\Test;


use YunInternet\Libvirt\Connection;

trait LibvirtDefaultConnection
{
    public function connection()
    {
        static $connection;
        if (is_null($connection))
            $connection = new Connection("qemu:///system");
        return $connection;
    }
}