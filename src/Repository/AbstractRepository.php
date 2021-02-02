<?php
declare(strict_types=1);

namespace App\Repository;

use App\Service\DbService;
use PDO;

abstract class AbstractRepository
{
    protected static function executeQuery(string $sql, array $params = []): void
    {
        $pdo = DbService::getInstance();
        $query = $pdo->prepare($sql);
        $result = $query->execute($params);

        if ($result === false) {
            $error = implode(' ', $pdo->errorInfo());

            throw new \LogicException('Cannot execute query: ' . $error);
        }
    }

    protected static function findBy(string $sql, array $params = []): array
    {
        $pdo = DbService::getInstance();
        $query = $pdo->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
