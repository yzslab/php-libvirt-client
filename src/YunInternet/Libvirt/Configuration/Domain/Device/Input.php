<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午4:53
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Input extends SimpleXMLImplement
{
    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    public function setBus($bus)
    {
        $this->setAttribute("bus", $bus);
        return $this;
    }
}