<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午4:31
 */

namespace YunInternet\Libvirt\Configuration\Domain\Device;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

class MemoryBalloon extends SimpleXMLImplement
{
    public function setModel($model)
    {
        $this->setAttribute("model", $model);
        return $this;
    }
}