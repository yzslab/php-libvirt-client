<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:23
 */

namespace YunInternet\Libvirt\Configuration\StoragePool;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

/**
 * Class Source
 * @method XMLElementContract host()
 * @method XMLElementContract dir()
 * @method XMLElementContract format()
 * @package YunInternet\Libvirt\Configuration\StoragePool
 */
class Source extends SimpleXMLImplement
{
    public function setHostName($hostName)
    {
        $this->host()->setAttribute("name", $hostName);
        return $this;
    }

    public function addDevice($path, $configuration = null)
    {
        $device = $this->addChild("device");

        if (is_callable($configuration)) {
            $configuration($device);
            return $this;
        }
        return $device;
    }
}