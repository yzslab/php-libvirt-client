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
    /**
     * @var $__childrenSingleton SimpleXMLImplement[]
     */
    private $__childrenSingleton = [];

    /**
     * @param $name
     * @param $arguments
     * @return XMLElementContract
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);

        $wrapChild = function ($name, $new) {
            if (property_exists($this, "singletonChildWrappers") && array_key_exists($name, $this->singletonChildWrappers)) {
                if ($new) {
                    $this->__childrenSingleton[$name] = new $this->singletonChildWrappers[$name]($this->__childrenSingleton[$name]->getSimpleXMLElement());
                } else {
                    $this->__childrenSingleton[$name] = $this->singletonChildWrappers[$name]::createFromSimpleXMLElement($this->__childrenSingleton[$name]->getSimpleXMLElement());
                }
            }
        };

        /**
         * If XML element doesn't have target $name, create one
         */
        if (!isset($this->getSimpleXMLElement()->{$name}[0])) {
            $this->__childrenSingleton[$name] = $this->addChild($name);
            $wrapChild($name, true);
        }

        /**
         * If $__childrenSingleton doesn't have target $name, create one from XML element
         */
        if (!isset($this->__childrenSingleton[$name])) {
            $this->__childrenSingleton[$name] = new SimpleXMLImplement($this->getSimpleXMLElement()->{$name}[0]);
            $wrapChild($name, false);
        }

        return $this->__childrenSingleton[$name];
    }
}