<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午2:17
 */

namespace YunInternet\Libvirt\XMLImplement;


use YunInternet\Libvirt\Contract\XMLElementContract;

trait SingletonChild
{
    private $__childrenSingleton = [];

    /**
     * @param $name
     * @param $arguments
     * @return XMLElementContract
     */
    public function __call($name, $arguments)
    {
        /**
         * If XML element doesn't have target $name, create one
         */
        if (!isset($this->getSimpleXMLElement()->{$name}[0])) {
            $this->__childrenSingleton[$name] = $this->addChild($name);
        }

        /**
         * If $__childrenSingleton doesn't have target $name, create one from XML element
         */
        if (!isset($this->__childrenSingleton[$name])) {
            $this->__childrenSingleton[$name] = new SimpleXMLImplement($this->getSimpleXMLElement()->{$name}[0]);
        }

        return $this->__childrenSingleton[$name];
    }
}