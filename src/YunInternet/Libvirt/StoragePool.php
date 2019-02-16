<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:51
 */

namespace YunInternet\Libvirt;

/**
 * Class StoragePool
 * @method resource libvirt_storagevolume_create_xml($xml, $flags = 0)
 * @method bool libvirt_storagepool_create()
 * @method bool libvirt_storagepool_set_autostart(bool $autostart)
 * @method bool libvirt_storagepool_get_autostart() Returns: TRUE if success, FALSE on error
 * @package YunInternet\Libvirt
 */
class StoragePool extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_storagevolume_create_xml" => true,
        "libvirt_storagepool_create" => true,
        "libvirt_storagepool_set_autostart" => true,
        "libvirt_storagepool_get_autostart" => true,
    ];

    private $storagePoolResource;

    public function __construct($storagePoolResource)
    {
        $this->storagePoolResource = $storagePoolResource;
    }

    public function storageVolumeCreateXML($xml, $flags = 0)
    {
        $storageVolume = $this->libvirt_storagevolume_create_xml($xml, $flags);
        return new StorageVolume($storageVolume);
    }

    protected function getResources($functionName)
    {
        return [$this->storagePoolResource];
    }
}