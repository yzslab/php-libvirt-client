<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-11
 * Time: 上午12:19
 */

namespace YunInternet\Libvirt\Configuration\StorageVolume;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class backingStore
 * @method XMLElementContract path()
 * @method XMLElementContract format()
 * @package YunInternet\Libvirt\Configuration\StorageVolume
 */
class BackingStore extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($path, $format, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);
        $this
            ->setPath($path)
            ->setFormat($format)
        ;
    }

    public function setPath($path)
    {
        $this->path()->setValue($path);
        return $this;
    }

    public function setFormat($format)
    {
        $this->format()->setAttribute("type", $format);
        return $this;
    }
}