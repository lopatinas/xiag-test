<?php
declare(strict_types=1);

namespace App\Service;

use PDO;

class DbService extends PDO
{
    private static ?DbService $instance = null;

    public function __construct()
    {
        parent::__construct('mysql:host=db;port=3306;dbname=test', 'test', 'test');
    }

    public static function getInstance(): DbService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
