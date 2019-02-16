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
 * @method bool libvirt_storagepool_destroy()
 * @method bool libvirt_storagepool_delete()
 * @method bool libvirt_storagepool_undefine()
 * @method resource libvirt_storagevolume_lookup_by_name(string $name)
 * @package YunInternet\Libvirt
 */
class StoragePool extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_storagevolume_create_xml" => true,
        "libvirt_storagepool_create" => true,
        "libvirt_storagepool_set_autostart" => true,
        "libvirt_storagepool_get_autostart" => true,
        "libvirt_storagepool_destroy" => true,
        "libvirt_storagepool_delete" => true,
        "libvirt_storagepool_undefine" => true,
        "libvirt_storagevolume_lookup_by_name" => true,
    ];

    private $storagePoolResource;

    public function __construct($storagePoolResource)
    {
        $this->storagePoolResource = $storagePoolResource;
    }

    public function storageVolumeCreateXML($xml, $flags = 0)
    {
        $storageVolumeResource = $this->libvirt_storagevolume_create_xml($xml, $flags);
        return new StorageVolume($storageVolumeResource);
    }

    public function storageVolumeLookupByName($name)
    {
        $storageVolumeResource = $this->libvirt_storagevolume_lookup_by_name($name);
        return new StorageVolume($storageVolumeResource);
    }

    protected function getResources($functionName)
    {
        return [$this->storagePoolResource];
    }
}