<?php
/**
 * Created by PhpStorm.
 * Date: 19-2-28
 * Time: 下午10:54
 */

namespace YunInternet\Libvirt;


use YunInternet\Libvirt\Constants\Host\VirConnectCredentialType;

abstract class CredentialFactory
{
    public static function fromAuthNameAndPassphrase($authName, $passphrase)
    {
        return [VirConnectCredentialType::VIR_CRED_AUTHNAME => $authName, VirConnectCredentialType::VIR_CRED_PASSPHRASE => $passphrase];
    }
}