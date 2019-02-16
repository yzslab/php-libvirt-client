<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-8
 * Time: 上午12:52
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class IOTune
 * @method XMLElementContract total_bytes_sec() The optional total_bytes_sec element is the total throughput limit in bytes per second. This cannot appear with read_bytes_sec or write_bytes_sec.
 * @method XMLElementContract read_bytes_sec() The optional read_bytes_sec element is the read throughput limit in bytes per second.
 * @method XMLElementContract write_bytes_sec() The optional write_bytes_sec element is the write throughput limit in bytes per second.
 * @method XMLElementContract total_iops_sec() The optional total_iops_sec element is the total I/O operations per second. This cannot appear with read_iops_sec or write_iops_sec.
 * @method XMLElementContract read_iops_sec() The optional read_iops_sec element is the read I/O operations per second.
 * @method XMLElementContract write_iops_sec() The optional write_iops_sec element is the write I/O operations per second.
 * @method XMLElementContract total_bytes_sec_max() The optional total_bytes_sec_max element is the maximum total throughput limit in bytes per second. This cannot appear with read_bytes_sec_max or write_bytes_sec_max.
 * @method XMLElementContract read_bytes_sec_max() The optional read_bytes_sec_max element is the maximum read throughput limit in bytes per second.
 * @method XMLElementContract write_bytes_sec_max() The optional write_bytes_sec_max element is the maximum write throughput limit in bytes per second.
 * @method XMLElementContract total_iops_sec_max() The optional total_iops_sec_max element is the maximum total I/O operations per second. This cannot appear with read_iops_sec_max or write_iops_sec_max.
 * @method XMLElementContract read_iops_sec_max() The optional read_iops_sec_max element is the maximum read I/O operations per second.
 * @method XMLElementContract write_iops_sec_max() The optional write_iops_sec_max element is the maximum write I/O operations per second.
 * @method XMLElementContract size_iops_sec() The optional size_iops_sec element is the size of I/O operations per second.
 * @package YunInternet\Libvirt\Configuration\Domain\Device\Disk
 */
class IOTune extends SimpleXMLImplement
{
    use SingletonChild;
}