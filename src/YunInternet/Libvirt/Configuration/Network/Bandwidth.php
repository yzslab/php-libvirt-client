<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-8
 * Time: 上午12:38
 */

namespace YunInternet\Libvirt\Configuration\Network;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Bandwidth
 * @method XMLElementContract inbound()
 * @method XMLElementContract outbound()
 * @package YunInternet\Libvirt\Configuration\Network
 */
class Bandwidth extends SimpleXMLImplement
{
    use SingletonChild;

    public function setInboundAverage($average)
    {
        $this->inbound()->setAttribute("average", $average);
        return $this;
    }

    public function setInboundPeak($peak)
    {
        $this->inbound()->setAttribute("peak", $peak);
        return $this;
    }

    public function setInboundFloor($floor)
    {
        $this->inbound()->setAttribute("floor", $floor);
        return $this;
    }

    public function setInboundBurst($burst)
    {
        $this->inbound()->setAttribute("burst", $burst);
        return $this;
    }

    public function setOutboundAverage($average)
    {
        $this->outbound()->setAttribute("average", $average);
        return $this;
    }

    public function setOutboundPeak($peak)
    {
        $this->outbound()->setAttribute("peak", $peak);
        return $this;
    }

    public function setOutboundBurst($burst)
    {
        $this->outbound()->setAttribute("burst", $burst);
        return $this;
    }

}