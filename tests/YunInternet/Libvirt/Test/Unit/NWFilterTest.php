<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-16
 * Time: 下午4:29
 */

namespace YunInternet\Libvirt\Test\Unit;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Exception\LibvirtException;
use YunInternet\Libvirt\NWFilter;
use YunInternet\Libvirt\Test\LibvirtDefaultConnection;
use YunInternet\Libvirt\Test\LibvirtTestConnection;

class NWFilterTest extends TestCase
{
    use LibvirtDefaultConnection;

    public function testListAllNWFilters()
    {
        foreach ($this->connection()->listAllNWFilters() as $NWFilter) {
            var_dump($NWFilter->libvirt_nwfilter_get_name());
            var_dump($NWFilter->libvirt_nwfilter_get_xml_desc());
        }
        $this->assertTrue(true);
    }

    public function testNWFilterLookup()
    {
        try {
            @$this->connection()->nwFilterLookupByName("not-exists");
        } catch (LibvirtException $e) {
            $this->assertTrue(true);
        }

        $nwFilterName = "clean-traffic";
        $this->assertEquals($nwFilterName, $this->connection()->nwFilterLookupByName($nwFilterName)->libvirt_nwfilter_get_name());
    }
}