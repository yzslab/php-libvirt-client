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
}