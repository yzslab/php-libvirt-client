<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 18:32
 */

namespace YunInternet\Libvirt;

use YunInternet\Libvirt\Constants\Domain\VirDomainXMLFlags;
use YunInternet\Libvirt\Exception\DomainException;
use YunInternet\Libvirt\Exception\ErrorCode;

/**
 * Class Domain
 * @method bool libvirt_domain_create()
 * @method bool libvirt_domain_reboot($flags = 0)
 * @method bool libvirt_domain_reset($flags = 0)
 * @method bool libvirt_domain_destroy()
 * @method string libvirt_domain_get_xml_desc($xpath, int $flags = 0)
 * @method array libvirt_domain_get_disk_devices()
 * @method array libvirt_domain_get_block_info(string $dev)
 * @method int libvirt_domain_is_active() Return 1 on domain active, otherwise 0
 * @method bool libvirt_domain_undefine()
 * @method bool libvirt_domain_undefine_flags($flags = 0)
 * @method bool libvirt_domain_update_device(string $xml, int $flags = 0) $flags [int]:	Flags to update the device (VIR_DOMAIN_DEVICE_MODIFY_CURRENT, VIR_DOMAIN_DEVICE_MODIFY_LIVE, VIR_DOMAIN_DEVICE_MODIFY_CONFIG, VIR_DOMAIN_DEVICE_MODIFY_FORCE)
 * @method string|false libvirt_domain_qemu_agent_command(string $command, int $timeout = -1, int $flags = 0) $timeout for waiting (-2 block, -1 default, 0 no wait, >0 wait specific time
 * @method bool libvirt_domain_attach_device(string $xml, int $flags = VIR_DOMAIN_AFFECT_LIVE)
 * @method bool libvirt_domain_detach_device(string $xml, int $flags = VIR_DOMAIN_AFFECT_LIVE)
 * @method array|false libvirt_domain_get_network_info()
 * @method array|false libvirt_domain_block_stats(string $device)
 * @method array|false libvirt_domain_get_cpu_total_stats()
 * @package YunInternet\Libvirt
 */
class Domain extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_domain_is_persistent" => true,
        "libvirt_domain_set_max_memory" => true,
        "libvirt_domain_set_memory" => true,
        "libvirt_domain_set_memory_flags" => true,
        "libvirt_domain_get_autostart" => true,
        "libvirt_domain_set_autostart" => true,
        "libvirt_domain_get_metadata" => true,
        "libvirt_domain_set_metadata" => true,
        "libvirt_domain_is_active" => true,
        "libvirt_domain_lookup_by_name" => true,
        "libvirt_domain_lookup_by_uuid" => true,
        "libvirt_domain_qemu_agent_command" => true,
        "libvirt_domain_lookup_by_uuid_string" => true,
        "libvirt_domain_get_name" => true,
        "libvirt_domain_get_uuid_string" => true,
        "libvirt_domain_get_screenshot_api" => true,
        "libvirt_domain_get_screenshot" => true,
        "libvirt_domain_get_screen_dimensions" => true,
        "libvirt_domain_send_keys" => true,
        "libvirt_domain_send_pointer_event" => true,
        "libvirt_domain_get_uuid" => true,
        "libvirt_domain_get_id" => true,
        "libvirt_domain_get_next_dev_ids" => true,
        "libvirt_domain_get_xml_desc" => true,
        "libvirt_domain_get_disk_devices" => true,
        "libvirt_domain_get_interface_devices" => true,
        "libvirt_domain_change_vcpus" => true,
        "libvirt_domain_change_memory" => true,
        "libvirt_domain_change_boot_devices" => true,
        "libvirt_domain_disk_add" => true,
        "libvirt_domain_disk_remove" => true,
        "libvirt_domain_nic_add" => true,
        "libvirt_domain_nic_remove" => true,
        "libvirt_domain_get_info" => true,
        "libvirt_domain_create" => true,
        "libvirt_domain_destroy" => true,
        "libvirt_domain_resume" => true,
        "libvirt_domain_core_dump" => true,
        "libvirt_domain_shutdown" => true,
        "libvirt_domain_managedsave" => true,
        "libvirt_domain_suspend" => true,
        "libvirt_domain_undefine" => true,
        "libvirt_domain_undefine_flags" => true,
        "libvirt_domain_reboot" => true,
        "libvirt_domain_reset" => true,
        "libvirt_domain_memory_peek" => true,
        "libvirt_domain_memory_stats" => true,
        "libvirt_domain_update_device" => true,
        "libvirt_domain_block_stats" => true,
        "libvirt_domain_block_resize" => true,
        "libvirt_domain_block_commit" => true,
        "libvirt_domain_block_job_abort" => true,
        "libvirt_domain_block_job_set_speed" => true,
        "libvirt_domain_get_network_info" => true,
        "libvirt_domain_get_block_info" => true,
        "libvirt_domain_xml_xpath" => true,
        "libvirt_domain_interface_stats" => true,
        "libvirt_domain_get_connect" => true,
        "libvirt_domain_migrate_to_uri" => true,
        "libvirt_domain_migrate_to_uri2" => true,
        "libvirt_domain_migrate" => true,
        "libvirt_domain_get_job_info" => true,
        "libvirt_domain_has_current_snapshot" => true,
        "libvirt_domain_snapshot_lookup_by_name" => true,
        "libvirt_domain_snapshot_create" => true,
        "libvirt_domain_snapshot_create_xml" => true,
        "libvirt_domain_snapshot_get_xml" => true,
        "libvirt_domain_snapshot_revert" => true,
        "libvirt_domain_snapshot_delete" => true,
        "libvirt_domain_attach_device" => true,
        "libvirt_domain_detach_device" => true,
        "libvirt_domain_get_cpu_total_stats" => true,
    ];

    private $domainResource;

    private $connection;

    private $guestAgent;

    public function __construct($domainResource, Connection $connection)
    {
        $this->domainResource = $domainResource;

        $this->connection = $connection;
    }

    /**
     * @throws DomainException
     */
    public function vncDisplay()
    {
        if (!$this->libvirt_domain_is_active())
            throw new DomainException("domain is not running", ErrorCode::DOMAIN_IS_NOT_RUNNING);
        $vncGraphic = $this->findVNCGraphical();
        $port = intval(@$vncGraphic["port"]);
        if ($port <= 0)
            throw new DomainException("vnc port not found", ErrorCode::VNC_DISPLAY_PORT_NOT_FOUND);
        return $port;
    }

    /**
     * @param $password
     * @return bool
     * @throws DomainException
     */
    public function setVNCPassword($password)
    {
        $vncGraphic = $this->findVNCGraphical();

        // Set password
        $vncGraphic["passwd"] = $password;

        return $this->libvirt_domain_update_device($vncGraphic->asXML(), $this->returnLiveTagOnInstanceRunning() | VIR_DOMAIN_DEVICE_MODIFY_CONFIG);
    }


    /**
     * @return GuestAgent
     */
    public function getGuestAgent() : GuestAgent
    {
        if (is_null($this->guestAgent))
            $this->guestAgent = new GuestAgent($this);
        return $this->guestAgent;
    }


    /**
     * @param null $xpath
     * @param $flags
     * @return \SimpleXMLElement
     */
    public function domainSimpleXMLElement($xpath = null, $flags = VirDomainXMLFlags::VIR_DOMAIN_XML_SECURE)
    {
        return new \SimpleXMLElement($this->libvirt_domain_get_xml_desc($xpath, $flags));
    }

    /**
     * @return int
     */
    public function returnLiveTagOnInstanceRunning()
    {
        if ($this->libvirt_domain_is_active())
            return VIR_DOMAIN_DEVICE_MODIFY_LIVE;
        return 0;
    }

    /**
     * @param int $flags
     * @return int
     */
    public function enableLiveTagOnInstanceRunning($flags = 0)
    {
        if ($this->libvirt_domain_is_active())
            return VIR_DOMAIN_DEVICE_MODIFY_LIVE | $flags;
        return $flags;
    }

    /**
     * @return mixed
     */
    public function getDomainResource()
    {
        return $this->domainResource;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    protected function getResources($functionName)
    {
        return [$this->domainResource];
    }

    /**
     * @return \SimpleXMLElement|null
     * @throws DomainException
     */
    private function findVNCGraphical()
    {
        $vncGraphic = null;
        // Find graphic which type is vnc
        foreach ($this->domainSimpleXMLElement()->devices->graphics as $vncGraphic) {
            if ($vncGraphic["type"] === "vnc")
                break;
        }

        // Throw exception on VNC graphic not found
        if (is_null($vncGraphic))
            throw new DomainException("VNC graphic not found", ErrorCode::VNC_GRAPHIC_NOT_FOUND);

        return $vncGraphic;
    }
}