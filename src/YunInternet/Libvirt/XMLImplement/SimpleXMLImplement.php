<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 上午12:54
 */

namespace YunInternet\Libvirt\XMLImplement;


use YunInternet\Libvirt\Contract\XMLElementContract;

class SimpleXMLImplement implements XMLElementContract
{
    private $simpleXMLElement;

    public function __construct(\SimpleXMLElement $simpleXMLElement)
    {
        $this->simpleXMLElement = $simpleXMLElement;
    }

    public function addChild($name, $value = null, $attributes = null): XMLElementContract
    {
        $child = $this->simpleXMLElement->addChild($name, $value);
        $childSimpleXMLImplement = new self($child);

        if (is_callable($attributes)) {
            $attributes($childSimpleXMLImplement); // Use callable function to set attributes
        } else if (is_array($attributes)) {
            foreach ($attributes as $name => $value) {
                $childSimpleXMLImplement->setAttribute($name, $value);
            }
        }

        return $childSimpleXMLImplement;
    }

    public function createChild($name, $value = null, $attributes = null): XMLElementContract
    {
        $this->addChild(... func_get_args());
        return $this;
    }

    public function getChildren($name, $filter = null, $wrapper = null): array
    {
        if (is_array($filter)) {
            $attributes = $filter;
            $filter = function (SimpleXMLImplement $simpleXMLImplement) use ($attributes) {
                foreach ($attributes as $key => $value) {
                    if ($simpleXMLImplement->getAttribute($key) !== $value) {
                        return false;
                    }
                }
                return true;
            };
        } else if (!is_callable($filter)) {
            $filter = function ($element) {
                return true;
            };
        }

        // Convert all non-callable $wrapper to callable
        if (is_string($wrapper)) {
            $wrapperClassName = $wrapper;
            $wrapper = function ($simpleXMLElement) use ($wrapperClassName) {
                return $wrapperClassName::createFromSimpleXMLElement($simpleXMLElement);
            };
        } else if (!is_callable($wrapper)) {
            $wrapper = function ($simpleXMLElement) {
                return new SimpleXMLImplement($simpleXMLElement);
            };
        }

        $collection = [];
        foreach ($this->simpleXMLElement->{$name} as $child) {
            $child = $wrapper($child);
            $key = $filter($child);
            // Add to collection
            if ($key === true) {
                $collection[] = $child;
            } else if (is_string($key) || is_integer($key)) {
                $collection[$key] = $child;
            }
        }

        return $collection;
    }

    public function findChild($name, $filter = null, $wrapper = null)
    {
        $collection = $this->getChildren($name, $filter, $wrapper);
        $collectionCount = count($collection);
        if ($collectionCount === 1) {
            return $collection[0];
        } else if ($collectionCount > 1) {
            throw new \Exception("filter result not unique");
        }
        return null;
    }

    public function removeChildByName($name): XMLElementContract
    {
        unset($this->simpleXMLElement->{$name});
        return $this;
    }

    public function removeChild(XMLElementContract $XMLElementContract): XMLElementContract
    {
        $child = dom_import_simplexml($XMLElementContract->getSimpleXMLElement());
        $child->parentNode->removeChild($child);
        return $this;
    }

    public function setValue($value): XMLElementContract
    {
        $this->simpleXMLElement[0] = $value;
        return $this;
    }

    public function setAttribute($name, $value): XMLElementContract
    {
        $this->simpleXMLElement[$name] = $value;
        return $this;
    }

    public function getAttribute($name)
    {
        return $this->simpleXMLElement[$name] ? $this->simpleXMLElement[$name]->__toString() : null;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getSimpleXMLElement()
    {
        return $this->simpleXMLElement;
    }

    public function getXML(): string
    {
        $XMLContent = $this->simpleXMLElement->asXML();
        self::removeXMLVersionElement($XMLContent);
        return $XMLContent;
    }

    public function getFormattedXML()
    {
        $dom = dom_import_simplexml($this->getSimpleXMLElement())->ownerDocument;
        $dom->formatOutput = true;
        $XMLContent = $dom->saveXML();
        self::removeXMLVersionElement($XMLContent);
        return $XMLContent;
    }

    public static function createFromSimpleXMLElement(\SimpleXMLElement $simpleXMLElement)
    {
        $reflectionClass = new \ReflectionClass(static::class);

        // Get self::class
        $parent = $reflectionClass;
        do {
            if ($parent->name === self::class) {
                break;
            }
        } while ($parent = $parent->getParentClass());
        if ($parent === false) {
            throw new \Exception("unexpected"); // This should never happen
        }

        // Prepare to set the value of property simpleXMLElement
        $simpleXMLElementProperty = $parent->getProperty("simpleXMLElement");
        $simpleXMLElementProperty->setAccessible(true);

        $configuration = $reflectionClass->newInstanceWithoutConstructor();
        $simpleXMLElementProperty->setValue($configuration, $simpleXMLElement);
        return $configuration;
    }

    private static function removeXMLVersionElement(&$xml)
    {
        if (strpos($xml, '<?xml version="') === 0) {
            $xml = substr($xml, strpos($xml, ">") + 2);
        }
    }
}