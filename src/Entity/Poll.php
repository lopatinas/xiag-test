<?php
declare(strict_types=1);

namespace App\Entity;

class Poll
{
    protected ?int $id;
    protected string $slug;
    protected string $question;
    protected array $answers = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): Poll
    {
        $this->id = $id;

        return $this;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): Poll
    {
        $this->slug = $slug;

        return $this;
    }
    public function getQuestion(): string
    {
        return $this->question;
    }
    public function setQuestion(string $question): Poll
    {
        $this->question = $question;

        return $this;
    }
    public function getAnswers(): array
    {
        return $this->answers;
    }
    public function setAnswers(array $answers): Poll
    {
        $this->answers = $answers;

        return $this;
    }

    public static function createFromRaw(array $data, array $answers = []): Poll
    {
        $poll = new Poll();
        $poll->id = !empty($data['id']) ? (int) $data['id'] : null;
        $poll->slug = $data['slug'];
        $poll->question = $data['question'];

        foreach ($answers as $answer) {
            $poll->answers[] = Answer::createFromRaw($answer);
        }

        return $poll;
    }
}
