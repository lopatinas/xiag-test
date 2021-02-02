<?php
declare(strict_types=1);

namespace App\Websocket;

use GuzzleHttp\Psr7\Uri;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Exception;

class Websocket implements MessageComponentInterface
{
    protected SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        /** @var Uri $uri */
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $numRecv = 0;

        foreach ($this->clients as $client) {
            if ($from->resourceId === $client->resourceId) {
                continue;
            }

            if ($this->getUri($from) !== $this->getUri($client)) {
                continue;
            }

            $client->send($msg);
            $numRecv++;
        }

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function getUri(ConnectionInterface $conn): string
    {
        return $conn->httpRequest->getUri()->getPath();
    }
}
