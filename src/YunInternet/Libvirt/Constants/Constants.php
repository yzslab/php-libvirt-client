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
        "sata" => "sd",
        "ide" => "hd",
        "fdc" => "fd",
    ];

    const NETWORK_TYPE_FTP = 'ftp';
    const NETWORK_TYPE_FTPS = 'ftps';
    const NETWORK_TYPE_GLUSTER = 'gluster';
    const NETWORK_TYPE_HTTP = 'http';
    const NETWORK_TYPE_HTTPS = 'https';
    const NETWORK_TYPE_ISCSI = 'iscsi';
    const NETWORK_TYPE_NDB = 'nbd';
    const NETWORK_TYPE_NFS = 'nfs';
    const NETWORK_TYPE_SHEEPDOG = 'sheepdog';
    const NETWORK_TYPE_RBD = 'rbd';
    const NETWORK_TYPE_TFTP = 'tftp';
    const NETWORK_TYPE_VXHS = 'vxhs';
}