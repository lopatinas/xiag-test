<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Answer;
use App\Service\DbService;

class AnswerRepository extends AbstractRepository
{
    public static function create(Answer $answer): Answer
    {
        self::executeQuery("INSERT INTO answer (poll_id, answer) VALUES (:pollId, :answer)", [
            ':pollId' => $answer->getPollId(),
            ':answer' => $answer->getAnswer(),
        ]);
        $answer->setId((int) DbService::getInstance()->lastInsertId());

        return $answer;
    }

    /**
     * @param int $pollId
     *
     * @return array|Answer[]
     */
    public static function findByPollId(int $pollId): array
    {
        $results = [];
        $rows = self::findBy("SELECT * FROM answer WHERE poll_id = :pollId", [':pollId' => $pollId]);

        foreach ($rows as $row) {
            $results[] = Answer::createFromRaw($row);
        }

        return $results;
    }
}
