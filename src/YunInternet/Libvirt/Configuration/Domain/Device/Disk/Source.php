<?php

namespace YunInternet\Libvirt\Configuration\Domain\Device\Disk;

use YunInternet\Libvirt\Constants\Constants;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Source
 * @method XMLElementContract host()
 * @package YunInternet\Libvirt\Configuration\Domain\Device\Host
 */
class Source extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "host" => Host::class,
    ];

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

        $this->timeout()->getAttribute2Array('seconds', $array, 'timeout');

        return $array;
    }

    public function setNetwork(array $array)
    {
        $this->setAttributeFromArray('protocol', $array);
        $this->setAttributeFromArray('transport', $array);
        $protocol = $this->getProtocol();

        if ($protocol === Constants::NETWORK_TYPE_HTTP || $protocol === Constants::NETWORK_TYPE_HTTPS) {
            $this->setAttributeFromArray('query', $array);
        }

        $this->timeout()->setAttributeFromArray('timeout', $array, 'seconds');

        return $this;
    }

    public function getProtocol(): string
    {
        return $this->getAttribute('protocol');
    }

    public function getNetworkNFS(): array
    {
        $array = [];
        $this->getAttribute2Array('name', $array);
        $this->identity()->getAttribute2Array('user', $array);
        $this->identity()->getAttribute2Array('group', $array);

        $this->getDefaults($array);

        return $array;
    }

    public function getDefaults(array &$array)
    {
        $this->snapshot()->getAttribute2Array('name', $array, 'snapshot');
        $this->config()->getAttribute2Array('file', $array, 'config');
        $this->readahead()->getAttribute2Array('size', $array, 'readahead');
    }

    public function setNetworkNFS(array $array): array
    {
        $this->setAttributeFromArray('name', $array);
        $this->identity()->setAttributeFromArray('user', $array);
        $this->identity()->setAttributeFromArray('group', $array);

        $this->setDefaults($array);

        return $this;
    }

    public function setDefaults(array $array)
    {
        $this->snapshot()->setAttributeFromArray('snapshot', $array, 'name');
        $this->config()->setAttributeFromArray('config', $array, 'file');
        $this->readahead()->setAttributeFromArray('readahead', $array, 'size');
    }

    public function getNetworkGLUSTER(): array
    {
        $array = [];
        $this->getAttribute2Array('name', $array);
        $this->getAttribute2Array('diskSourceNetworkHost', $array);
        $this->getAttribute2Array('encryption', $array);

        $this->getDefaults($array);

        return $array;
    }

    public function setNetworkGLUSTER(array $array): array
    {
        $this->setAttributeFromArray('name', $array);
        $this->setAttributeFromArray('diskSourceNetworkHost', $array);
        $this->setAttributeFromArray('encryption', $array);

        $this->setDefaults($array);

        return $this;
    }

    public function getAuth()
    {
        $array = [];

        $this->auth()->getAttribute2Array('username', $array);
        $this->auth()->secret()->getAttribute2Array('type', $array);
        $this->auth()->secret()->getAttribute2Array('usage', $array, 'password');
        return $array;
    }

    public function setAuth(array $array)
    {
        $this->auth()->setAttributeFromArray('username', $array);
        $this->auth()->secret()->setAttributeFromArray('type', $array);
        $this->auth()->secret()->setAttributeFromArray('password', $array, 'usage');

        return $this;
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

    /**
     * @param callable|null $filter A callable accept a Host as parameter
     * @return array Host[]
     */
    public function getHostCollection($filter = null): array
    {
        return $this->getChildren("host", $filter, Host::class);
    }
}