<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-8
 * Time: 下午5:27
 */

namespace YunInternet\Libvirt\Configuration\Domain\BlockIOTune;


use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;
use YunInternet\Libvirt\XMLImplement\SingletonChild;

/**
 * Class Device
 * @method XMLElementContract path()
 * @method XMLElementContract weight()
 * @method XMLElementContract read_bytes_sec()
 * @method XMLElementContract write_bytes_sec()
 * @method XMLElementContract read_iops_sec()
 * @method XMLElementContract write_iops_sec()
 * @package YunInternet\Libvirt\Configuration\Domain\BlockIOTune
 */
class Device extends SimpleXMLImplement
{
    use SingletonChild;

    public function __construct($path, $weight, \SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);

        $this->path()->setValue($path);
        $this->weight()->setValue($weight);
    }
}