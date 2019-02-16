<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 20:46
 */

namespace YunInternet\Libvirt\Constants\Domain;


interface VirDomainXMLFlags
{
    const VIR_DOMAIN_XML_SECURE = VIR_DOMAIN_XML_SECURE; // dump security sensitive information too
    const VIR_DOMAIN_XML_INACTIVE = VIR_DOMAIN_XML_INACTIVE; // dump inactive domain information
    const VIR_DOMAIN_XML_UPDATE_CPU = VIR_DOMAIN_XML_UPDATE_CPU; // update guest CPU requirements according to host CPU
    const VIR_DOMAIN_XML_MIGRATABLE = VIR_DOMAIN_XML_MIGRATABLE; // dump XML suitable for migration
}