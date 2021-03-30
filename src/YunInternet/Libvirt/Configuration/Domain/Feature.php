<?php


namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\Configuration\Domain\Feature\HyperV;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Feature
 * @method HyperV hyperv()
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class Feature extends SimpleXMLImplement
{
    protected $singletonChildWrappers = [
        "hyperv" => HyperV::class,
    ];

    use SingletonChild;
}