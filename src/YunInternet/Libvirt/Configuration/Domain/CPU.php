<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午1:03
 */

namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class CPU
 * @method XMLElementContract cache()
 * @method XMLElementContract topology()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class CPU extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->setMode("host-passthrough");
    }

    public function setMode($mode)
    {
        $this->setAttribute("mode", $mode);
        return $this;
    }

    public function setMatch($match)
    {
        $this->setAttribute("match", $match);
        return $this;
    }

    public function setCacheMode($mode)
    {
        $this->cache()->setAttribute("mode", $mode);
        return $this;
    }

    public function setSocket($sockets)
    {
        $this->topology()->setAttribute("sockets", $sockets);
        return $this;
    }

    public function setCore($corePreSocket)
    {
        $this->topology()->setAttribute("cores", $corePreSocket);
        return $this;
    }

    public function setThread($threadPreCore)
    {
        $this->topology()->setAttribute("threads",$threadPreCore);
        return $this;
    }
}