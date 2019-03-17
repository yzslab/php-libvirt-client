<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 15:18
 */

namespace YunInternet\Libvirt;

use YunInternet\Libvirt\Constants\Host\VirConnectCredentialType;
use YunInternet\Libvirt\Exception\CertificateNotTrustedException;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\Exception\LibvirtException as Exception;

/**
 * Class LibvirtConnection
 * @method array libvirt_node_get_info() @throws \Exception
 * @method string[] libvirt_list_domains
 * @method resource[] libvirt_list_domain_resources
 * @method resource libvirt_domain_new($name, $arch, $memMB, $maxmemMB, $vcpus, $iso_image, $disks, $networks, $flags)
 * @method resource libvirt_domain_lookup_by_name(string $name)
 * @method resource libvirt_domain_lookup_by_uuid(string $uuid)
 * @method resource libvirt_domain_define_xml(string $xml)
 * @method string libvirt_connect_get_capabilities($xpath = null)
 * @method resource libvirt_storagepool_define_xml(string $xml)
 * @method resource libvirt_storagepool_lookup_by_name(string $name)
 * @method resource|false libvirt_nwfilter_define_xml(string $xml) Function is used to define a new nwfilter based on the XML description
 * @method resource|false libvirt_nwfilter_lookup_by_name(string $name)
 * @method resource|false libvirt_nwfilter_lookup_by_uuid_string(string $uuid)
 * @method resource|false libvirt_nwfilter_lookup_by_uuid(string $binaryUUID)
 * @method resource[] libvirt_list_all_nwfilters()
 * @method string[] libvirt_list_nwfilters()
 * @method resource libvirt_network_define_xml(string $xml)
 * @method resource libvirt_network_get(string $name)
 */
class Connection extends Libvirt
{
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_node_get_info" => true,
        "libvirt_node_get_cpu_stats" => true,
        "libvirt_node_get_cpu_stats_for_each_cpu" => true,
        "libvirt_node_get_mem_stats" => true,
        "libvirt_connect_get_machine_types" => true,
        "libvirt_connect_get_information" => true,
        "libvirt_connect_get_uri" => true,
        "libvirt_connect_get_hostname" => true,
        "libvirt_image_create" => true,
        "libvirt_image_remove" => true,
        "libvirt_connect_get_hypervisor" => true,
        "libvirt_connect_is_encrypted" => true,
        "libvirt_connect_is_secure" => true,
        "libvirt_connect_get_all_domain_stats" => true,
        "libvirt_connect_get_maxvcpus" => true,
        "libvirt_connect_get_sysinfo" => true,
        "libvirt_domain_get_counts" => true,
        "libvirt_domain_lookup_by_id" => true,
        "libvirt_connect_get_capabilities" => true,
        "libvirt_connect_get_emulator" => true,
        "libvirt_connect_get_nic_models" => true,
        "libvirt_connect_get_soundhw_models" => true,
        "libvirt_domain_new" => true,
        "libvirt_domain_lookup_by_name" => true,
        "libvirt_domain_lookup_by_uuid" => true,
        "libvirt_domain_define_xml" => true,
        "libvirt_domain_create_xml" => true,
        "libvirt_list_domains" => true,
        "libvirt_list_domain_resources" => true,

        "libvirt_network_define_xml" => true,
        "libvirt_network_get" => true,

        "libvirt_storagepool_define_xml" => true,
        "libvirt_storagepool_lookup_by_name" => true,

        "libvirt_nwfilter_define_xml" => true,
        "libvirt_nwfilter_lookup_by_name" => true,
        "libvirt_nwfilter_lookup_by_uuid_string" => true,
        "libvirt_nwfilter_lookup_by_uuid" => true,
        "libvirt_list_all_nwfilters" => true,
        "libvirt_list_nwfilters" => true,
    ];

    private $uri;

    private $readonly;

    private $credentials;

    private $libvirtResource;

    /**
     * Connection constructor.
     * @param $uri
     * @param bool $readonly
     * @param null $credentials
     * @throws CertificateNotTrustedException
     * @throws Exception
     */
    public function __construct($uri, $readonly = false, $credentials = null)
    {
        $this->uri = $uri;
        $this->readonly = $readonly;
        $this->credentials = $credentials;


        try {
            $this->libvirtResource = $this->createConnection();
            if (!$this->libvirtResource)
                Libvirt::errorHandler();
        } catch (\ErrorException $e) {
            Libvirt::errorHandler($e->getMessage());
        }
    }

    /**
     * @return Domain[]
     */
    public function listDomains()
    {
        $domains = [];

        foreach ($this->libvirt_list_domain_resources() as $domainResource) {
            $domains[] = new Domain($domainResource, $this);
        }

        return $domains;
    }

    public function close()
    {
    }

    public function domainDefineXML($xml)
    {
        $domain = $this->libvirt_domain_define_xml($xml);
        return new Domain($domain, $this);
    }

    public function domainLookupByName($name)
    {
        $domainResource = $this->libvirt_domain_lookup_by_name($name);
        return new Domain($domainResource, $this);
    }

    public function domainLookupByUUID($uuid)
    {
        $domainResource = $this->libvirt_domain_lookup_by_uuid($uuid);
        return new Domain($domainResource, $this);
    }

    public function storagePoolDefineXML($xml)
    {
        $storagePool = $this->libvirt_storagepool_define_xml($xml);
        return new StoragePool($storagePool);
    }

    public function storagePoolLookupByName($name)
    {
        $storagePoolResource = $this->libvirt_storagepool_lookup_by_name($name);
        return new StoragePool($storagePoolResource);
    }

    public function nwFilterDefineXML($xml)
    {
        return new NWFilter($this->libvirt_nwfilter_define_xml($xml));
    }

    public function nwFilterLookupByName($name)
    {
        return new NWFilter($this->libvirt_nwfilter_lookup_by_name($name));
    }

    public function nwFilterLookupByUUID($uuid)
    {
        return NEW NWFilter($this->libvirt_nwfilter_lookup_by_uuid_string($uuid));
    }

    /**
     * @return NWFilter[]
     */
    public function listAllNWFilters()
    {
        $nwFilters = [];
        foreach ($this->libvirt_list_all_nwfilters() as $nwFilter)
            $nwFilters[] = new NWFilter($nwFilter);
        return $nwFilters;
    }

    public function networkDefineXML($xml)
    {
        return new Network($this->libvirt_network_define_xml($xml));
    }

    public function networkGet($name)
    {
        return new Network($this->libvirt_network_get($name));
    }

    protected function getResources($functionName)
    {
        return [$this->libvirtResource];
    }

    protected function createConnection()
    {
        if (is_array($this->credentials))
            return \libvirt_connect($this->uri, $this->readonly, $this->credentials);
        return \libvirt_connect($this->uri, $this->readonly);
    }
}