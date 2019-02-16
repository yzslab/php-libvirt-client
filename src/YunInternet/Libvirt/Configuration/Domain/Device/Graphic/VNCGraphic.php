<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午5:07
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device\Graphic;


use YunInternet\Libvirt\Configuration\Domain\Device\Graphic;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class VNCGraphic
 * @method XMLElementContract listen()
 * @package YunInternet\Libvirt\Configuration\Domain\Device\Graphic
 */
class VNCGraphic extends Graphic
{
    use SingletonChild;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct("vnc", $simpleXMLElement);
    }

    public function setPort($port)
    {
        $this
            ->setAttribute("port", $port)
            ->setAttribute("autoport", "no")
        ;
        return $this;
    }

    public function useAutoPort()
    {
        $this
            ->setAttribute("port", "-1")
            ->setAttribute("autoport", "yes")
        ;
        return $this;
    }

    /**
     * @param int|string $port-1 for auto port
     * @return $this
     */
    public function enableWebsocket($port)
    {
        $this->setAttribute("websocket", $port);
        return $this;
    }

    public function setPassword($password)
    {
        $this->setAttribute("passwd", $password);
        return $this;
    }

    public function setSharePolicy($sharePolicy)
    {
        $this->setAttribute("sharePolicy", $sharePolicy);
        return $this;
    }

    public function setListenAddress($address)
    {
        $this->listen()
            ->setAttribute("type", "address")
            ->setAttribute("address", $address)
        ;

        return $this;
    }
}