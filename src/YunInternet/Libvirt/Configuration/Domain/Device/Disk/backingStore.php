<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-17
 * Time: 上午1:20
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;

use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class backingStore
 * @method XMLElementContract source()
 * @method XMLElementContract format()
 * @package YunInternet\Libvirt\Configuration\Domain\Device\Disk
 */
class backingStore extends SimpleXMLImplement
{
    use SingletonChild;

    public function isActive()
    {
        return !empty($this->getType());
    }

    public function setType($type)
    {
        $this->setAttribute("type", $type);
        return $this;
    }

    public function getType()
    {
        return $this->getAttribute('type');
    }

    public function fileSource($filePath)
    {
        $this->source()->setAttribute('file', $filePath);
        return $this;
    }

    public function getfileSource()
    {
        return $this->source()->getAttribute('file');
    }

    public function getFormat()
    {
        return $this->format()->getAttribute('type');
    }
}