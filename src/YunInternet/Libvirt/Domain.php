<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 18:32
 */

namespace YunInternet\Libvirt;

/**
 * Class Domain
 * @method bool libvirt_domain_create()
 * @method bool libvirt_domain_destroy()
 * @method string libvirt_domain_get_xml_desc($xpath, int $flags = 0)
 * @method array libvirt_domain_get_disk_devices()
 * @method array libvirt_domain_get_block_info(string $dev)
 * @method bool libvirt_domain_undefine($flags = 0)
 * @method bool libvirt_domain_update_device(string $xml, int $flags = 0) $flags [int]:	Flags to update the device (VIR_DOMAIN_DEVICE_MODIFY_CURRENT, VIR_DOMAIN_DEVICE_MODIFY_LIVE, VIR_DOMAIN_DEVICE_MODIFY_CONFIG, VIR_DOMAIN_DEVICE_MODIFY_FORCE)
 * @method string|false libvirt_domain_qemu_agent_command(string $command, int $timeout = -1, int $flags = 0) $timeout for waiting (-2 block, -1 default, 0 no wait, >0 wait specific time
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
        "libvirt_domain_reboot" => true,
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
        "libvirt_domain_snapshot_get_xml" => true,
        "libvirt_domain_snapshot_revert" => true,
        "libvirt_domain_snapshot_delete" => true,
    ];

    private $domainResource;

    private $connection;

    public function __construct($domainResource, Connection $connection)
    {
        $this->domainResource = $domainResource;

        $this->connection = $connection;
    }

    protected function getResources($functionName)
    {
        return [$this->domainResource];
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
}