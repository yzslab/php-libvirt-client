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
 * @method XMLElementContract currentMemory()
 * @method XMLElementContract vcpu()
 * @method CPU cpu()
 * @method OS os()
 * @method PowerManagement pm()
 * @method Device devices()
 * @method Device device()
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
    const QEMU_NAMESPACE = "http://libvirt.org/schemas/domain/qemu/1.0";

    protected $singletonChildAliases = [
        "device" => "devices",
        "QEMUCommandLine" => "qemu:commandline",
    ];

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

        $this->setMemory($memory);

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

    /**
     * @param string $namespace
     * @param string $value
     * @return $this
     */
    public function setXMLNamespace(string $namespace, string $value)
    {
        $this->getSimpleXMLElement()["xmlns:" . $namespace] = $value;
        return $this;
    }

    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    /**
     * The maximum allocation of memory for the guest at boot time
     * @param int $allocation
     * @param string $unit
     * @return $this
     */
    public function setMemory(int $allocation, string $unit = "MiB")
    {
        $this->memory()->setValue($allocation)->setAttribute("unit", $unit);
        return $this;
    }

    /**
     * The actual allocation of memory for the guest
     * @param int $allocation
     * @param string $unit
     * @return $this
     */
    public function setCurrentMemory(int $allocation, string $unit = "MiB")
    {
        $this->currentMemory()->setValue($allocation)->setAttribute("unit", $unit);
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

    public function sysinfo()
    {
        return new SysInfo($this->getSimpleXMLElement()->addChild("sysinfo"));
    }

    public function setQEMUCommandLineArguments(array $arguments)
    {
        $QEMUCommandLineElement = $this->QEMUCommandLineElement();
        foreach ($QEMUCommandLineElement->children(self::QEMU_NAMESPACE)->arg as $child) {
            $childDOM = dom_import_simplexml($child);
            $childDOM->parentNode->removeChild($childDOM);
        }
        foreach ($arguments as $argument) {
            $child = $QEMUCommandLineElement->addChild("xmlns:qemu:arg");
            $child["value"] = $argument;
        }
        return $this;
    }

    private function QEMUCommandLineElement()
    {
        $this->setXMLNamespace("qemu", self::QEMU_NAMESPACE);
        $QEMUCommandLine = $this->getSimpleXMLElement()->children(self::QEMU_NAMESPACE)->commandline[0];
        if (is_null($QEMUCommandLine)) {
            $QEMUCommandLine = $this->getSimpleXMLElement()->addChild("xmlns:qemu:commandline");
        }
        return $QEMUCommandLine;
    }


    private function initFeatures()
    {

        $this->features()
            ->createChild("acpi")
            ->createChild("apic")
        ;
    }
}