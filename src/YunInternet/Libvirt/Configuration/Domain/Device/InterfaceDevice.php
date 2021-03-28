<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午4:37
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\Configuration\Domain\Device\InterfaceDevice\Bandwidth;
use YunInternet\Libvirt\Configuration\Domain\Device\InterfaceDevice\NWFilter;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class InterfaceDevice
 * @method XMLElementContract source()
 * @method XMLElementContract mac()
 * @method XMLElementContract model()
 * @method Bandwidth bandwidth()
 * @package YunInternet\Libvirt\Configuration\Domain\Device
 */
class InterfaceDevice extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "bandwidth" => Bandwidth::class,
    ];

    use SingletonChild;

    public function __construct($type, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->setAttribute("type", $type);
    }

    public function setSourceNetwork($network)
    {
        $this->source()->setAttribute("network", $network);
        return $this;
    }

    public function getSourceNetwork()
    {
        return $this->source()->getAttribute("network");
    }

    public function setSourceBridge($bridge)
    {
        $this->source()->setAttribute("bridge", $bridge);
        return $this;
    }

    public function getSourceBridge($bridge)
    {
        return $this->source()->getAttribute("bridge");
    }

    public function setMacAddress($macAddress)
    {
        $this->mac()->setAttribute("address", $macAddress);
        return $this;
    }

    public function getMacAddress()
    {
        return $this->mac()->getAttribute("address");
    }

    public function setModel($model)
    {
        $this->model()->setAttribute("type", $model);
        return $this;
    }

    public function getModel()
    {
        return $this->model()->getAttribute("type");
    }

    public function applyNWFilter($filter, $configuration = null)
    {
        $NWFilter = new NWFilter($filter, $this->getSimpleXMLElement()->addChild("filterref"));

        if (is_callable($configuration)) {
            $configuration($NWFilter);
            return $this;
        }

        return $NWFilter;
    }
}