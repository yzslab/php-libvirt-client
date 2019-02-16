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
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

class Device extends SimpleXMLImplement
{
    use SingletonChild;

    /**
     * @var MemoryBalloon
     */
    private $memballoon;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->memballoon = new MemoryBalloon($this->getSimpleXMLElement()->addChild("memballoon"));
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
            if (!is_null($socketName))
                $channel->source()->setAttribute("path", "/var/lib/libvirt/qemu/$socketName.agent");

            $channel->target()
                ->setAttribute("type", "virtio")
                ->setAttribute("name", $targetName)
            ;
        });

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
        $this->memballoon->setModel("none");
        return;
    }

    /**
     * @return MemoryBalloon
     */
    public function memballoon()
    {
        return $this->memballoon;
    }
}