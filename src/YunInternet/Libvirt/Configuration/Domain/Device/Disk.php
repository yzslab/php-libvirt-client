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
use YunInternet\Libvirt\Configuration\Domain\Device\Disk\Source;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Disk
 * @method Address address()
 * @method BackingStore backingStore()
 * @method IOTune IOTune()
 * @method Source source()
 * @method XMLElementContract driver()
 * @method XMLElementContract product()
 * @method XMLElementContract readonly()
 * @method XMLElementContract serial()
 * @method XMLElementContract target()
 * @method XMLElementContract vendor()
 * @method XMLElementContract wwn()
 * @package YunInternet\Libvirt\Configuration\Domain\Device
 */
class Disk extends SimpleXMLImplement
{
    protected $singletonChildAliases = [
        "BackingStore" => "backingStore",
        "IOTune" => "iotune",
    ];

    protected $singletonChildWrappers = [
        "address" => Address::class,
        "backingStore" => BackingStore::class,
        "iotune" => IOTune::class,
        "source" => Source::class,
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
        if (is_null($filter)) {
            $filter = function (BackingStore $backingStore) {
                return $backingStore->isActive();
            };
        }
        return $this->getChildren("backingStore", $filter, BackingStore::class, true);
    }

    public function sourceClass(): string
    {
        return ($this->getType() === 'network' ? $this->getType() . $this->source()->getProtocol() : $this->getType());
    }
}