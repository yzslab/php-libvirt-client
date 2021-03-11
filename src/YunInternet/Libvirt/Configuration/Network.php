<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午10:15
 */

namespace YunInternet\Libvirt\Configuration;


use YunInternet\Libvirt\Configuration\Network\Bandwidth;
use YunInternet\Libvirt\Configuration\Network\Bridge;
use YunInternet\Libvirt\Configuration\Network\Forward;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Network
 * @method XMLElementContract name()
 * @method XMLElementContract mac()
 * @package YunInternet\Libvirt\Configuration
 */
class Network extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($name, $enableIPv6WithoutGateway = true, $trustGuestRxFilters = false)
    {
        parent::__construct(new \SimpleXMLElement("<network/>"));
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name()->setValue($name);
        return $this;
    }

    public function setMacAddress($macAddress)
    {
        $this->mac()->setAttribute("address", $macAddress);
        return $this;
    }

    public function enableIPv6WithoutGateway($enable)
    {
        $this->setAttribute("ipv6", $enable ? "yes" : "no");
        return $this;
    }

    public function trustGuestRxFilters($trust)
    {
        $this->setAttribute("trustGuestRxFilters", $trust ? "yes" : "no");
        return $this;
    }

    private $forward;

    public function forward()
    {
        if (is_null($this->forward)) {
            $this->forward = new Forward($this->getSimpleXMLElement()->addChild("forward"));
        }
        return $this->forward;
    }

    private $bridge;

    public function bridge()
    {
        if (is_null($this->bridge)) {
            $this->bridge = new Bridge($this->getSimpleXMLElement()->addChild("bridge"));
        }
        return $this->bridge;
    }

    private $bandwidth;

    /**
     * @return Bandwidth
     */
    public function bandwidth()
    {
        if (is_null($this->bandwidth)) {
            $this->bandwidth = new Bandwidth($this->getSimpleXMLElement()->addChild("bandwidth"));
        }
        return $this->bandwidth;
    }
}