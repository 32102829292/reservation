<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'reservation_system',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'synchronous' => null,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT === 'production') {
            $dsn = env('DATABASE_URL', '');

            if (!empty($dsn)) {
                // Use full connection string (from Supabase)
                $this->default['DSN']      = $dsn;
                $this->default['DBDriver'] = 'Postgre';
                $this->default['schema']   = 'public';
            } else {
                // Fallback to individual vars
                $this->default['hostname'] = env('DB_HOSTNAME', '');
                $this->default['username'] = env('DB_USERNAME', 'postgres');
                $this->default['password'] = env('DB_PASSWORD', '');
                $this->default['database'] = env('DB_DATABASE', 'postgres');
                $this->default['DBDriver'] = 'Postgre';
                $this->default['port']     = (int) env('DB_PORT', 5432);
                $this->default['charset']  = 'utf8';
                $this->default['DBCollat'] = '';
                $this->default['schema']   = 'public';
            }
        }

        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}