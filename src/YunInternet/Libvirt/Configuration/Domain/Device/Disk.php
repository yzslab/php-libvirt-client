<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午3:55
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\Configuration\Domain\Device\Disk\Address;
use YunInternet\Libvirt\Configuration\Domain\Device\Disk\IOTune;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Disk
 * @method XMLElementContract driver()
 * @method XMLElementContract source()
 * @method XMLElementContract target()
 * @method XMLElementContract readonly()
 * @method XMLElementContract serial()
 * @method XMLElementContract wwn()
 * @method XMLElementContract vendor()
 * @method XMLElementContract product()
 * @package YunInternet\Libvirt\Configuration\Domain\Device
 */
class Disk extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($type, $device, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->driver()->setAttribute("name", "qemu");
        $this
            ->setType($type)
            ->setDevice($device)
        ;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    /**
     * @param string $device
     * @return $this
     */
    public function setDevice($device)
    {
        $this->setAttribute("device", $device);
        return $this;
    }

    public function volumeSource($poolName, $volumeName)
    {
        $this->source()
            ->setAttribute("pool", $poolName)
            ->setAttribute("volume", $volumeName)
        ;
        return $this;
    }

    public function fileSource($filePath)
    {
        $this->source()->setAttribute("file", $filePath);
        return $this;
    }

    public function setDriver($name)
    {
        $this->driver()->setAttribute("name", $name);
        return $this;
    }

    /**
     * @param string $type e.g. raw, qcow2
     * @return $this
     */
    public function setDriverType($type)
    {
        $this->driver()->setAttribute("type", $type);
        return $this;
    }

    /**
     * @param string $device e.g. sda, hda
     * @return $this
     */
    public function setTargetDevice($device)
    {
        $this->target()->setAttribute("dev", $device);
        return $this;
    }

    /**
     * @param string $bus e.g. virtio, ide, fdc
     * @return $this
     */
    public function setTargetBus($bus)
    {
        $this->target()->setAttribute("bus", $bus);
        return $this;
    }

    public function setReadonly($enable)
    {
        if ($enable) {
            $this->readonly();
        }
        else {
            unset($this->getSimpleXMLElement()->readonly);
        }
        return $this;
    }

    private $IOTune;

    public function IOTune()
    {
        if (is_null($this->IOTune)) {
            $this->IOTune = new IOTune($this->getSimpleXMLElement()->addChild("iotune"));
        }
        return $this->IOTune;
    }

    private $address;

    public function address()
    {
        if (is_null($this->address)) {
            $this->address = new Address($this->getSimpleXMLElement()->addChild("address"));
        }
        return $this->address;
    }
}