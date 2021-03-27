<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 上午12:44
 */

namespace YunInternet\Libvirt\Configuration;


use YunInternet\Libvirt\Configuration\Domain\BlockIOTune;
use YunInternet\Libvirt\Configuration\Domain\Clock;
use YunInternet\Libvirt\Configuration\Domain\CPU;
use YunInternet\Libvirt\Configuration\Domain\Device;
use YunInternet\Libvirt\Configuration\Domain\OS;
use YunInternet\Libvirt\Configuration\Domain\PowerManagement;
use YunInternet\Libvirt\Configuration\Domain\SysInfo;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Domain
 * @method XMLElementContract uuid()
 * @method XMLElementContract features()
 * @method XMLElementContract on_poweroff()
 * @method XMLElementContract on_reboot()
 * @method XMLElementContract on_crash()
 * @package YunInternet\Libvirt\Configuration
 */
class Domain extends SimpleXMLImplement
{
    use SingletonChild;

    private $domain;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var int $memory MiB unit
     */
    private $memory;

    /**
     * @var int $maxAllocatedVCPU
     */
    private $maxAllocatedVCPU;

    /**
     * @var string $uuid
     */
    private $uuid;

    /**
     * @var CPU $cpu CPU section
     */
    private $cpu;

    /**
     * @var OS $os OS section
     */
    private $os;

    /**
     * @var PowerManagement
     */
    private $pm;

    /**
     * @var Device
     */
    private $device;

    /**
     * Domain constructor.
     * @param string $xml XML Domain or empty for new
     */
    public function __construct($xml = '<domain/>')
    {
        parent::__construct(new \SimpleXMLElement($xml));
    }

    /**
     * Domain initializer.
     * @param string $name Domain name
     * @param int $memory MiB unit
     * @param int $maxAllocatedVCPU
     * @param string $type Domain type, e.g. kvm
     */
    public function init($name, $memory, $maxAllocatedVCPU, $type = "kvm")
    {
        $this->setType($type);

        $this->name = $this->addChild("name", $name);

        $this->memory = $this->addChild("memory", $memory, ["unit" => "MiB"]);

        $this->maxAllocatedVCPU = $this->addChild("vcpu", $maxAllocatedVCPU, ["placement" => "static"]);

        $this->cpu = new CPU($this->getSimpleXMLElement()->addChild("cpu"));

        $this->cpu
            ->setSocket(1)
            ->setCore($maxAllocatedVCPU)
            ->setThread(1)
        ;

        $this->os = new OS($this->getSimpleXMLElement()->addChild("os"));

        $this->pm = new PowerManagement($this->getSimpleXMLElement()->addChild("pm"));

        $this->device = new Device($this->getSimpleXMLElement()->addChild("devices"));

        $this->clock()
            ->addTimer("rtc", function (SimpleXMLImplement $timer) {
                $timer->setAttribute("tickpolicy", "catchup");
            })
            ->addTimer("pit", function (SimpleXMLImplement $timer) {
                $timer->setAttribute("tickpolicy", "delay");
            })
            ->addTimer("hpet", function (SimpleXMLImplement $timer) {
                $timer->setAttribute("present", "no");
            })
        ;

        $this->powerManagement()
            ->setAllowSuspend2Memory(false)
            ->setAllowSuspend2Disk(true)
        ;

        $this->initFeatures();
    }

    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    /**
     * @param string $uuid
     * @return $this
     */
    public function setUUID($uuid)
    {
        $this->uuid()->setValue($uuid);
        return $this;
    }

    public function setOnPowerOff($action)
    {
        $this->on_poweroff()->setValue($action);
        return $this;
    }

    public function setOnReboot($action)
    {
        $this->on_reboot()->setValue($action);
        return $this;
    }

    public function setOnCrash($action)
    {
        $this->on_crash()->setValue($action);
        return $this;
    }

    /**
     * @return PowerManagement
     */
    public function pm()
    {
        return $this->pm;
    }

    /**
     * @return PowerManagement
     */
    public function powerManagement()
    {
        return $this->pm;
    }

    /**
     * @return CPU
     */
    public function cpu()
    {
        return $this->cpu;
    }

    /**
     * @return OS
     */
    public function os()
    {
        return $this->os;
    }

    public function device()
    {
        return $this->device;
    }

    public function devices()
    {
        return $this->device;
    }

    /**
     * @return BlockIOTune
     */
    public function blockIOTune()
    {
        return $this->blkiotune();
    }

    private $blkiotune;
    /**
     * @return BlockIOTune
     */
    public function blkiotune()
    {
        if (is_null($this->blkiotune)) {
            $this->blkiotune = new BlockIOTune($this->getSimpleXMLElement()->addChild("blkiotune"));
        }
        return $this->blkiotune;
    }

    public function sysinfo()
    {
        return new SysInfo($this->getSimpleXMLElement()->addChild("sysinfo"));
    }

    /**
     * @var Clock $clock
     */
    private $clock;

    public function clock()
    {
        if (is_null($this->clock)) {
            $this->clock = new Clock("utc", $this->getSimpleXMLElement()->addChild("clock"));
        }
        return $this->clock;
    }

    private function initFeatures()
    {

        $this->features()
            ->createChild("acpi")
            ->createChild("apic")
        ;
    }
}