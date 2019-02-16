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


    public function setValue($value) : XMLElementContract
    {
        $this->simpleXMLElement[0] = $value;
        return $this;
    }

    public function setAttribute($name, $value): XMLElementContract
    {
        $this->simpleXMLElement[$name] = $value;
        return $this;
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
        $lineBreakPosition = strpos($XMLContent, PHP_EOL);
        return substr($XMLContent, $lineBreakPosition + 1);
    }

    public function getFormattedXML()
    {
        $dom = dom_import_simplexml($this->getSimpleXMLElement())->ownerDocument;
        $dom->formatOutput = true;
        $xmlContent = $dom->saveXML();

        $lineBreakPosition = strpos($xmlContent, PHP_EOL);
        return substr($xmlContent, $lineBreakPosition + 1);
    }
}