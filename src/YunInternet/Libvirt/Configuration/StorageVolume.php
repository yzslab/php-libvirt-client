<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:55
 */

namespace YunInternet\Libvirt\Configuration;


use YunInternet\Libvirt\Configuration\StorageVolume\BackingStore;
use YunInternet\Libvirt\Configuration\StorageVolume\Target;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class StorageVolume
 * @method XMLElementContract name()
 * @method XMLElementContract allocation()
 * @method XMLElementContract capacity()
 * @package YunInternet\Libvirt\Configuration
 */
class StorageVolume extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($type, $name)
    {
        parent::__construct(new \SimpleXMLElement("<volume/>"));
        $this->setAttribute("type", $type);
        $this->name()->setValue($name);
    }

    /*
    public function setKey($key)
    {
        $this->key()->setValue($key);
        return $this;
    }
    */

    public function setAllocation($allocation, $unit = "MiB")
    {
        $this->allocation()
            ->setAttribute("unit", $unit)
            ->setValue($allocation)
        ;
        return $this;
    }

    public function setCapacity($capacity, $unit = "MiB")
    {
        $this->capacity()
            ->setAttribute("unit", $unit)
            ->setValue($capacity)
        ;
        return $this;
    }

    /**
     * @var BackingStore $backingStore
     */
    private $backingStore;

    public function useBackingStore($path, $format)
    {
        if (is_null($this->backingStore)) {
            $this->backingStore = new BackingStore($path, $format, $this->getSimpleXMLElement()->addChild("backingStore"));
        } else {
            $this->backingStore
                ->setPath($path)
                ->setFormat($format)
            ;
        }
    }

    private $target;

    public function target()
    {
        if (is_null($this->target))
            $this->target = new Target($this->getSimpleXMLElement()->addChild("target"));
        return $this->target;
    }
}