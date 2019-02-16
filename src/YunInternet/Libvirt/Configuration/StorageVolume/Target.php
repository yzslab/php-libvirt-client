<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-11
 * Time: 上午12:10
 */

namespace YunInternet\Libvirt\Configuration\StorageVolume;


use YunInternet\Libvirt\Configuration\StorageVolume\Target\Permission;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Target
 * @method XMLElementContract path()
 * @method XMLElementContract format()
 * @package YunInternet\Libvirt\Configuration\StorageVolume
 */
class Target extends SimpleXMLImplement
{
    use SingletonChild;

    public function setFormat($format)
    {
        $this->format()->setAttribute("type", $format);
        return $this;
    }

    public function setPermission($mode = "0600", $owner = 0, $group = 0)
    {
        $this->permissions()->mode()->setValue($mode);
        $this->permissions()->owner()->setValue($owner);
        $this->permissions()->group()->setValue($group);
        return $this;
    }

    private $permissions;

    public function permissions()
    {
        if (is_null($this->permissions))
            $this->permissions = new Permission($this->getSimpleXMLElement()->addChild("permissions"));
        return $this->permissions;
    }
}