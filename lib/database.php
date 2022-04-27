<?php
declare(strict_types=1);
require_once 'config.php';

abstract class Database {
    protected $driver, $host, $port, $dbName, $user, $password;
    protected $connection;
    public function __construct(IConfig $config) {
        $this->driver = $config->getDriver();
        $this->host = $config->getHost();
        $this->port = $config->getPort();
        $this->dbName = $config->getDbName();
        $this->user = $config->getUsername();
        $this->password = $config->getPassword();
    }

    protected function connect() {
        $this->connection = new PDO("{$this->driver}:host={$this->host};port={$this->port};dbname={$this->dbName}",$this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::FETCH_ASSOC => true,
            PDO::FETCH_BOTH => false
        ]);
    }

    public function getConnection() {
        return $this->connection;
    }
}

class MySQLDatabase extends Database {
    public function __construct(IConfig $config) {
        parent::__construct($config);
        $this->driver = 'mysql';
        $this->connect();
    }
}

class PgSQLDatabase extends Database {
    public function __construct(IConfig $config) {
        parent::__construct($config);
        $this->driver = 'pgsql';
        $this->connect();
    }
}

function getConnection(): PDO {
    /*global $persistentConnection;
    return $persistentConnection;*/
    $config = ConfigManager::getConfig();
    return (new MySQLDatabase($config))->getConnection();
}
