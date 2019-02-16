<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-8
 * Time: 下午5:26
 */

namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;
use YunInternet\Libvirt\Configuration\Domain\BlockIOTune\Device;

/**
 * Class BlockIOTune
 * @method XMLElementContract weight()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class BlockIOTune extends SimpleXMLImplement
{
    use SingletonChild;

    /**
     * @param $path
     * @param $weight
     * @param null $configuration
     * @return $this|Device Return $this on $configuration is callable, or return Device
     */
    public function addDevice($path, $weight, $configuration = null)
    {
        $device = new Device($path, $weight, $this->getSimpleXMLElement()->addChild("device"));

        if (is_callable($configuration)) {
            $configuration($device);
            return $this;
        }
        return $device;
    }
}