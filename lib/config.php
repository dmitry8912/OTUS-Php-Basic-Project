<?php
declare(strict_types=1);

interface IConfig {
    public function getDriver();
    public function getHost();
    public function getPort();
    public function getDbName();
    public function getUsername();
    public function getPassword();
}

class Config implements IConfig {
    private $config;
    public function __construct($configPath)
    {
        if(!file_exists($configPath))
            die("config.ini not found!");
        $this->config = parse_ini_file($configPath, true);
    }
    public function getDriver()
    {
        return $this->config['database']['driver'];
    }
    public function getHost()
    {
        return $this->config['database']['host'];
    }
    public function getPort()
    {
        return $this->config['database']['port'];
    }
    public function getDbName()
    {
        return $this->config['database']['db'];
    }
    public function getUsername()
    {
        return $this->config['database']['user'];
    }
    public function getPassword()
    {
        return $this->config['database']['password'];
    }
}

class ConfigManager {
    public static function getConfig(): IConfig {
        return new Config($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'config.ini');

    }
}
