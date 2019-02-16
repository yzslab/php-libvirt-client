<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-10
 * Time: 下午10:48
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Configuration\Network;
use YunInternet\Libvirt\Test\LibvirtTestConnection;

class NetworkConfigurationBuilderTest extends TestCase
{
    use LibvirtTestConnection;

    public function testHostBridgeNetwork()
    {
        $network = new Network("hostBridgeNetwork0");

        $network->forward()->setMode("bridge");
        $network->bridge()->setName("br0");

        print $network->getFormattedXML();

        $this->assertTrue(is_resource($this->connection()->libvirt_network_define_xml($network->getXML())));
    }

    public function testVeryIsolatedNetwork()
    {
        $network = new Network("veryIsolatedNetwork0");

        print $network->getFormattedXML();

        $this->assertTrue(is_resource($this->connection()->libvirt_network_define_xml($network->getXML())));
    }
}