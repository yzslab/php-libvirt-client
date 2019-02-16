<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午10:40
 */

namespace YunInternet\Libvirt\Configuration\Network;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Bridge extends SimpleXMLImplement
{
    public function setName($name)
    {
        $this->setAttribute("name", $name);
        return $this;
    }
}