<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午3:55
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\Configuration\Domain\Device\Disk\Address;
use YunInternet\Libvirt\Configuration\Domain\Device\Disk\BackingStore;
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
 * @method IOTune IOTune()
 * @method Address address()
 * @method BackingStore backingStore()
 * @package YunInternet\Libvirt\Configuration\Domain\Device
 */
class Disk extends SimpleXMLImplement
{
    protected $singletonChildAliases = [
        "IOTune" => "iotune",
        "BackingStore" => "backingStore",
    ];

    protected $singletonChildWrappers = [
        "iotune" => IOTune::class,
        "address" => Address::class,
        "backingStore" => BackingStore::class,
    ];

    use SingletonChild;

    public function __construct($type, $device, \SimpleXMLElement $simpleXMLElement = null)
    {
        if (is_null($simpleXMLElement)) {
            $simpleXMLElement = new \SimpleXMLElement("<disk/>");
        }
        parent::__construct($simpleXMLElement);

        $this->driver()->setAttribute("name", "qemu");
        $this
            ->setType($type)
            ->setDevice($device);
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

    public function getType()
    {
        return $this->getAttribute("type");
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

    public function getDevice()
    {
        return $this->getAttribute("device");
    }

    public function volumeSource($poolName, $volumeName)
    {
        $this->source()
            ->setAttribute("pool", $poolName)
            ->setAttribute("volume", $volumeName);
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

    public function setCache($cache)
    {
        $this->driver()->setAttribute("cache", $cache);
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

    public function getTargetDevice()
    {
        return $this->target()->getAttribute("dev");
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

    public function getTargetBus()
    {
        return $this->target()->getAttribute("bus");
    }

    public function setReadonly($enable)
    {
        if ($enable) {
            $this->readonly();
        } else {
            unset($this->getSimpleXMLElement()->readonly);
        }
        return $this;
    }

    public function hasBacking(): bool
    {
        return is_null($this->findChild("backingStore")) === false && $this->backingStore()->isActive();
    }

    /**
     * @param callable|null $filter A callable accept a BackingStore as parameter
     * @return array BackingStore[]
     */
    public function getBackiStorageCollection($filter = null): array
    {
        return $this->getChildren("backingStore", $filter, BackingStore::class, true);
    }
}