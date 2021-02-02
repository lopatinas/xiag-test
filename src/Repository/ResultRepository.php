<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Result;
use App\Service\DbService;

class ResultRepository extends AbstractRepository
{
    public static function create(Result $result): Result
    {
        self::executeQuery("INSERT INTO result (poll_id, answer_id, name) VALUES (:pollId, :answerId, :name)", [
            ':pollId' => $result->getPollId(),
            ':answerId' => $result->getAnswerId(),
            ':name' => $result->getName(),
        ]);
        $result->setId((int) DbService::getInstance()->lastInsertId());

        return $result;
    }

    /**
     * @param int $pollId
     *
     * @return array|Result[]
     */
    public static function findByPollId(int $pollId): array
    {
        $results = [];
        $rows = self::findBy("SELECT * FROM result WHERE poll_id = :pollId", [':pollId' => $pollId]);

        foreach ($rows as $row) {
            $results[] = Result::createFromRaw($row);
        }

        return $results;
    }
}
