# Libvirt Client & XML Builder
## Requirements
You need use my patched libvirt-php(https://github.com/yzslab/libvirt-php.git) if you need these functions:
```
resource libvirt_domain_snapshot_create_xml(resource $domain, string $xml, int $flags = 0)
bool libvirt_domain_undefine_flags(resource $domain, int $flags = 0)
bool libvirt_domain_reset(resource $domain, int $flags = 0)

int libvirt_get_last_error_code()
int libvirt_get_last_error_domain()

array libvirt_network_get_dhcp_leases(string $mac = null, int $flags = 0)
array libvirt_domain_get_cpu_total_stats()
```

### Patched libvirt-php installation steps
```
git clone https://github.com/yzslab/libvirt-php.git
cd libvirt-php
./autogen.sh
make -j8
make -j8 install
```
## Install
```
composer require yuninternet/php-libvirt-client
```
## Usage
```
<?php
// ...
$domainXML = new YunInternet\Libvirt\Configuration\Domain("Test", "1024", "4");

$domainXML->device()
    ->addDisk("volume", "disk", function (Domain\Device\Disk $disk) {
        $disk
            ->volumeSource("testPool1", "testVolume1")
            ->setDriverType("qcow2")
            ->setTargetBus("virtio")
            ->setTargetDevice("vda")
        ;
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
    ->disableMemoryBalloon()
;

$connection = new YunInternet\Libvir\Connection("test:///default");
$domainResource = $connection->libvirt_domain_define_xml($domainXML->getXML());
// ...
```
## More example
In tests/YunInternet/Libvirt/Test：
```
phpunit
```
## Some configuration suggestions for Windows guest
```
        ...->features()->addChild("hyperv", null, function (XMLElementContract $feature) {
            $feature
                ->createChild("relaxed", null, ["state" => "on"])
                ->createChild("vapic", null, ["state" => "on"])
                ->createChild("spinlocks", null, ["state" => "on", "retries" => "8191"])
                ->createChild("vpindex", null, ["state" => "on"])
                ->createChild("runtime", null, ["state" => "on"])
                ->createChild("synic", null, ["state" => "on"])
                ->createChild("stimer", null, ["state" => "on"])
                ->createChild("reset", null, ["state" => "on"])
            ;
        });
        
        ...->clock()
            ->setOffset("localtime")
            ->addTimer("hypervclock", function (SimpleXMLImplement $timer) {
                $timer->setAttribute("present", "yes");
            })
        ;
```
