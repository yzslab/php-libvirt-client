<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-17
 * Time: 上午1:20
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Address extends SimpleXMLImplement
{
    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    public function setController($controller)
    {
        $this->setAttribute("controller", $controller);
        return $this;
    }

    public function setBus($bus)
    {
        $this->setAttribute("bus", $bus);
        return $this;
    }

    public function setTarget($target)
    {
        $this->setAttribute("target", $target);
        return $this;
    }

    public function setUnit($unit)
    {
        $this->setAttribute("unit", $unit);
        return $this;
    }
}