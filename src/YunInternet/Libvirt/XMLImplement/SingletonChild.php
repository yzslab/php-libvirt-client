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
     * @param string $name
     * @param array|null $arguments
     * @return XMLElementContract
     */
    public function __call($name, $arguments)
    {
        if (property_exists($this, "singletonChildAliases") && array_key_exists($name, $this->singletonChildAliases)) {
            $name = $this->singletonChildAliases[$name];
        }

        $wrapChild = function ($child, $name, $new) {
            if (property_exists($this, "singletonChildWrappers") && array_key_exists($name, $this->singletonChildWrappers)) {
                if ($new) {
                    return new $this->singletonChildWrappers[$name]($child->getSimpleXMLElement());
                } else {
                    return $this->singletonChildWrappers[$name]::createFromSimpleXMLElement($child->getSimpleXMLElement());
                }
            }
            return $child;
        };

        $new = false;
        $child = $this->findChild($name, $arguments);
        if (is_null($child)) {
            $new = true;
            $child = $this->addChild($name, null, $arguments);
        }
        return $wrapChild($child, $name, $new);
    }
}