<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午5:05
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Graphic extends SimpleXMLImplement
{
    public function __construct($type, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);
        $this->setType($type);
    }

    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }
}