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
     * @param string $name XML tag name
     * @param callable|array|null $filter A callable accept a \SimpleXMLElement as parameter and return bool|string|int; true represented push the child to the array, string|int will be used as the array key
     * @param callable|string|null $wrapper A callable accept a \SimpleXMLElement as parameter and return a wrapped object, or a classname, or null represented that do not wrap children
     * @return array
     */
    public function getChildren($name, $filter = null, $wrapper = null): array;

    /**
     * Find a child which should be unique
     * @param string $name XML tag name
     * @param callable|null $filter A closure accept a \SimpleXMLElement as parameter and return bool|string|int; true represented push the child to the array, string|int will be used as the array key
     * @param callable|string|null $wrapper A closure accept a \SimpleXMLElement as parameter and return a wrapped object, or a classname, or null represented that do not wrap children
     * @return XMLElementContract|null
     */
    public function findChild($name, $filter = null, $wrapper = null);

    /**
     * @param $name
     * @return XMLElementContract Return parent element
     */
    public function removeChildByName($name) : XMLElementContract;

    /**
     * @param XMLElementContract $XMLElementContract
     * @return XMLElementContract
     */
    public function removeChild(XMLElementContract $XMLElementContract): XMLElementContract;

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