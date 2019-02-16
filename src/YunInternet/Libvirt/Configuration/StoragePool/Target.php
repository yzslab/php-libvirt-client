<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:20
 */

namespace YunInternet\Libvirt\Configuration\StoragePool;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Target
 * @method XMLElementContract path()
 * @package YunInternet\Libvirt\Configuration\StoragePool
 */
class Target extends SimpleXMLImplement
{
    use SingletonChild;

    public function setPath($path)
    {
        $this->path()->setValue($path);
        return $this;
    }
}