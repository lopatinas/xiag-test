<?php
declare(strict_types=1);

namespace App\Service;

use WebSocket\Client;
use WebSocket\TimeoutException;

class WebsocketClientService
{
    public static function sendMessage(string $slug, array $data) {
        try {
            $client = new Client("ws://ws:8080/poll/{$slug}");
            $client->text(json_encode($data));
            echo $client->receive();
            $client->close();
        } catch (TimeoutException $exception) {}
    }
}
