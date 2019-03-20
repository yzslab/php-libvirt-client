<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-11
 * Time: 上午12:53
 */

namespace YunInternet\Libvirt;

use YunInternet\Libvirt\Exception\LibvirtException;

/**
 * Class StorageVolume
 * @method string libvirt_storagevolume_get_path()
 * @method boolean libvirt_storagevolume_delete($flags = 0) $flags -> YunInternet\Libvirt\Constants\Volume\VirStorageVolDeleteFlags
 * @method int libvirt_storagevolume_resize(int $byteCapacity, int $flags = 0) $flags -> VIR_STORAGE_VOL_RESIZE_ALLOCATE, VIR_STORAGE_VOL_RESIZE_DELTA, VIR_STORAGE_VOL_RESIZE_SHRINK
 * @method string|false libvirt_storagevolume_get_name()
 * @package YunInternet\Libvirt
 */
class StorageVolume extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_storagevolume_get_path" => true,
        "libvirt_storagevolume_delete" => true,
        "libvirt_storagevolume_resize" => true,
        "libvirt_storagevolume_get_name" => true,
    ];

    private $storageVolumeResource;

    public function __construct($storageVolumeResource)
    {
        $this->storageVolumeResource = $storageVolumeResource;
    }

    /**
     * @param int $flags YunInternet\Libvirt\Constants\Volume\VirStorageVolDeleteFlags
     * @return boolean
     * @throws LibvirtException
     */
    public function delete($flags = 0)
    {
        return $this->libvirt_storagevolume_delete($flags);
    }

    protected function getResources($functionName)
    {
        return [$this->storageVolumeResource];
    }
}