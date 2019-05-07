<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 18:44
 */

namespace YunInternet\Libvirt;


use YunInternet\Libvirt\Exception\CertificateNotTrustedException;
use YunInternet\Libvirt\Exception\ErrorCode;
use YunInternet\Libvirt\Exception\LibvirtException;

abstract class Libvirt
{
    /**
     * Functions that allow to be invoked by __call
     */
    const WHITE_LIST_FUNCTIONS = [
        "libvirt_connect",
        "libvirt_get_last_error"
    ];

    public static function __callStatic($name, $arguments)
    {
        if (!array_key_exists($name, static::WHITE_LIST_FUNCTIONS))
            throw new LibvirtException("Unknown function " . $name, 10001);
        return call_user_func($name, ... $arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws LibvirtException
     */
    public function __call($name, $arguments)
    {
        if (!array_key_exists($name, static::WHITE_LIST_FUNCTIONS))
            throw new LibvirtException("Unknown function " . $name, 10001);

        try {
            $returnResult = call_user_func($name, ... $this->getResources($name), ... $arguments);
            if ($returnResult === false)
                self::errorHandler();
            return $returnResult;
        } catch (\ErrorException $e) {
            self::errorHandler();
        }
    }

    /**
     * @param string $functionName
     * @return resource[]
     */
    abstract protected function getResources($functionName);

    public static function getLastError()
    {
        return \libvirt_get_last_error();
    }

    /**
     * Patch version libvirt-php required
     * @return int The error code, a virErrorNumber, https://libvirt.org/html/libvirt-virterror.html#virErrorNumber
     */
    public static function getLastVirErrorNumber()
    {
        return \libvirt_get_last_error_code();
    }

    /**
     * Patch version libvirt-php required
     * @return int What part of the library raised this error, https://libvirt.org/html/libvirt-virterror.html#virErrorDomain
     */
    public static function getLastVirErrorDomain()
    {
        return \libvirt_get_last_error_domain();
    }

    /**
     * @param null|string $message
     * @throws LibvirtException
     * @throws CertificateNotTrustedException
     */
    public static function errorHandler($message = null)
    {
        if (is_null($message))
            $message = static::getLastError();
        // Not an error
        if (is_null($message))
            return;

        $errorCode = 0;

        if (self::isErrorMessageContainString($message, "certificate is not trusted"))
            throw new CertificateNotTrustedException($message, ErrorCode::CERTIFICATE_NOT_TRUSTED);
        if (self::isErrorMessageContainString($message, "Unable to import client certificate"))
            $errorCode = ErrorCode::UNABLE_IMPORT_CLIENT_CERTIFICATE;
        else if (self::isErrorMessageContainString($message, "Unable to set x509 key and certificate"))
            $errorCode = ErrorCode::UNABLE_SET_X509_KEY_AND_CERTIFICATE;
        else if (self::isErrorMessageContainString($message, "Failed to verify peer's certificate"))
            $errorCode = ErrorCode::FAILED_TO_VERIFY_PEER_CERTIFICATE;
        else if (self::isErrorMessageContainString($message, "The certificate hasn't got a known issuer"))
            $errorCode = ErrorCode::CERTIFICATE_HAS_NOT_GOT_A_KNOWN_ISSUER;
        else if (self::isErrorMessageContainString($message, "Unable to read TLS confirmation: Input/output error"))
            $errorCode = ErrorCode::UNABLE_READ_TLS_CONFIRMATION;
        else if (self::isErrorMessageContainString($message, "Storage pool not found:"))
            $errorCode = ErrorCode::STORAGE_POOL_NOT_FOUND;
        else if (self::isErrorMessageContainString($message, "Requested operation is not valid: storage pool '") && (self::isErrorMessageContainString($message, "' is already active") || self::isErrorMessageContainString($message, "' is active")))
            $errorCode = ErrorCode::STORAGE_POOL_IS_ACTIVE;
        else if (self::isErrorMessageContainString($message, "Domain not found"))
            $errorCode = ErrorCode::DOMAIN_NOT_FOUND;
        else if (self::isErrorMessageContainString($message, "Storage volume not found:"))
            $errorCode = ErrorCode::STORAGE_VOLUME_NOT_FOUND;
        else if (self::isErrorMessageContainString($message, "domain is already running"))
            $errorCode = ErrorCode::DOMAIN_IS_ALREADY_RUNNING;
        else if (self::isErrorMessageContainString($message, "domain is not running"))
            $errorCode = ErrorCode::DOMAIN_IS_NOT_RUNNING;
        else if (self::isErrorMessageContainString($message, "Network filter not found"))
            $errorCode = ErrorCode::NW_FILTER_NOT_FOUND;
        else if (self::isErrorMessageContainString($message, "No more available PCI slots"))
            $errorCode = ErrorCode::NO_MORE_AVAILABLE_PCI_SLOTS;
        else if (self::isErrorMessageContainString($message, "Is another process using the image"))
            $errorCode = ErrorCode::ANOTHER_PROCESS_USING_THE_IMAGE;
        else if (self::isErrorMessageContainString($message, "bus 'sata' cannot be hotplugged"))
            $errorCode = ErrorCode::BUS_SATA_CAN_NOT_BE_HOT_PLUGGED;
        else if (self::isErrorMessageContainString($message, "Requested operation is not valid: target") && self::isErrorMessageContainString($message, "already exists"))
            $errorCode = ErrorCode::TARGET_ALREADY_EXISTS;

        throw new LibvirtException($message, $errorCode);
    }

    private static function isErrorMessageContainString($message, $string)
    {
        return strpos($message, $string) !== false;
    }
}