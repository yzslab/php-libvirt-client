<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-7
 * Time: 下午2:44
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Configuration\Domain;
use YunInternet\Libvirt\Connection;
use YunInternet\Libvirt\Contract\XMLElementContract;
use YunInternet\Libvirt\Exception\LibvirtException;

class DomainConfigurationBuilderTest extends TestCase
{
    public function testDomainConfigurationXMLBuilder()
    {
        $domainXML = new Domain("Test", "1024", "4");

        print $domainXML->getFormattedXML();

        $this->assertEquals("Test", $domainXML->getSimpleXMLElement()->name[0]->__toString());
        $this->assertEquals("1024", $domainXML->getSimpleXMLElement()->memory[0]->__toString());
        $this->assertEquals("4", $domainXML->getSimpleXMLElement()->vcpu[0]->__toString());
    }

    public function testDomainConfigurationXMLBuilderWithSetValue()
    {
        $domainXML = new Domain("Test", "1024", "4", "test");

        $domainXML->vcpu()->setValue(8);
        $this->assertEquals("8", $domainXML->getSimpleXMLElement()->vcpu[0]->__toString());

        $domainXML->cpu()
            ->setSocket(4)
            ->setCore(1)
            ->setThread(2)
        ;
        $this->assertEquals("4", $domainXML->getSimpleXMLElement()->cpu->topology["sockets"]->__toString());
        $this->assertEquals("1", $domainXML->getSimpleXMLElement()->cpu->topology["cores"]->__toString());

        $domainXML->os()
            ->setArchitecture("i686")
            ->setBootDevices(["hd"])
            ->addBootDevice("cdrom")
            ->setBootMenuTimeout(1000)
        ;

        /*
        $uuid = uniqid(null, true);
        $domainXML->setUUID($uuid);
        $this->assertEquals($uuid, $domainXML->getSimpleXMLElement()->uuid[0]->__toString());
        */

        $domainXML->setOnPowerOff("destroy");
        $domainXML->setOnReboot("restart");
        $domainXML->setOnCrash("restart");

        $domainXML->device()
            ->addDisk("volume", "disk", function (Domain\Device\Disk $disk) {
                $disk
                    ->volumeSource("testPool1", "testVolume1")
                    ->setDriverType("qcow2")
                    ->setTargetBus("virtio")
                    ->setTargetDevice("vda")
                ;
            })
            ->addDisk("volume", "disk", function (Domain\Device\Disk $disk) {
                $disk
                    ->volumeSource("testPool2", "testVolume2")
                    ->setDriverType("qcow2")
                    ->setTargetBus("virtio")
                    ->setTargetDevice("vdb")
                ;

                $disk->IOTune()->total_bytes_sec()->setValue(102400);
            })
            ->addDisk("file", "cdrom", function (Domain\Device\Disk $disk) {
                $disk
                    ->fileSource("/iso/iso.iso")
                    ->setDriverType("raw")
                    ->setTargetBus("ide")
                    ->setTargetDevice("hda")
                ;
            })
            ->addInterface("network", function (Domain\Device\InterfaceDevice $interfaceDevice) {
                $interfaceDevice
                    ->setSourceNetwork("default")
                    ->setMacAddress("52:54:00:00:00:01")
                    ->setModel("virtio")
                    ->applyNWFilter("clean-traffic", function (Domain\Device\InterfaceDevice\NWFilter $NWFilter) {
                        $NWFilter->addParameter("IP", "192.168.122.2");
                    })
                ;

                $interfaceDevice->bandwidth()
                    ->setInboundAverage(10240)
                    ->setInboundBurst(20480)
                    ->setInboundPeak(20480)
                    ->setOutboundAverage(10240)
                    ->setOutboundBurst(20480)
                    ->setOutboundPeak(20480)
                ;
            })
            ->useAbsoluteMousePointer()
            ->addVNCGraphic(function (Domain\Device\Graphic\VNCGraphic $VNCGraphic) {
                $VNCGraphic
                    ->setPassword("1234567890")
                    ->useAutoPort()
                    ->setListenAddress("0.0.0.0")
                ;
            })
            ->addQEMUGuestAgentChannel()
            ->disableMemoryBalloon()
        ;

        $domainXML->blkiotune()->weight()->setValue(1000);
        $domainXML->blkiotune()->addDevice("/dev/sda", 1000, function (Domain\BlockIOTune\Device $device) {
            $device->read_bytes_sec()->setValue(10240);
            $device->write_bytes_sec()->setValue(10240);
        });

        $formattedXML = $domainXML->getFormattedXML();

        print $formattedXML;

        $connection = new Connection("test:///default");
        $domain = $connection->domainDefineXML($domainXML->getXML());

        $this->assertTrue($domain->libvirt_domain_create());

        $this->expectException(LibvirtException::class);
        @$domain->libvirt_domain_create();

        $this->assertTrue($domain->libvirt_domain_destroy());
        $this->assertTrue($domain->libvirt_domain_undefine());
    }
}