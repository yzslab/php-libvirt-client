<?php

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;

use YunInternet\Libvirt\Constants\Constants;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

class Source extends SimpleXMLImplement
{
    use SingletonChild;

    public function setFile(string $fileName)
    {
        $this->setAttribute('file', $fileName);
        return $this;
    }

    public function getFile(): string
    {
        return $this->getAttribute('file');
    }

    public function setDir(string $fileName)
    {
        $this->setAttribute('dir', $fileName);
        return $this;
    }

    public function getDir(): string
    {
        return $this->getAttribute('dir');
    }

    public function setVolume(string $poolName, string $volumeName)
    {
        $this->setAttribute('pool', $poolName);
        $this->setAttribute('volume', $volumeName);
        return $this;
    }

    public function getNetwork(): array
    {
        $array = [];
        $protocol = $this->getAttribute2Array('protocol', $array);

        if ($protocol === Constants::NETWORK_TYPE_HTTP || $protocol === Constants::NETWORK_TYPE_HTTPS) {
            $this->getAttribute2Array('query', $array);
        }

        if ($this->host()->getAttribute2Array('transport', $array) === 'unix') {
            $this->host()->getAttribute2Array('socket', $array);
        } else
        {
            $this->host()->getAttribute2Array('name', $array, 'host');
            $this->host()->getAttribute2Array('port', $array);
        }

        return $array;
    }

    public function getNetworkNFS(): array
    {
        $array = [];
        $this->getAttribute2Array('name', $array);
        $this->identity()->getAttribute2Array('user', $array);
        $this->identity()->getAttribute2Array('group', $array);
        return $array;
    }

    public function getNetworkGLUSTER(): array
    {
        $array = [];
        $this->getAttribute2Array('name', $array);
        $this->getAttribute2Array('diskSourceNetworkHost', $array);
        $this->getAttribute2Array('encryption', $array);
        return $array;
    }

    public function getVolume(): array
    {
        return array('pool' => $this->getAttribute('pool'), 'volume' => $this->getAttribute('volume'));
    }

    public function setDevice(string $deviceName)
    {
        $this->setAttribute('dev', $deviceName);
        return $this;
    }

    public function getDevice(): string
    {
        return $this->getAttribute('dev');
    }

    public function setProtocol(string $protocol)
    {
        $this->setAttribute('protocol', $protocol);
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->getAttribute('protocol');
    }
}