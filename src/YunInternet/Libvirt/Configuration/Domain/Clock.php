<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-7
 * Time: 下午10:40
 */

namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Clock extends SimpleXMLImplement
{
    public function __construct($offset, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->setOffset($offset);
    }

    public function setOffset($offset)
    {
        $this->setAttribute("offset", $offset);
        return $this;
    }

    /**
     * @param $name
     * @param null $configuration
     * @return $this|\YunInternet\Libvirt\Contract\XMLElementContract
     */
    public function addTimer($name, $configuration = null)
    {
        $timer = $this->addChild("timer", null, ["name" => $name]);
        if (is_callable($configuration)) {
            $configuration($timer);
            return $this;
        }
        return $timer;
    }
}