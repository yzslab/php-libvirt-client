<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-28
 * Time: 下午9:54
 */

namespace YunInternet\Libvirt\Constants\Domain;


interface VirDomainRebootFlags
{
    const VIR_DOMAIN_REBOOT_DEFAULT = VIR_DOMAIN_REBOOT_DEFAULT; // (0x0) hypervisor choice
    const VIR_DOMAIN_REBOOT_ACPI_POWER_BTN = VIR_DOMAIN_REBOOT_ACPI_POWER_BTN; // (0x1; 1 << 0)Send ACPI event
    const VIR_DOMAIN_REBOOT_GUEST_AGENT = VIR_DOMAIN_REBOOT_GUEST_AGENT; // (0x2; 1 << 1)Use guest agent
    const VIR_DOMAIN_REBOOT_INITCTL = VIR_DOMAIN_REBOOT_INITCTL; // (0x4; 1 << 2)Use initctl
    const VIR_DOMAIN_REBOOT_SIGNAL = VIR_DOMAIN_REBOOT_SIGNAL; // (0x8; 1 << 3)Send a signal
    const VIR_DOMAIN_REBOOT_PARAVIRT = VIR_DOMAIN_REBOOT_PARAVIRT; // (0x10; 1 << 4)Use paravirt guest control
}