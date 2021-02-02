<?php
declare(strict_types=1);

namespace App\Entity;

class Result
{
    protected ?int $id;
    protected ?int $pollId;
    protected ?int $answerId;
    protected string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Result
    {
        $this->id = $id;

        return $this;
    }

    public function getPollId(): ?int
    {
        return $this->pollId;
    }

    public function setPollId(int $pollId): Result
    {
        $this->pollId = $pollId;

        return $this;
    }

    public function getAnswerId(): ?int
    {
        return $this->answerId;
    }

    public function setAnswerId(int $answerId): Result
    {
        $this->answerId = $answerId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Result
    {
        $this->name = $name;

        return $this;
    }

    public static function createFromRaw(array $data): Result
    {
        $result = new Result();
        $result->id = !empty($data['id']) ? (int) $data['id'] : null;
        $result->pollId = !empty($data['poll_id']) ? (int) $data['poll_id'] : null;
        $result->answerId = !empty($data['answer_id']) ? (int) $data['answer_id'] : null;
        $result->name = $data['name'];

        return $result;
    }
}
