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
use YunInternet\Libvirt\Configuration\Domain\Feature;
use YunInternet\Libvirt\Configuration\Domain\OS;
use YunInternet\Libvirt\Configuration\Domain\PowerManagement;
use YunInternet\Libvirt\Configuration\Domain\SysInfo;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Domain
 * @method XMLElementContract name()
 * @method XMLElementContract memory()
 * @method XMLElementContract vcpu()
 * @method CPU cpu()
 * @method OS os()
 * @method PowerManagement pm()
 * @method Device devices()
 * @method BlockIOTune blkiotune()
 * @method Clock clock()
 * @method XMLElementContract uuid()
 * @method Feature features()
 * @method XMLElementContract on_poweroff()
 * @method XMLElementContract on_reboot()
 * @method XMLElementContract on_crash()
 * @package YunInternet\Libvirt\Configuration
 */
class Domain extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "cpu" => CPU::class,
        "os" => OS::class,
        "pm" => PowerManagement::class,
        "devices" => Device::class,
        "blkiotune" => BlockIOTune::class,
        "clock" => Clock::class,
        "features" => Feature::class,
    ];

    use SingletonChild;

    /**
     * Domain constructor.
     * @param string $name Domain name
     * @param int $memory MiB unit
     * @param int $maxAllocatedVCPU
     * @param string $type Domain type, e.g. kvm
     */
    public function __construct($name, $memory, $maxAllocatedVCPU, $type = "kvm")
    {
        parent::__construct(new \SimpleXMLElement("<domain/>"));

        $this->setType($type);

        $this->name()->setValue($name);

        $this->memory()->setValue($memory)->setAttribute("unit", "MiB");

        $this->vcpu()->setValue($maxAllocatedVCPU)->setAttribute("placement", "static");

        $this->cpu()
            ->setSocket(1)
            ->setCore($maxAllocatedVCPU)
            ->setThread(1)
        ;

        $this->clock()
            ->setOffset("utc")
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
    public function powerManagement()
    {
        return $this->pm();
    }

    public function device()
    {
        return $this->devices();
    }

    public function sysinfo()
    {
        return new SysInfo($this->getSimpleXMLElement()->addChild("sysinfo"));
    }

    private function initFeatures()
    {

        $this->features()
            ->createChild("acpi")
            ->createChild("apic")
        ;
    }
}