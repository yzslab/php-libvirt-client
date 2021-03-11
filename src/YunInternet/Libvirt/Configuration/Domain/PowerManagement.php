<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午3:47
 */

namespace YunInternet\Libvirt\Configuration\Domain;


use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

/**
 * Class PowerManagement
 * @package YunInternet\Libvirt\Configuration\Domain
 */
class PowerManagement extends SimpleXMLImplement
{
    public function setAllowSuspend2Memory($enable)
    {
        $this->suspend2Mem()->setAttribute("enable", $this->getText($enable));
        return $this;
    }

    public function setAllowSuspend2Disk($enable)
    {
        $this->suspend2Disk()->setAttribute("enable", $this->getText($enable));
        return $this;
    }

    private $element;

    public function suspend2Mem()
    {
        if (is_null($this->element)) {
            $this->element = $this->addChild("suspend-to-mem");
        }
        return $this->element;
    }

    public function suspend2Disk()
    {
        if (is_null($this->element)) {
            $this->element = $this->addChild("suspend-to-disk");
        }
        return $this->element;
    }

    private function getText($enable)
    {
        return $enable ? "yes" : "no";
    }
}