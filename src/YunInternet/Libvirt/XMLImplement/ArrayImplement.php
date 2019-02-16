<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午12:39
 */

namespace YunInternet\Libvirt\XMLImplement;


use YunInternet\Libvirt\Contract\XMLElementContract;

/**
 * Class ArrayImplement
 * @property-read string $name
 * @property-read string $value
 * @property-read array|null $attributes
 * @property-read array|null $children
 * @package YunInternet\Libvirt\XMLImplement
 */
class ArrayImplement implements XMLElementContract
{
    private $name;

    private $value;

    private $attributes = null;

    private $children = null;

    public function __construct($name, $value = null, $attributes = null)
    {
        $this->name = $name;
        $this->value = $value;

        if (is_callable($attributes))
            $attributes($this);
        else
            $this->attributes = $attributes;
    }

    public function addChild($name, $value = null, $attributes = null): XMLElementContract
    {
        $child = new self($name, $value, $attributes);
        $this->children[] = $child;
        return $child;
    }

    public function createChild($name, $value = null, $attributes = null): XMLElementContract
    {
        throw new \Exception("Not implemented");
    }

    public function deleteChild($name)
    {
        unset($this->children[$name]);
        return $this;
    }

    public function setValue($value) : XMLElementContract
    {
        $this->value = $value;
        return $this;
    }

    public function setAttribute($name, $value): XMLElementContract
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function deleteAttribute($name, $value)
    {
        unset($this->attributes[$name]);
        return $this;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function getXML(): string
    {
        throw new \Exception("Not implemented");
    }
}