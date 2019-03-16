<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-16
 * Time: 下午4:17
 */

namespace YunInternet\Libvirt;

/**
 * Class NWFilter
 * @method boolean libvirt_nwfilter_undefine() Function is used to undefine already defined nwfilter
 * @method string libvirt_nwfilter_get_xml_desc($xpath = null) Function is used to get the XML description for the nwfilter
 * @method string|false libvirt_nwfilter_get_uuid_string() Function is used to get nwfilter's UUID in string format
 * @method string|false libvirt_nwfilter_get_uuid() Function is used to get nwfilter's UUID in binary format
 * @method string|false libvirt_nwfilter_get_name()
 * @package YunInternet\Libvirt
 */
class NWFilter extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_nwfilter_undefine" => true,
        "libvirt_nwfilter_get_xml_desc" => true,
        "libvirt_nwfilter_get_uuid_string" => true,
        "libvirt_nwfilter_get_uuid" => true,
        "libvirt_nwfilter_get_name" => true,
    ];

    private $nwFilterResource;

    public function __construct($nwFilterResource)
    {
        $this->nwFilterResource = $nwFilterResource;
    }

    protected function getResources($functionName)
    {
        return [$this->nwFilterResource];
    }
}