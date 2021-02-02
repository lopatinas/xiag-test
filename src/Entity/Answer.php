<?php
declare(strict_types=1);

namespace App\Entity;

class Answer
{
    protected ?int $id;
    protected ?int $pollId;
    protected string $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Answer
    {
        $this->id = $id;

        return $this;
    }

    public function getPollId(): ?int
    {
        return $this->pollId;
    }

    public function setPollId(int $pollId): Answer
    {
        $this->pollId = $pollId;

        return $this;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): Answer
    {
        $this->answer = $answer;

        return $this;
    }

    public static function createFromRaw(array $data): Answer
    {
        $answer = new Answer();
        $answer->id = !empty($data['id']) ? (int) $data['id'] : null;
        $answer->pollId = !empty($data['poll_id']) ? (int) $data['poll_id'] : null;
        $answer->answer = $data['answer'];

        return $answer;
    }
}
