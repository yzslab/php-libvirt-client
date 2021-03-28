<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 上午12:46
 */

namespace YunInternet\Libvirt\Contract;


interface XMLElementContract
{
    /**
     * @param string $name
     * @param mixed $value
     * @param array|callable $attributes
     * @return XMLElementContract Return child element
     */
    public function addChild($name, $value = null, $attributes = null) : XMLElementContract;


    /**
     * @param $name
     * @param null $value
     * @param null $attributes
     * @return XMLElementContract Return parent element
     */
    public function createChild($name, $value = null, $attributes = null) : XMLElementContract;

    /**
     * @param $name
     * @return XMLElementContract Return parent element
     */
    public function removeChild($name) : XMLElementContract;

    /**
     * @param mixed $value
     * @return XMLElementContract
     */
    public function setValue($value) : XMLElementContract;

    /**
     * @param string $name
     * @param mixed $value
     * @return XMLElementContract
     */
    public function setAttribute($name, $value) : XMLElementContract;

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name);

    /**
     * @return string
     */
    public function getXML() : string;
}