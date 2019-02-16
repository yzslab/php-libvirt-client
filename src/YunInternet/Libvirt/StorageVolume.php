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
 * @package YunInternet\Libvirt
 */
class StorageVolume extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_storagevolume_get_path" => true,
        "libvirt_storagevolume_delete" => true,
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