<?php

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;

use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class BackingStore
 * @method XMLElementContract source()
 * @method XMLElementContract format()
 * @method BackingStore backingStore()
 * @package YunInternet\Libvirt\Configuration\Domain\Device\Disk
 */
class BackingStore extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "backingStore" => self::class,
        "source" => Source::class,
    ];

    use SingletonChild;

    public function isActive(): bool
    {
        return !empty($this->getType());
    }

    public function getLayer(): int
    {
        if ($this->isActive() === false) {
            return 0;
        }

        $layer = 1;
        if ($this->backingStore()->isActive()) {
            $layer += $this->backingStore()->getLayer();
        }
        return $layer;
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

    public function getFileSource()
    {
        return $this->source()->getAttribute('file');
    }

    public function setFormat(string $type)
    {
        $this->format()->setAttribute("type", $type);
        return $this;
    }

    public function getFormat()
    {
        return $this->format()->getAttribute('type');
    }

    public function hasBacking(): bool
    {
        return is_null($this->findChild("backingStore")) === false && $this->backingStore()->isActive();
    }

    public function sourceClass(): string
    {
        return ($this->getType() === 'network' ? $this->getType() . $this->source()->getProtocol() : $this->getType());
    }
}