<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Poll;
use App\Entity\Result;
use App\Repository\ResultRepository;

class ResultService
{
    public static function create(Poll $poll, array $data): Result
    {
        $data['poll_id'] = $poll->getId();
        $result = Result::createFromRaw($data);
        $result = ResultRepository::create($result);

        WebsocketClientService::sendMessage($poll->getSlug(), [
            'name' => $result->getName(),
            'answerId' => $result->getAnswerId()
        ]);

        return $result;
    }

    public static function listByPoll(Poll $poll): array
    {
        return ResultRepository::findByPollId($poll->getId());
    }
}
