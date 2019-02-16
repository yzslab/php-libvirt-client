<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午10:35
 */

namespace YunInternet\Libvirt\Configuration\Network;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class Forward extends SimpleXMLImplement
{
    public function setMode($mode)
    {
        $this->setAttribute("mode", $mode);
        return $this;
    }
}