<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 16:18
 */

namespace YunInternet\Libvirt\Test\Unit;


use YunInternet\Libvirt\Domain;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\Exception\LibvirtException;
use YunInternet\Libvirt\Test\BaseConnectionTestCase;

class ConnectionTest extends BaseConnectionTestCase
{
    public function testNodeGetInfoConnection()
    {
        var_dump($nodeInfo = $this->getLibvirtConnection()->libvirt_node_get_info());
        $this->assertArrayHasKey("model", $nodeInfo);
    }

    public function testDomainGetCount()
    {
        var_dump($count = $this->getLibvirtConnection()->libvirt_domain_get_counts());
        $this->assertArrayHasKey("active", $count);
    }

    public function testListDomainIds()
    {
        var_dump($domains = $this->getLibvirtConnection()->libvirt_list_domains());

        $this->assertTrue(true);
    }

    public function testListDomainResources()
    {
        var_dump($domains = $this->getLibvirtConnection()->libvirt_list_domain_resources());

        $this->assertTrue(true);
    }

    public function testGetCapabilities()
    {
        $capabilities = $this->getLibvirtConnection()->libvirt_connect_get_capabilities();

        print $capabilities;

        $this->assertTrue(is_string($capabilities));
    }

    public function testDomainLookupByName()
    {
        $domain = $this->getLibvirtConnection()->domainLookupByName("test");
        $this->assertInstanceOf(Domain::class, $domain);

        try {
            @$this->getLibvirtConnection()->domainLookupByName("not-exists");
            $this->assertFalse(true);
        } catch (LibvirtException $libvirtException) {
            $this->assertEquals(ErrorCode::DOMAIN_NOT_FOUND, $libvirtException->getCode());
        }
    }

    /**
     * @throws \Exception
     */
    public function testInvokeFunctionNotInWhiteList()
    {
        $this->expectException(LibvirtException::class);
        $this->getLibvirtConnection()->function_no_in_white_list();
    }
}