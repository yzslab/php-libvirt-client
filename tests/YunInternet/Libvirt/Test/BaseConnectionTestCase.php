<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/27
 * Time: 18:15
 */

namespace YunInternet\Libvirt\Test;


use PHPUnit\Framework\TestCase;
use YunInternet\Libvirt\Connection;

abstract class BaseConnectionTestCase extends TestCase
{
    /**
     * @var Connection $libvirtConnection
     */
    private $libvirtConnection;

    private $uri;

    private $username;

    private $password;

    /**
     * @var Connection[] $libvirtConnections
     */
    private static $libvirtConnections = [];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->uri = @$_SERVER["URI"];
        $this->username = @$_SERVER["USERNAME"];
        $this->password = @$_SERVER["PASSWORD"];

        if (!isset($this->uri))
            $this->uri = "test:///default";

        $this->libvirtConnection = $this->createConnectionOrReuseExistsConnection();
    }

    /**
     * @return Connection
     */
    public function getLibvirtConnection(): Connection
    {
        return $this->libvirtConnection;
    }

    private function createConnection()
    {
        return new Connection($this->uri, $this->username, $this->password);
    }

    private function createConnectionOrReuseExistsConnection()
    {
        $key = sprintf("%s%s%s", $this->uri, $this->username, $this->password);

        if (!array_key_exists($key, self::$libvirtConnections)) {
            self::$libvirtConnections[$key] = $this->createConnection();
        }

        return self::$libvirtConnections[$key];
    }
}