<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午12:08
 */

namespace YunInternet\Libvirt\Configuration\Domain;

use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class OS
 * @property string $loader
 * @property string $type
 * @property string $architecture
 * @property array $bootDevices
 * @property bool $enableBootMenu
 * @property int $bootMenuTimeout microsecond unit
 * @method XMLElementContract bootmenu()
 * @method XMLElementContract type()
 * @method XMLElementContract loader()
 * @method XMLElementContract smbios()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class OS extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this
            ->setType("hvm")
            ->setArchitecture("x86_64")
        ;

        $this->setBootDevices([
            "hd",
            "cdrom",
            "fd",
        ]);

        $this
            ->toggleBootMenu(true)
            ->setBootMenuTimeout(3000)
        ;
    }

    public function setType($type)
    {
        $this->type()->setValue($type);
        return $this;
    }

    public function setArchitecture($architecture)
    {
        $this->type()->setAttribute("arch", $architecture);
        return $this;
    }

    public function setMachine($machine)
    {
        $this->type()->setAttribute("machine", "q35");
        return $this;
    }

    public function toggleBootMenu($enable = true)
    {
        $this->bootmenu()->setAttribute("enable", $enable ? "yes" : "no");
        return $this;
    }

    public function setBootMenuTimeout($timeout)
    {
        $this->bootmenu()->setAttribute("timeout", $timeout);
        return $this;
    }

    /**
     * Change the loader used by the domain
     * @param string|null $loader Loader file path, UEFI is /usr/share/ovmf/OVMF.fd, set null use default loader
     * @return $this
     */
    public function setLoader($loader)
    {
        $this->loader()->setValue($loader);
        return $this;
    }

    /**
     * @param array $bootDevices
     * @return $this
     */
    public function setBootDevices($bootDevices)
    {
        unset($this->getSimpleXMLElement()->boot);
        foreach ($bootDevices as $bootDevice)
            $this->addChild("boot", null, ["dev" => $bootDevice]);
        return $this;
    }

    /**
     * @param string $bootDevice e.g. hd
     * @return $this
     */
    public function addBootDevice($bootDevice)
    {
        $this->addChild("boot", null, ["dev" => $bootDevice]);
        return $this;
    }

    public function setSMBIOSMode($mode)
    {
        $this->smbios()->setAttribute("mode", $mode);
        return $this;
    }
}