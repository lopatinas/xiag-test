<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Service\PollService;
use App\Service\ResultService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PollController extends AbstractController
{
    public function new(Request $request): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $poll = PollService::create($request->request->all());

            return new RedirectResponse('/poll/' . $poll->getSlug());
        }

        return $this->render('new_poll.html.twig');
    }

    public function view(Request $request, string $slug): Response
    {
        $poll = PollService::findBySlug($slug);

        if ($poll === null) {
            return new Response('Poll not found', Response::HTTP_NOT_FOUND);
        }

        $hasAnswer = $request->cookies->has('poll_result_' . $slug);
        $results = ResultService::listByPoll($poll);

        if ($request->isMethod(Request::METHOD_POST)) {
            if ($hasAnswer) {
                return new RedirectResponse('/poll/' . $poll->getSlug());
            }

            ResultService::create($poll, $request->request->all());
            $response = new RedirectResponse('/poll/' . $poll->getSlug());
            $cookie = Cookie::create('poll_result_' . $slug, $slug, strtotime('+10 years'));
            $response->headers->setCookie($cookie);

            return $response;
        }

        return $this->render('view_poll.html.twig', [
            'poll' => $poll,
            'results' => $results,
            'hasAnswer' => $hasAnswer,
            'answerIds' => array_map(function (Answer $answer) {
                return $answer->getId();
            }, $poll->getAnswers())
        ]);
    }
}
