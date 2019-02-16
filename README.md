# Libvirt Client & XML Builder
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