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
use YunInternet\Libvirt\Exception\DomainException;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\Exception\LibvirtException;
use YunInternet\Libvirt\XMLImplement\SimpleXMLImplement;

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

        $domainXML->cpu()
            ->setSocket(4)
            ->setCore(1)
            ->setThread(2);

        $domainXML->os()
            ->setArchitecture("i686")
            ->setBootDevices(["hd"])
            ->addBootDevice("cdrom")
            ->setBootMenuTimeout(1000);

        $uuid = trim(file_get_contents("/proc/sys/kernel/random/uuid"));
        $domainXML->setUUID($uuid);

        $domainXML->setOnPowerOff("destroy");
        $domainXML->setOnReboot("restart");
        $domainXML->setOnCrash("restart");

        $domainXML->device()
            ->setEmulator("/usr/bin/qemu-system-x86_64")
            ->addDisk("volume", "disk", function (Domain\Device\Disk $disk) {
                $disk
                    ->volumeSource("testPool1", "testVolume1")
                    ->setDriverType("qcow2")
                    ->setTargetBus("virtio")
                    ->setTargetDevice("vda");
            })
            ->addDisk("volume", "disk", function (Domain\Device\Disk $disk) {
                $disk
                    ->volumeSource("testPool2", "testVolume2")
                    ->setDriverType("qcow2")
                    ->setTargetBus("virtio")
                    ->setTargetDevice("vdb");

                $disk->IOTune()->total_bytes_sec()->setValue(102400);
            })
            ->addDisk("file", "cdrom", function (Domain\Device\Disk $disk) {
                $disk
                    ->fileSource("/iso/iso.iso")
                    ->setDriverType("raw")
                    ->setTargetBus("ide")
                    ->setTargetDevice("hda");
            })
            ->addInterface("network", function (Domain\Device\InterfaceDevice $interfaceDevice) {
                $interfaceDevice
                    ->setSourceNetwork("default")
                    ->setMacAddress("52:54:00:00:00:01")
                    ->setModel("virtio")
                    ->applyNWFilter("clean-traffic", function (Domain\Device\InterfaceDevice\NWFilter $NWFilter) {
                        $NWFilter->addParameter("IP", "192.168.122.2");
                    });

                $interfaceDevice->bandwidth()
                    ->setInboundAverage(10240)
                    ->setInboundBurst(20480)
                    ->setInboundPeak(20481)
                    ->setOutboundAverage(10241)
                    ->setOutboundBurst(20482)
                    ->setOutboundPeak(20483);
            })
            ->useAbsoluteMousePointer()
            ->addVNCGraphic(function (Domain\Device\Graphic\VNCGraphic $VNCGraphic) {
                $VNCGraphic
                    ->setPassword("12345678")
                    ->useAutoPort()
                    ->setListenAddress("0.0.0.0");
            })
            ->addQEMUGuestAgentChannel()
            ->disableMemoryBalloon();

        $domainXML->blkiotune()->weight()->setValue(1000);
        $domainXML->blkiotune()->addDevice("/dev/sda", 1000, function (Domain\BlockIOTune\Device $device) {
            $device->read_bytes_sec()->setValue(10240);
            $device->write_bytes_sec()->setValue(10240);
        });

        $formattedXML = $domainXML->getFormattedXML();

        print $formattedXML;

        $connection = new Connection("test:///default");
        $xml = $domainXML->getXML();
        $domainSimpleXMLElement = new \SimpleXMLElement($xml);


        // Check XML content
        $this->assertEquals($domainSimpleXMLElement["type"]->__toString(), "test");
        $this->assertEquals($domainSimpleXMLElement->name[0]->__toString(), "Test");
        $this->assertEquals($domainSimpleXMLElement->memory[0]["unit"]->__toString(), "MiB");
        $this->assertEquals($domainSimpleXMLElement->memory[0]->__toString(), "1024");

        $this->assertEquals($domainSimpleXMLElement->vcpu[0]->__toString(), "8");
        $this->assertEquals($domainSimpleXMLElement->vcpu[0]["placement"]->__toString(), "static");
        $this->assertEquals($domainSimpleXMLElement->cpu[0]["mode"]->__toString(), "host-passthrough");
        $this->assertEquals($domainSimpleXMLElement->cpu[0]->topology[0]["sockets"]->__toString(), "4");
        $this->assertEquals($domainSimpleXMLElement->cpu[0]->topology[0]["cores"]->__toString(), "1");
        $this->assertEquals($domainSimpleXMLElement->cpu[0]->topology[0]["threads"]->__toString(), "2");

        $this->assertEquals($domainSimpleXMLElement->clock[0]["offset"]->__toString(), "utc");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[0]["name"]->__toString(), "rtc");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[0]["tickpolicy"]->__toString(), "catchup");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[1]["name"]->__toString(), "pit");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[1]["tickpolicy"]->__toString(), "delay");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[2]["name"]->__toString(), "hpet");
        $this->assertEquals($domainSimpleXMLElement->clock[0]->timer[2]["present"]->__toString(), "no");

        $this->assertEquals($domainSimpleXMLElement->pm[0]->{"suspend-to-mem"}[0]["enable"]->__toString(), "yes");
        $this->assertNotNull($domainSimpleXMLElement->features[0]->acpi[0]);
        $this->assertNotNull($domainSimpleXMLElement->features[0]->apic[0]);
        $this->assertNull($domainSimpleXMLElement->features[0]->nonexists[0]);

        $this->assertEquals($domainSimpleXMLElement->os[0]->type[0]["arch"]->__toString(), "i686");
        $this->assertEquals($domainSimpleXMLElement->os[0]->type[0]->__toString(), "hvm");
        $this->assertEquals($domainSimpleXMLElement->os[0]->bootmenu[0]["enable"]->__toString(), "yes");
        $this->assertEquals($domainSimpleXMLElement->os[0]->bootmenu[0]["timeout"]->__toString(), "1000");
        $this->assertEquals($domainSimpleXMLElement->os[0]->boot[0]["dev"]->__toString(), "hd");
        $this->assertEquals($domainSimpleXMLElement->os[0]->boot[1]["dev"]->__toString(), "cdrom");

        $this->assertEquals($domainSimpleXMLElement->uuid[0]->__toString(), $uuid);

        $this->assertEquals($domainSimpleXMLElement->on_poweroff[0]->__toString(), "destroy");
        $this->assertEquals($domainSimpleXMLElement->on_reboot[0]->__toString(), "restart");
        $this->assertEquals($domainSimpleXMLElement->on_crash[0]->__toString(), "restart");

        $this->assertEquals($domainSimpleXMLElement->devices->emulator[0]->__toString(), "/usr/bin/qemu-system-x86_64");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]["type"]->__toString(), "volume");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]["device"]->__toString(), "disk");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]->driver[0]["type"]->__toString(), "qcow2");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]->source[0]["pool"]->__toString(), "testPool1");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]->source[0]["volume"]->__toString(), "testVolume1");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]->target[0]["bus"]->__toString(), "virtio");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[0]->target[0]["dev"]->__toString(), "vda");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]["type"]->__toString(), "volume");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]["device"]->__toString(), "disk");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->driver[0]["type"]->__toString(), "qcow2");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->source[0]["pool"]->__toString(), "testPool2");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->source[0]["volume"]->__toString(), "testVolume2");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->target[0]["bus"]->__toString(), "virtio");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->target[0]["dev"]->__toString(), "vdb");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[1]->iotune[0]->total_bytes_sec->__toString(), "102400");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]["type"]->__toString(), "file");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]["device"]->__toString(), "cdrom");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]->driver[0]["type"]->__toString(), "raw");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]->source[0]["file"]->__toString(), "/iso/iso.iso");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]->target[0]["bus"]->__toString(), "ide");
        $this->assertEquals($domainSimpleXMLElement->devices->disk[2]->target[0]["dev"]->__toString(), "hda");

        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]["type"]->__toString(), "network");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->source[0]["network"]->__toString(), "default");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->mac[0]["address"]->__toString(), "52:54:00:00:00:01");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->model[0]["type"]->__toString(), "virtio");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->filterref[0]["filter"]->__toString(), "clean-traffic");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->filterref[0]->parameter[0]["name"]->__toString(), "IP");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->filterref[0]->parameter[0]["value"]->__toString(), "192.168.122.2");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->inbound[0]["average"]->__toString(), "10240");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->inbound[0]["burst"]->__toString(), "20480");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->inbound[0]["peak"]->__toString(), "20481");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->outbound[0]["average"]->__toString(), "10241");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->outbound[0]["burst"]->__toString(), "20482");
        $this->assertEquals($domainSimpleXMLElement->devices->interface[0]->bandwidth[0]->outbound[0]["peak"]->__toString(), "20483");

        $this->assertEquals($domainSimpleXMLElement->devices->input[0]["type"]->__toString(), "tablet");
        $this->assertEquals($domainSimpleXMLElement->devices->input[0]["bus"]->__toString(), "usb");

        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]["type"]->__toString(), "vnc");
        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]["passwd"]->__toString(), "12345678");
        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]["port"]->__toString(), "-1");
        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]["autoport"]->__toString(), "yes");
        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]->listen[0]["type"]->__toString(), "address");
        $this->assertEquals($domainSimpleXMLElement->devices->graphics[0]->listen[0]["address"]->__toString(), "0.0.0.0");

        $this->assertEquals($domainSimpleXMLElement->devices->channel[0]["type"]->__toString(), "unix");
        $this->assertEquals($domainSimpleXMLElement->devices->channel[0]->source["mode"]->__toString(), "bind");
        $this->assertEquals($domainSimpleXMLElement->devices->channel[0]->target["type"]->__toString(), "virtio");
        $this->assertEquals($domainSimpleXMLElement->devices->channel[0]->target["name"]->__toString(), "org.qemu.guest_agent.0");

        $this->assertEquals($domainSimpleXMLElement->devices->memballoon[0]["model"]->__toString(), "none");

        $domain = $connection->domainDefineXML($xml);

        $this->assertTrue($domain->libvirt_domain_create());

        try {
            @$domain->libvirt_domain_create();
            $this->assertFalse(true);
        } catch (LibvirtException $e) {
            $this->assertTrue(true);
        }

        $connection->domainLookupByName("Test");

        $this->assertTrue($domain->libvirt_domain_destroy());

        $domainXML = $domain->getConfigurationBuilder();

        $domainXML->devices()->addDisk("file", "disk", function (Domain\Device\Disk $disk) {
            $disk
                ->fileSource("/mnt/medias/vdc.qcow2")
                ->setDriverType("qcow2")
                ->setTargetBus("virtio")
                ->setTargetDevice("vdc");
        });
        $domainXML->devices()->getDiskByTargetDev("vdc");
        $domainXML->devices()->removeDiskByTargetDev("vda");

        $domainXML->features()->hyperv()
            ->setRelaxed(true)
            ->setVapic(true)
            ->setSpinLocks(true, 4095)
            ->setVpindex(true)
            ->setRuntime(true)
            ->setSynic(true)
            ->setStimer(true)
            ->setReset(true)
            ->setVendorId(true, "QEMU");

        // Test add timer
        $domainXML->clock()
            ->addTimer("hypervclock", function (SimpleXMLImplement $timer) {
                $timer->setAttribute("present", "yes");
            });
        $this->assertNotNull($domainXML->clock()->findChild("timer", [
            "name" => "hypervclock",
        ]));

        // Test remove timer
        $this->assertNotNull($domainXML->clock()->findChild("timer", [
            "name" => "hpet",
        ]));
        $domainXML->clock()->removeTimer("hpet");
        $this->assertNull($domainXML->clock()->findChild("timer", [
            "name" => "hpet",
        ]));

        print $domainXML->getFormattedXML();
        $xml = $domainXML->getXML();
        $domainSimpleXMLElement = new \SimpleXMLElement($xml);


        // Check new XML
        foreach ($domainSimpleXMLElement->devices->disk as $disk) {
            $this->assertNotEquals("vda", $disk->target["dev"]->__toString());
        }
        $found = false;
        foreach ($domainSimpleXMLElement->devices->disk as $disk) {
            if ($disk->target["dev"]->__toString() === "vdb") {
                $found = true;
                break;
            }
        }
        if ($found === false) {
            $this->assertTrue(false, "vdb not found");
        }
        $found = false;
        foreach ($domainSimpleXMLElement->devices->disk as $disk) {
            if ($disk->target[0]["dev"]->__toString() === "vdc" && $disk->source[0]["file"]->__toString() === "/mnt/medias/vdc.qcow2") {
                $found = true;
                break;
            }
        }
        if ($found === false) {
            $this->assertTrue(false, "vdc not found");
        }

        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->relaxed[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->vapic[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->spinlocks[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->spinlocks[0]["retries"], "4095");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->vpindex[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->runtime[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->synic[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->stimer[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->reset[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->vendor_id[0]["state"], "on");
        $this->assertEquals($domainSimpleXMLElement->features[0]->hyperv[0]->vendor_id[0]["value"], "QEMU");

        $connection->domainDefineXML($xml);

        $domain->getDiskByTargetDev("vdc");
        $domainXML = $domain->getConfigurationBuilder();

        $domainXML
            ->setMemory("3072")
            ->setCurrentMemory("2048");

        $domainXML->os()
            ->setKernel("/usr/src/linux-5.10.12/arch/x86/boot/bzImage")
            ->setInitrd("/usr/src/linux-5.10.12/initrd.img")
            ->setCMDLine("root=UUID=1dfafb06-78d8-46b6-877b-a09819b285bb ro nokaslr");

        // Test remove disk
        $domainXML->device()->removeDiskByTargetDev("vdc");
        try {
            $domainXML->device()->getDiskByTargetDev("vdc");
            $this->assertTrue(false);
        } catch (DomainException $domainException) {
            $this->assertEquals($domainException->getCode(), ErrorCode::DISK_NOT_FOUND);
        }

        // Test remove interface
        $domainXML->device()->removeInterfaceByMacAddress("52:54:00:00:00:01");
        try {
            $domainXML->device()->getInterfaceByMacAddress("52:54:00:00:00:01");
            $this->assertTrue(false);
        } catch (DomainException $domainException) {
            $this->assertEquals($domainException->getCode(), ErrorCode::INTERFACE_NOT_FOUND);
        }

        $domainXML->setQEMUCommandLineArguments([
            "-gdb",
            "tcp::1235",
        ]);

        $this->assertFalse($domainXML->devices()->getDiskByTargetDev("vdb")->hasBacking());

        $xml = $domainXML->getFormattedXML();
        print $xml;


        $domainSimpleXMLElement = new \SimpleXMLElement($xml);

        $this->assertEquals($domainSimpleXMLElement->memory[0]->__toString(), "3072");
        $this->assertEquals($domainSimpleXMLElement->memory[0]["unit"]->__toString(), "MiB");
        $this->assertEquals($domainSimpleXMLElement->currentMemory[0]->__toString(), "2048");
        $this->assertEquals($domainSimpleXMLElement->currentMemory[0]["unit"]->__toString(), "MiB");

        $this->assertEquals($domainSimpleXMLElement->os[0]->kernel[0]->__toString(), "/usr/src/linux-5.10.12/arch/x86/boot/bzImage");
        $this->assertEquals($domainSimpleXMLElement->os[0]->initrd[0]->__toString(), "/usr/src/linux-5.10.12/initrd.img");
        $this->assertEquals($domainSimpleXMLElement->os[0]->cmdline[0]->__toString(), "root=UUID=1dfafb06-78d8-46b6-877b-a09819b285bb ro nokaslr");

        $this->assertEquals($domainSimpleXMLElement->children(Domain::QEMU_NAMESPACE)->commandline[0]->children(Domain::QEMU_NAMESPACE)->arg[0]->attributes()["value"]->__toString(), "-gdb");
        $this->assertEquals($domainSimpleXMLElement->children(Domain::QEMU_NAMESPACE)->commandline[0]->children(Domain::QEMU_NAMESPACE)->arg[1]->attributes()["value"]->__toString(), "tcp::1235");


        $connection->domainDefineXML($domainXML->getXML());

        $domainXML = $domain->getConfigurationBuilder();
        $domainXML->os()
            ->removeKernel()
            ->removeInitrd()
            ->removeCMDLine();
        $domainXML->setQEMUCommandLineArguments([]);

        $vdb = $domainXML->devices()->getDiskByTargetDev("vdb");
        $this->assertFalse($vdb->backingStore()->isActive());
        $this->assertFalse($vdb->hasBacking()); // should be false event a <backingStore/> exists
        $this->assertEquals($vdb->backingStore()->getLayer(), 0);
        $vdb->backingStore()->setType("file")->setFormat("qcow2")->fileSource("/var/lib/libvirt/images/snapshot.qcow");
        $this->assertTrue($vdb->backingStore()->isActive());
        $this->assertTrue($vdb->hasBacking());
        $this->assertEquals(1, $vdb->backingStore()->getLayer());

        $this->assertFalse($vdb->backingStore()->hasBacking());
        $this->assertFalse($vdb->backingStore()->backingStore()->isActive());
        $this->assertFalse($vdb->backingStore()->hasBacking());
        $this->assertEquals(1, $vdb->backingStore()->getLayer());
        $vdb->backingStore()->backingStore()->setType("block")->setFormat("raw")->source()->setAttribute("dev", "/dev/mapper/base");
        $this->assertTrue($vdb->backingStore()->backingStore()->isActive());
        $this->assertTrue($vdb->backingStore()->hasBacking());
        $this->assertEquals(2, $vdb->backingStore()->getLayer());
        $this->assertEquals(1, $vdb->backingStore()->backingStore()->getLayer());

        $xml = $domainXML->getFormattedXML();
        $domainSimpleXMLElement = new \SimpleXMLElement($xml);
        print $xml;
        $this->assertNull($domainSimpleXMLElement->os->kernel[0]);
        $this->assertNull($domainSimpleXMLElement->os->initrd[0]);
        $this->assertNull($domainSimpleXMLElement->os->cmdline[0]);
        $this->assertEquals("file", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]["type"]->__toString());
        $this->assertEquals("qcow2", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]->format[0]["type"]->__toString());
        $this->assertEquals("/var/lib/libvirt/images/snapshot.qcow", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]->source[0]["file"]->__toString());
        $this->assertEquals("block", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]->backingStore[0]["type"]->__toString());
        $this->assertEquals("raw", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]->backingStore[0]->format[0]["type"]->__toString());
        $this->assertEquals("/dev/mapper/base", $domainSimpleXMLElement->devices->disk[0]->backingStore[0]->backingStore[0]->source[0]["dev"]->__toString());
        $this->assertNull($domainSimpleXMLElement->devices->disk[0]->backingStore[0]->backingStore[0]->backingStore[0]["type"]);
        $this->assertEquals(count($domainSimpleXMLElement->children(Domain::QEMU_NAMESPACE)->commandline[0]->children(Domain::QEMU_NAMESPACE)), 0);

        $connection->domainDefineXML($domainXML->getXML());

        $this->assertTrue($domain->libvirt_domain_undefine());
    }
}