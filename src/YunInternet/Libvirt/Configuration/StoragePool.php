<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:18
 */

namespace YunInternet\Libvirt\Configuration;


use YunInternet\Libvirt\Configuration\StoragePool\Source;
use YunInternet\Libvirt\Configuration\StoragePool\Target;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class StoragePool
 * @method XMLElementContract name()
 * @method XMLElementContract allocation()
 * @method XMLElementContract capacity()
 * @method XMLElementContract available()
 * @package YunInternet\Libvirt\Configuration
 */
class StoragePool extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($type, $name)
    {
        parent::__construct(new \SimpleXMLElement("<pool/>"));

        $this->setAttribute("type", $type);
        $this->name()->setValue($name);
    }

    private $source;

    public function source()
    {
        if (is_null($this->source)) {
            $this->source = new Source($this->getSimpleXMLElement()->addChild("source"));
        }
        return $this->source;
    }

    private $target;

    public function target()
    {
        if (is_null($this->target)) {
            $this->target = new Target($this->getSimpleXMLElement()->addChild("target"));
        }
        return $this->target;
    }
}