<?php
/**
 * Created by PhpStorm.
 * Date: 19-3-22
 * Time: 下午7:30
 */

namespace YunInternet\Libvirt;


class GuestAgent
{
    private $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function ping()
    {
        return $this->guestAgentExecute("guest-ping");
    }

    public function sync($id = null)
    {
        if (is_null($id))
            $id = random_int(0x0, 0xFFFFFFFF);
        $response = $this->guestAgentExecute("guest-sync", ["id" => $id]);
        return $id === $response["return"];
    }

    public function getInfo()
    {
        return $this->guestAgentExecute("guest-info");
    }

    public function getOsInfo()
    {
        return $this->guestAgentExecute("guest-get-osinfo");
    }

    public function setPlainTextPassword($username, $plainTextPassword, $crypted = false)
    {
        return $this->setBase64EncodedPassword($username, base64_encode($plainTextPassword), $crypted);
    }

    public function setBase64EncodedPassword($username, $base64EncodedPassword, $crypted = false)
    {
        return $this->guestAgentExecute("guest-set-user-password", [
            "username" => $username,
            "password" => $base64EncodedPassword,
            "crypted" => $crypted,
        ]);
    }

    public function getNetworkInterfaces()
    {
        return $this->guestAgentExecute("guest-network-get-interfaces");
    }

    public function fileOpen($path, $mode)
    {
        return $this->guestAgentExecute("guest-file-open", [
            "path" => $path,
            "mode" => $mode,
        ]);
    }

    public function fileWrite($handle, $content)
    {
        return $this->guestAgentExecute("guest-file-write", [
            "handle" => $handle,
            "buf-b64" => base64_encode($content),
        ]);
    }

    /**
     * @param int $handle
     * @param int $count
     * @param mixed $readContent The content return from qga, and decoded
     * @param int $readCount Then bytes read by qga
     * @param mixed $rawResponse
     * @return bool Reach end of file
     */
    public function fileRead($handle, $count, &$readContent, &$readCount, &$rawResponse = null)
    {
        $rawResponse = $this->guestAgentExecute("guest-file-read", [
            "handle" => $handle,
            "count" => $count,
        ]);

        $readContent = base64_decode($rawResponse["return"]["buf-b64"]);
        $readCount = $rawResponse["return"]["count"];
        return $rawResponse["return"]["eof"];
    }

    public function fileClose($handle)
    {
        return $this->guestAgentExecute("guest-file-close", [
            "handle" => $handle,
        ]);
    }

    public function exec($path, $args = [], $captureOutput = false)
    {
        return $this->guestAgentExecute("guest-exec", [
            "path" => $path,
            "arg" => $args,
            "capture-output" => $captureOutput,
        ]);
    }

    public function execStatus($pid)
    {
        return $this->guestAgentExecute("guest-exec-status", [
            "pid" => $pid,
        ]);
    }

    public function guestAgentExecute($command, $arguments = null)
    {
        if (is_null($arguments))
            $arguments = new \stdClass();
        return $this->guestAgentCommand([
            "execute" => $command,
            "arguments" => $arguments,
        ]);
    }

    public function guestAgentCommand($command, $timeout = -1, int $flags = 0)
    {
        if (is_array($command))
            $command = json_encode($command);
        return json_decode($this->domain->libvirt_domain_qemu_agent_command($command, $timeout, $flags), true);
    }
}