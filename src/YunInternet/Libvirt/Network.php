<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-17
 * Time: 下午2:27
 */

namespace YunInternet\Libvirt;

/**
 * Class Network
 * @method bool libvirt_network_undefine()
 * @method string libvirt_network_get_bridge()
 * @method string libvirt_network_get_active()
 * @method array libvirt_network_get_information()
 * @method bool libvirt_network_set_active(int $flags)
 * @method string|false libvirt_network_get_xml_desc($xpath = null)
 * @method bool libvirt_network_set_autostart(int $flags)
 * @method array libvirt_network_get_dhcp_leases($mac = null, $flags = 0)
 * @package YunInternet\Libvirt
 */
class Network extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_network_undefine" => true,
        "libvirt_network_get_bridge" => true,
        "libvirt_network_get_active" => true,
        "libvirt_network_get_information" => true,
        "libvirt_network_set_active" => true,
        "libvirt_network_get_xml_desc" => true,
        "libvirt_network_set_autostart" => true,
        "libvirt_network_get_dhcp_leases" => true,
    ];

    private $networkResource;

    public function __construct($networkResource)
    {
        $this->networkResource = $networkResource;
    }

    protected function getResources($functionName)
    {
        return [$this->networkResource];
    }
}