<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午5:18
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Channel
 * @method XMLElementContract source()
 * @method XMLElementContract target()
 * @package YunInternet\Libvirt\Configuration\Domain\Device
 */
class Channel extends SimpleXMLImplement
{
    use SingletonChild;

    /**
     * Channel constructor.
     * @param string $type
     * @param \SimpleXMLElement $simpleXMLElement
     */
    public function __construct($type, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->setType($type);
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }
}