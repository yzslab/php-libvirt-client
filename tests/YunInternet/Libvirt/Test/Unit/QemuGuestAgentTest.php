<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-22
 * Time: 下午8:27
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\GuestAgent;

abstract class QemuGuestAgentTest extends TestCase
{
    public function testSync()
    {
        $this->assertTrue($this->getQGA()->sync());
    }

    public function testPing()
    {
        var_dump($this->getQGA()->ping());
        $this->assertTrue(true);
    }

    public function testGetNetworkInterfaces()
    {
        $networkInterfaces = $this->getQGA()->getNetworkInterfaces();

        foreach ($networkInterfaces["return"] as $networkInterface) {
            if (array_key_exists("hardware-address", $networkInterface)) {
                var_dump($networkInterface["hardware-address"]);
            }
        }
        $this->assertTrue(true);
    }

    public function testInfo()
    {
        print_r($this->getQGA()->getInfo()["return"]);
        $this->assertTrue(true);
    }

    public function testOSInfo()
    {
        print_r($this->getQGA()->getOsInfo());
        $this->assertTrue(true);
    }

    public function testFileWrite()
    {
        $response = $this->getQGA()->fileOpen("C:/qga.test", "w");
        $handle = $response["return"];
        try {
            $this->getQGA()->fileWrite($handle, "TestContent");
            $this->assertTrue(true);
        } finally {
            $this->getQGA()->fileClose($handle);
        }
    }

    public function testFileRead()
    {
        $response = $this->getQGA()->fileOpen("C:/qga.test", "r");
        $handle = $response["return"];
        try {
            while (!$this->getQGA()->fileRead($handle, 1024, $content, $count)) {
                print $content;
            }
            $this->assertTrue(true);
        } finally {
            $this->getQGA()->fileClose($handle);
        }
    }

    public function testGuestExec()
    {
        $return = $this->getQGA()->exec("ping", ["--help"], true)["return"];
        sleep(1);
        var_dump(base64_decode($this->getQGA()->execStatus($return["pid"])["return"]["out-data"]));

        $this->assertTrue(true);
    }

    abstract protected function getQGA() : GuestAgent;
}