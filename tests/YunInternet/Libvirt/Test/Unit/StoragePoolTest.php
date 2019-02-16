<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午11:33
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Configuration\StoragePool;
use YunInternet\Libvirt\Connection;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\Test\LibvirtTestConnection;

class StoragePoolTest extends TestCase
{
    use LibvirtTestConnection;

    public function testDirectoryStoragePool()
    {
        $storagePool = new StoragePool("dir", "testDirectoryStoragePool");

        $storagePool->target()->setPath("/testDirectoryStoragePool");

        print $storagePool->getFormattedXML();

        $this->assertTrue(is_resource($this->connection()->libvirt_storagepool_define_xml($storagePool->getXML())));

        $this->assertInstanceOf(\YunInternet\Libvirt\StoragePool::class, $storagePoolInstance = $this->connection()->storagePoolLookupByName("testDirectoryStoragePool"));

        $storagePoolInstance->libvirt_storagepool_create();
        $this->expectExceptionCode(ErrorCode::STORAGE_POOL_IS_ACTIVE);
        @$storagePoolInstance->libvirt_storagepool_create();

        $this->assertTrue($storagePoolInstance->libvirt_storagepool_set_autostart(true));
        $this->assertTrue($storagePoolInstance->libvirt_storagepool_get_autostart());
        $this->assertTrue($storagePoolInstance->libvirt_storagepool_set_autostart(false));
        $this->assertFalse($storagePoolInstance->libvirt_storagepool_get_autostart());
    }
}