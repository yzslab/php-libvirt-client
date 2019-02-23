# Libvirt Client & XML Builder
## Requirements
You need to patch libvirt-php(8626cb017de0a564fa95f5db0c71981a20319540) extension, or use my forked libvirt-php(https://github.com/yzsme/libvirt-php.git) if you need these functions:
```
resource libvirt_domain_snapshot_create_xml(resource $domain, string $xml, int $flags = 0)

/*
enum virDomainUndefineFlagsValues {
    VIR_DOMAIN_UNDEFINE_MANAGED_SAVE	=	1 (0x1; 1 << 0)	
    Also remove any managed save
    
    VIR_DOMAIN_UNDEFINE_SNAPSHOTS_METADATA	=	2 (0x2; 1 << 1)	
    If last use of domain, then also remove any snapshot metadata
    
    VIR_DOMAIN_UNDEFINE_NVRAM	=	4 (0x4; 1 << 2)	
    Also remove any nvram file
    
    VIR_DOMAIN_UNDEFINE_KEEP_NVRAM	=	8 (0x8; 1 << 3)	
    Keep nvram file Future undefine control flags should come here.
}
*/
bool libvirt_domain_undefine_flags(resource $domain, int $flags = 0)
```

### Example steps
#### Use patch:
```
git clone git://libvirt.org/libvirt-php.git
cd libvirt-php
git checkout 8626cb017de0a564fa95f5db0c71981a20319540
patch -p1 < DIRECTORY_TO_PHP_LIBVIRT_CLIENT/libvirt-php-patches/0001-Add-function-libvirt_domain_undefine_flags-and-libvi.patch
./autogen.sh
make -j8
make -j8 install
```
#### Use my forked and patched repository
```
git clone https://github.com/yzsme/libvirt-php.git
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
