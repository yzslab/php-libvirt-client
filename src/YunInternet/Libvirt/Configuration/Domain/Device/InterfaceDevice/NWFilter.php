<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午6:38
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device\InterfaceDevice;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class NWFilter extends SimpleXMLImplement
{
    public function __construct($filter, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);
        $this->setFilter($filter);
    }

    public function setFilter($filter)
    {
        $this->setAttribute("filter", $filter);
        return $this;
    }

    /**
     * @param string|callable $nameOrCallable
     * @param string|null $value
     * @return $this
     */
    public function addParameter($nameOrCallable, $value = null)
    {
        $parameter = $this->addChild("parameter");

        if (is_callable($nameOrCallable)) {
            $nameOrCallable($parameter);
        } else {
            $parameter
                ->setAttribute("name", $nameOrCallable)
                ->setAttribute("value", $value)
            ;
        }

        return $this;
    }
}