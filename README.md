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
### Creat new domain
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
            ->setPassword("12345678")
            ->useAutoPort()
            ->setListenAddress("0.0.0.0")
        ;
    })
    ->addQEMUGuestAgentChannel()
    ->disableMemoryBalloon()
;

$connection = new yuninternet\Libvirt\Connection("qemu:///system"); // qemu+tls://libvirt-hostname/system?pkipath=/your-pki-path
$domain = $connection->domainDefineXML($domainXML->getXML());
```
### Domain operations
```
$domain->libvirt_domain_create(); // Power on

var_dump($domain->vncDisplay()); // Print VNC port
$domain->setVNCPassword("87654321"); // Change VNC password

// Change media
$domain->changeMedia("hda", "/mnt/medias/ubuntu-20.04.1-live-server-amd64.iso");
$domain->changeMedia("hda", function (Domain\Device\Disk $disk) {
    $disk
        ->volumeSource("iso", "debian-10");
});
$domain->changeMedia("hda"); // Eject

// Attach disk to exists domain
$domain->attachDisk("file", "disk", function (Disk $disk) {
    $disk
        ->setDriver("qemu")
        ->setDriverType("qcow2")
        ->setCache("none")
        ->fileSource("/mnt/medias/sdb.qcow2")
        ->setTargetDevice("vdb")
        ->setTargetBus("virtio")
    ;
});
// Detach disk
$domain->detachDiskByTargetDev("vdb");

// Change network interface
$interface = $domain->getInterfaceByMacAddress("52:54:00:00:00:01");
$interface
    ->setSourceNetwork("bridge0")
    ->setModel("e1000");
$domain->libvirt_domain_update_device($interface->getXML(), VIR_DOMAIN_DEVICE_MODIFY_CONFIG);

// QEMU Guest Agent
$qga = $domain->getGuestAgent();
$qga->ping();
$networkInterfaces = $qga->getNetworkInterfaces();
foreach ($networkInterfaces["return"] as $networkInterface) {
    var_dump($networkInterface);
}
```
### Modify exists domain configuration
```
$domain = $connection->domainLookupByName("Test");
$domainXML = $domain->getConfigurationBuilder();
$domainXML->os()
    ->setMachine("q35")
    ->setLoader("/usr/share/ovmf/OVMF.fd")
    ->setLoaderReadonly(true);
$domainXML->devices()
    ->removeDiskByTargetDev("hda")
    ->removeDiskByTargetDev("vdb");
$domainXML->devices()
    ->addDisk("file", "disk", function (Domain\Device\Disk $disk) {
        $disk
            ->fileSource("/mnt/medias/sda.qcow2")
            ->setDriverType("qcow2")
            ->setTargetBus("virtio-scsi")
            ->setTargetDevice("sda");
    })
    ->addDisk("file", "cdrom", function (Domain\Device\Disk $disk) {
        $disk
            ->fileSource("/iso/iso.iso")
            ->setDriverType("raw")
            ->setTargetBus("sata")
            ->setTargetDevice("sdb")
        ;
    });
$domainXML->devices()->getDiskByTargetDev("vda")->fileSource("/mnt/medias/vda.qcow2");
$domain = $connection->domainDefineXML($domainXML->getXML());
```
## More examples
In tests/YunInternet/Libvirt/Test：
```
phpunit
```
## Some configuration suggestions for Windows guest
```
$domainXML->features()->hyperv()
    ->setRelaxed(true)
    ->setVapic(true)
    ->setSpinLocks(true, 4095)
    ->setVpindex(true)
    ->setRuntime(true)
    ->setSynic(true)
    ->setStimer(true)
    ->setReset(true);

$domainXML->clock()
    ->setOffset("localtime")
    ->addTimer("hypervclock", function (SimpleXMLImplement $timer) {
        $timer->setAttribute("present", "yes");
    });
```
