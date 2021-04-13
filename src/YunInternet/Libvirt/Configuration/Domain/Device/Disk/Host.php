<?php

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;

use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

class host extends SimpleXMLImplement
{
    use SingletonChild;

    public function getHost(): array
    {
        $array = [];

        if ($this->getAttribute2Array('transport', $array) === 'unix') {
            $this->getAttribute2Array('socket', $array);
        } else {
            $this->getAttribute2Array('name', $array, 'host');
            $this->getAttribute2Array('port', $array);
        }

        return $array;
    }

    public function setHost(array $array)
    {
        if ($this->getAttribute('transport') === 'unix') {
            $this->setAttributeFromArray('socket', $array);
        } else {
            $this->setAttributeFromArray('host', $array, 'name');
            $this->setAttributeFromArray('port', $array);
        }

        return $this;
    }


}