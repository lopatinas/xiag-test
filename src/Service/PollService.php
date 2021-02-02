<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Poll;
use App\Repository\PollRepository;

class PollService
{
    public static function create(array $data): Poll
    {
        $answers = [];

        foreach ($data['answers'] as $answer) {
            $answer = trim($answer);

            if (!empty($answer)) {
                $answers[] = [
                    'answer' => $answer,
                ];
            }
        }

        $poll = Poll::createFromRaw([
            'question' => $data['question'],
            'slug' => hash('sha256', $data['question'] . microtime()),
        ], $answers);
        $poll = PollRepository::create($poll);

        return $poll;
    }

    public static function findBySlug(string $slug): ?Poll
    {
        return PollRepository::findOneBySlug($slug);
    }
}
