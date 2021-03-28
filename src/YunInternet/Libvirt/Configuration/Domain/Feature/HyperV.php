<?php


namespace YunInternet\Libvirt\Configuration\Domain\Feature;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Feature
 * @method SimpleXMLImplement relaxed()
 * @method SimpleXMLImplement vapic()
 * @method SimpleXMLImplement spinlocks()
 * @method SimpleXMLImplement vpindex()
 * @method SimpleXMLImplement runtime()
 * @method SimpleXMLImplement synic()
 * @method SimpleXMLImplement stimer()
 * @method SimpleXMLImplement reset()
 * @method SimpleXMLImplement vendor_id()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class HyperV extends SimpleXMLImplement
{
    use SingletonChild;

    public function setRelaxed($on)
    {
        $this->setState($this->relaxed(), $on);
        return $this;
    }

    public function setVapic($on)
    {
        $this->setState($this->vapic(), $on);
        return $this;
    }

    public function setSpinLocks($on, $retries)
    {
        $this->setState($this->spinlocks(), $on)->setAttribute("retries", $retries);
        return $this;
    }

    public function setVpindex($on)
    {
        $this->setState($this->vpindex(), $on);
        return $this;
    }

    public function setRuntime($on)
    {
        $this->setState($this->runtime(), $on);
        return $this;
    }

    public function setSynic($on)
    {
        $this->setState($this->synic(), $on);
        return $this;
    }

    public function setStimer($on)
    {
        $this->setState($this->stimer(), $on);
        return $this;
    }

    public function setReset($on)
    {
        $this->setState($this->reset(), $on);
        return $this;
    }

    public function setVendorId($on, $value)
    {
        $this->setState($this->vendor_id(), $on)->setAttribute("value", $value);
        return $this;
    }

    private function setState(SimpleXMLImplement $simpleXMLImplement, $on)
    {
        $simpleXMLImplement->setAttribute("state", self::stateText($on));
        return $simpleXMLImplement;
    }

    private static function stateText($on)
    {
        return $on ? "on" : "off";
    }
}