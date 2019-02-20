<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-20
 * Time: 下午4:57
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;

class TestPatch0001 extends TestCase
{
    public function testConstants()
    {
        $this->assertEquals(VIR_DOMAIN_UNDEFINE_MANAGED_SAVE, 1);
        $this->assertEquals(VIR_DOMAIN_UNDEFINE_SNAPSHOTS_METADATA, 2);
        $this->assertEquals(VIR_DOMAIN_UNDEFINE_NVRAM, 4);
        $this->assertEquals(VIR_DOMAIN_UNDEFINE_KEEP_NVRAM, 8);
    }

    public function testSnapshotAndUndefineFlags()
    {
        $libvirtResource = libvirt_connect("test:///default", false);
        $this->assertTrue(is_resource($libvirtResource));

        $domainResource = libvirt_domain_define_xml($libvirtResource, $this->getDomainXML());
        $this->assertTrue(is_resource($domainResource));

        // Create a snapshot named snapshot1
        $snapshotResource = libvirt_domain_snapshot_create_xml($domainResource, <<<EOF
<domainsnapshot>
  <name>snapshot1</name>
</domainsnapshot>
EOF
, VIR_SNAPSHOT_CREATE_LIVE | VIR_SNAPSHOT_CREATE_ATOMIC);
        $this->assertTrue(is_resource($snapshotResource));

        // Domain with snapshot can not be undefined directly
        $this->assertFalse(@libvirt_domain_undefine($domainResource));

        // Use VIR_DOMAIN_UNDEFINE_SNAPSHOTS_METADATA flag to undefine the domain
        $this->assertTrue(libvirt_domain_undefine_flags($domainResource, VIR_DOMAIN_UNDEFINE_SNAPSHOTS_METADATA));
    }

    private function getDomainXML()
    {
        return <<<EOF
<domain type="test">
  <name>Test</name>
  <memory unit="MiB">1024</memory>
  <vcpu placement="static">8</vcpu>
  <cpu mode="host-passthrough">
    <topology sockets="4" cores="1" threads="2"/>
  </cpu>
  <os>
    <type arch="i686">hvm</type>
    <loader readonly='yes' type='pflash'>/usr/share/ovmf/OVMF.fd</loader>
    <nvram template='/usr/share/OVMF/OVMF_VARS.fd'>/var/lib/libvirt/qemu/nvram/guest_VARS.fd</nvram>
    <bootmenu enable="yes" timeout="1000"/>
    <boot dev="hd"/>
    <boot dev="cdrom"/>
  </os>
  <pm>
    <suspend-to-mem enable="yes"/>
  </pm>
  <devices>
    <memballoon model="none"/>
    <disk type="volume" device="disk">
      <driver name="qemu" type="qcow2"/>
      <source pool="testPool1" volume="testVolume1"/>
      <target bus="virtio" dev="vda"/>
    </disk>
    <disk type="volume" device="disk">
      <driver name="qemu" type="qcow2"/>
      <source pool="testPool2" volume="testVolume2"/>
      <target bus="virtio" dev="vdb"/>
      <iotune>
        <total_bytes_sec>102400</total_bytes_sec>
      </iotune>
    </disk>
    <disk type="file" device="cdrom">
      <driver name="qemu" type="raw"/>
      <source file="/iso/iso.iso"/>
      <target bus="ide" dev="hda"/>
    </disk>
    <interface type="network">
      <source network="default"/>
      <mac address="52:54:00:00:00:01"/>
      <model type="virtio"/>
      <filterref filter="clean-traffic">
        <parameter name="IP" value="192.168.122.2"/>
      </filterref>
      <bandwidth>
        <inbound average="10240" burst="20480" peak="20480"/>
        <outbound average="10240" burst="20480" peak="20480"/>
      </bandwidth>
    </interface>
    <input type="tablet" bus="usb"/>
    <graphics type="vnc" passwd="12345678" port="-1" autoport="yes">
      <listen type="address" address="0.0.0.0"/>
    </graphics>
    <channel>
      <source mode="bind"/>
      <target type="virtio" name="org.qemu.guest_agent.0"/>
    </channel>
  </devices>
  <clock offset="utc"/>
  <features>
    <pae/>
    <acpi/>
    <apic/>
    <hyperv>
      <relaxed state="on"/>
      <vapic state="on"/>
      <spinlocks state="on" retries="8191"/>
    </hyperv>
  </features>
  <on_poweroff>destroy</on_poweroff>
  <on_reboot>restart</on_reboot>
  <on_crash>restart</on_crash>
  <blkiotune>
    <weight>1000</weight>
    <device>
      <path>/dev/sda</path>
      <weight>1000</weight>
      <read_bytes_sec>10240</read_bytes_sec>
      <write_bytes_sec>10240</write_bytes_sec>
    </device>
  </blkiotune>
</domain>
EOF
            ;
    }
}