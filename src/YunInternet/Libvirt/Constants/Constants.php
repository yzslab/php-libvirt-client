<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-16
 * Time: 下午8:41
 */

namespace YunInternet\Libvirt\Constants;


interface Constants
{
    const BUS_DEVICE_PREFIX = [
        "virtio" => "vd",
        "scsi" => "sd",
        "ide" => "hd",
        "fdc" => "fd",
    ];
}