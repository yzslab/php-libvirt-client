<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-11
 * Time: 上午12:27
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Configuration\StoragePool;
use YunInternet\Libvirt\Configuration\StorageVolume;
use YunInternet\Libvirt\Test\LibvirtTestConnection;

class StorageVolumeTest extends TestCase
{
    use LibvirtTestConnection;

    public function storagePool()
    {
        static $storagePoolInstance;
        if (is_null($storagePoolInstance)) {
            $storagePool = new StoragePool("dir", "testDirectoryStoragePool");
            $storagePool->target()->setPath("/testDirectoryStoragePool");

            $storagePoolInstance = $this->connection()->storagePoolDefineXML($storagePool->getXML());
            $storagePoolInstance->libvirt_storagepool_create();
        }
        return $storagePoolInstance;
    }

    public function testStorageVolume()
    {
        $storageVolume = new StorageVolume("file", "testStorageVolume");
        $storageVolume
            ->setCapacity(16, "GiB")
            ->setAllocation(0)
        ;
        $storageVolume->target()
            ->setFormat("qcow2")
            ->setPermission()
        ;
        print $storageVolume->getFormattedXML();

        $this->assertTrue(is_resource($storageVolumeResource = $this->storagePool()->libvirt_storagevolume_create_xml($storageVolume->getXML(), 0)));

        $storageVolumeInstance = new \YunInternet\Libvirt\StorageVolume($storageVolumeResource);

        $storageVolumePath = $storageVolumeInstance->libvirt_storagevolume_get_path();

        $storageVolumeWithBackingStore = new StorageVolume("file", "testStorageVolumeWithBackingStore");
        $storageVolumeWithBackingStore
            ->setCapacity(16, "GiB")
            ->setAllocation(0)
            ->useBackingStore($storageVolumePath, "qcow2")
        ;
        $storageVolumeWithBackingStore->target()
            ->setFormat("qcow2")
            ->setPermission()
        ;

        print $storageVolumeWithBackingStore->getFormattedXML();

        $this->assertTrue(is_resource($this->storagePool()->libvirt_storagevolume_create_xml($storageVolumeWithBackingStore->getXML(), 0)));

        $storageVolumeInstance = $this->storagePool()->storageVolumeLookupByName("testStorageVolumeWithBackingStore");
        $this->assertInstanceOf(\YunInternet\Libvirt\StorageVolume::class, $storageVolumeInstance);
    }
}