<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-11
 * Time: 上午12:14
 */

namespace YunInternet\Libvirt\Configuration\StorageVolume\Target;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Permission
 * @method XMLElementContract owner()
 * @method XMLElementContract group()
 * @method XMLElementContract mode()
 * @method XMLElementContract label()
 * @package YunInternet\Libvirt\Configuration\StorageVolume
 */
class Permission extends SimpleXMLImplement
{
    use SingletonChild;
}