<?php
declare(strict_types=1);

namespace App;

use App\Controller\PollController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    protected static array $routes = [
        '\/' => [
            'class' => PollController::class,
            'action' => 'new',
        ],
        '\/poll\/{slug}' => [
            'class' => PollController::class,
            'action' => 'view',
            'params' => [
                'slug' => '[-a-zA-Z0-9]+',
            ]
        ],
    ];

    public function handle(Request $request): Response
    {
        $uri = $request->server->get('REQUEST_URI');
        $controller = null;

        foreach (self::$routes as $pattern => $data) {
            if (!empty($data['params'])) {
                $search = array_map(function ($item) {
                    return "{{$item}}";
                }, array_keys($data['params']));

                $replace = array_map(function ($key, $value) {
                    return "(?P<{$key}>{$value})";
                }, array_keys($data['params']), array_values($data['params']));

                $pattern = str_replace($search, $replace, $pattern);
            }

            $pattern = '/^' . $pattern . '$/i';

            if (preg_match($pattern, $uri, $matches)) {
                $controller = $data;

                if (!empty($controller['params'])) {
                    foreach ($controller['params'] as $key => $param) {
                        $controller['params'][$key] = $matches[$key];
                    }
                }

                break;
            }
        }

        if ($controller === null) {
            return new Response('Page not found', Response::HTTP_NOT_FOUND);
        }

        $arguments = [$request];

        if (!empty($controller['params']) && is_array($controller['params'])) {
            $arguments = array_merge($arguments, array_values($controller['params']));
        }

        return (new $controller['class']())->{$controller['action']}(...$arguments);
    }
}
