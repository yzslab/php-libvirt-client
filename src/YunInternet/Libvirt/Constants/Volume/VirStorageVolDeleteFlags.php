<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-16
 * Time: 下午4:45
 */

namespace YunInternet\Libvirt\Constants\Volume;


interface VirStorageVolDeleteFlags
{
    const VIR_STORAGE_VOL_DELETE_NORMAL = VIR_STORAGE_VOL_DELETE_NORMAL; //Delete metadata only (fast)
    const VIR_STORAGE_VOL_DELETE_ZEROED = VIR_STORAGE_VOL_DELETE_ZEROED; // Clear all data to zeros (slow)
    const VIR_STORAGE_VOL_DELETE_WITH_SNAPSHOTS = VIR_STORAGE_VOL_DELETE_WITH_SNAPSHOTS; //Force removal of volume, even if in use
}