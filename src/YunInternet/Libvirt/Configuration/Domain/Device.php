<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午3:53
 */

namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\Configuration\Domain\Device\Channel;
use YunInternet\Libvirt\Configuration\Domain\Device\Disk;
use YunInternet\Libvirt\Configuration\Domain\Device\Graphic;
use YunInternet\Libvirt\Configuration\Domain\Device\Input;
use YunInternet\Libvirt\Configuration\Domain\Device\InterfaceDevice;
use YunInternet\Libvirt\Configuration\Domain\Device\MemoryBalloon;
use YunInternet\Libvirt\Exception\DomainException;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Device
 * @method SimpleXMLImplement emulator()
 * @method MemoryBalloon memballoon()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class Device extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "memballoon" => MemoryBalloon::class,
    ];

    use SingletonChild;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);
    }

    /**
     * @param $emulator
     * @return $this
     */
    public function setEmulator($emulator)
    {
        $this->emulator()->setValue($emulator);
        return $this;
    }

    /**
     * @param string $type e.g. file, volume
     * @param string $device e.g. disk, cdrom, floppy
     * @param null $diskConfiguration
     * @return $this|Disk Return $this on $diskConfiguration is callable, or return Disk
     */
    public function addDisk($type, $device, $diskConfiguration = null)
    {
        $disk = new Disk($type, $device, $this->getSimpleXMLElement()->addChild("disk"));

        if (is_callable($diskConfiguration)) {
            $diskConfiguration($disk);
            return $this;
        }

        return $disk;
    }

    /**
     * @param callable|null $filter A callable accept a Disk as parameter
     * @return Disk[]
     */
    public function getDiskCollection($filter = null): array
    {
        if (is_callable($filter) === false) {
            $filter = function ($disk) {
                return true;
            };
        }
        return $this->getChildren("disk", $filter, Disk::class);
    }

    /**
     * @param string $device
     * @return Disk[]
     */
    public function getDiskCollectionByDevice(string $device): array
    {
        return $this->getDiskCollection(function (Disk $disk) use ($device) {
            return $disk->getDevice() === $device;
        });
    }

    /**
     * @param string $targetDev
     * @return Disk|null
     */
    public function getDiskByTargetDev($targetDev)
    {
        $collection = $this->getDiskCollection(function (Disk $disk) use ($targetDev) {
            return $disk->getTargetDevice() === $targetDev;
        });
        $collectionCount = count($collection);
        if ($collectionCount === 1) {
            return $collection[0];
        } else if ($collectionCount > 1) {
            throw new DomainException("target disk dev value not unique", ErrorCode::DISK_TARGET_DEV_VALUE_NOT_UNIQUE);
        }
        throw new DomainException("target disk not found", ErrorCode::DISK_NOT_FOUND);
    }

    /**
     * @param string $targetDev
     * @return $this
     * @throws DomainException
     */
    public function removeDiskByTargetDev(string $targetDev)
    {
        $this->removeDevice($this->getDiskByTargetDev($targetDev));
        return $this;
    }

    /**
     * @param $type
     * @param null $interfaceConfiguration
     * @return $this|InterfaceDevice Return $this on $interfaceConfiguration is callable, or return InterfaceDevice
     */
    public function addInterface($type, $interfaceConfiguration = null)
    {
        $interface = new InterfaceDevice($type, $this->getSimpleXMLElement()->addChild("interface"));

        if (is_callable($interfaceConfiguration)) {
            $interfaceConfiguration($interface);
            return $this;
        }

        return $interface;
    }

    /**
     * @param null|callable $filter
     * @return InterfaceDevice[]
     */
    public function getInterfaceCollection($filter = null): array
    {
        if (is_callable($filter) === false) {
            $filter = function ($interface) {
                return true;
            };
        }
        return $this->getChildren("interface", $filter, InterfaceDevice::class);
    }

    /**
     * @param string $macAddress
     * @return InterfaceDevice
     * @throws DomainException
     */
    public function getInterfaceByMacAddress(string $macAddress): InterfaceDevice
    {
        $collection = $this->getInterfaceCollection(function (InterfaceDevice $interfaceDevice) use ($macAddress) {
            return $interfaceDevice->getMacAddress() === $macAddress;
        });
        $collectionCount = count($collection);
        if ($collectionCount === 1) {
            return $collection[0];
        } else if ($collectionCount > 1) {
            throw new DomainException("interface mac address not unique", ErrorCode::INTERFACE_MAC_ADDRESS_NOT_UNIQUE);
        }
        throw new DomainException("interface not found", ErrorCode::INTERFACE_NOT_FOUND);
    }

    /**
     * @param string $macAddress
     * @throws DomainException
     */
    public function removeInterfaceByMacAddress(string $macAddress)
    {
        $this->removeDevice($this->getInterfaceByMacAddress($macAddress));
    }

    public function addInput($inputConfiguration = null)
    {
        $input = new Input($this->getSimpleXMLElement()->addChild("input"));

        if (is_callable($inputConfiguration)) {
            $inputConfiguration($input);
            return $this;
        }

        return $input;
    }

    public function addGraphic($type, $configuration = null)
    {
        $graphic = new Graphic($type, $this->getSimpleXMLElement()->addChild("graphics"));

        if (is_callable($configuration)) {
            $configuration($graphic);
            return $this;
        }

        return $graphic;

    }

    public function addVNCGraphic($configuration = null)
    {
        $VNCGraphic = new Graphic\VNCGraphic($this->getSimpleXMLElement()->addChild("graphics"));

        if (is_callable($configuration)) {
            $configuration($VNCGraphic);
            return $this;
        }

        return $VNCGraphic;
    }

    public function addChannel($type, $configuration = null)
    {
        $channel = new Channel($type, $this->getSimpleXMLElement()->addChild("channel"));

        if (is_callable($configuration)) {
            $configuration($channel);
            return $this;
        }

        return $channel;
    }

    /**
     * @param null|string $socketName If you use libvirt 1.0.6 or newer, you can omit the path='...' attribute of the <source> element, and libvirt will manage things automatically on your behalf.
     * @param string $targetName
     */
    public function addQEMUGuestAgentChannel($socketName = null, $targetName = "org.qemu.guest_agent.0")
    {
        $this->addChannel("unix", function (Channel $channel) use (&$socketName, &$targetName) {
            $channel->source()->setAttribute("mode", "bind");
            if (!is_null($socketName)) {
                $channel->source()->setAttribute("path", "/var/lib/libvirt/qemu/$socketName.agent");
            }

            $channel->target()
                ->setAttribute("type", "virtio")
                ->setAttribute("name", $targetName)
            ;
        });

        return $this;
    }

    /**
     * @param SimpleXMLImplement $simpleXMLImplement
     * @return $this
     */
    public function removeDevice(SimpleXMLImplement $simpleXMLImplement)
    {
        $this->removeChild($simpleXMLImplement);
        return $this;
    }

    public function useAbsoluteMousePointer()
    {
        $this->addInput(function (Input $input) {
            $input
                ->setType("tablet")
                ->setBus("usb")
            ;
        });

        return $this;
    }

    public function disableMemoryBalloon()
    {
        $this->memballoon()->setModel("none");
        return $this;
    }
}