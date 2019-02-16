<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 20:55
 */

namespace YunInternet\Libvirt\Constants\Host;


interface VirConnectCredentialType
{
    const VIR_CRED_USERNAME = VIR_CRED_USERNAME; /* Identity to act as */
    const VIR_CRED_AUTHNAME = VIR_CRED_AUTHNAME; /* Identify to authorize as */
    const VIR_CRED_LANGUAGE = VIR_CRED_LANGUAGE; /* RFC 1766 languages, comma separated */
    const VIR_CRED_CNONCE = VIR_CRED_CNONCE; /* client supplies a nonce */
    const VIR_CRED_PASSPHRASE = VIR_CRED_PASSPHRASE; /* Passphrase secret */
    const VIR_CRED_ECHOPROMPT = VIR_CRED_ECHOPROMPT; /* Challenge response */
    const VIR_CRED_NOECHOPROMPT = VIR_CRED_NOECHOPROMPT; /* Challenge response */
    const VIR_CRED_REALM = VIR_CRED_REALM; /* Authentication realm */
    const VIR_CRED_EXTERNAL = VIR_CRED_EXTERNAL; /* Externally managed credential */
    const VIR_CRED_LAST = VIR_CRED_LAST; /* More may be added - expect the unexpected */
}