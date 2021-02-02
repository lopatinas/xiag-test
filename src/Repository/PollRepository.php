<?php
declare(strict_types=1);

namespace App\Repository;

use Exception;
use App\Entity\Poll;
use App\Service\DbService;

class PollRepository extends AbstractRepository
{
    public static function create(Poll $poll): Poll
    {
        $pdo = DbService::getInstance();
        $pdo->beginTransaction();

        try {
            self::executeQuery("INSERT INTO poll (slug, question) VALUES (:slug, :question)", [
                ':slug' => $poll->getSlug(),
                ':question' => $poll->getQuestion(),
            ]);
            $poll->setId((int)$pdo->lastInsertId());

            foreach ($poll->getAnswers() as &$answer) {
                $answer->setPollId($poll->getId());
                $answer = AnswerRepository::create($answer);
            }

            $pdo->commit();
        } catch (Exception $exception) {
            $pdo->rollBack();

            throw $exception;
        }

        return $poll;
    }

    public static function findOneBySlug(string $slug): ?Poll
    {
        $result = self::findBy("SELECT * FROM poll WHERE slug = :slug", [':slug' => $slug]);

        if (empty($result)) {
            return null;
        }

        $poll = Poll::createFromRaw($result[0]);
        $poll->setAnswers(AnswerRepository::findByPollId($poll->getId()));

        return $poll;
    }
}
