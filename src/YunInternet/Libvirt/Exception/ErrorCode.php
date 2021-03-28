<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-4
 * Time: 下午1:53
 */

namespace YunInternet\Libvirt\Exception;


interface ErrorCode
{
    const CERTIFICATE_NOT_TRUSTED = 10010;

    const UNABLE_IMPORT_CLIENT_CERTIFICATE = 10011;

    const UNABLE_SET_X509_KEY_AND_CERTIFICATE = 10012;

    const FAILED_TO_VERIFY_PEER_CERTIFICATE = 10013;

    const CERTIFICATE_HAS_NOT_GOT_A_KNOWN_ISSUER = 10015;

    const UNABLE_READ_TLS_CONFIRMATION = 10016;

    const STORAGE_POOL_NOT_FOUND = 10018;

    const STORAGE_POOL_IS_ACTIVE = 10019;

    const DOMAIN_NOT_FOUND = 10020;

    const STORAGE_VOLUME_NOT_FOUND = 10021;

    const DOMAIN_IS_ALREADY_RUNNING = 10022;

    const DOMAIN_IS_NOT_RUNNING = 10023;

    const ANOTHER_PROCESS_USING_THE_IMAGE = 10025;

    const NO_MORE_AVAILABLE_PCI_SLOTS = 10026;

    const BUS_SATA_CAN_NOT_BE_HOT_PLUGGED = 10028;


    const INVALID_PARAMETER = 20000;

    const DISK_NOT_FOUND = 20001;

    const VNC_GRAPHIC_NOT_FOUND = 20002;

    const VNC_DISPLAY_PORT_NOT_FOUND = 20003;

    const NW_FILTER_NOT_FOUND = 20005;

    const TARGET_ALREADY_EXISTS = 20006;

    const INTERFACE_NOT_FOUND = 20008;
}