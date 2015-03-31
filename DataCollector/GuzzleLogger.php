<?php

namespace AdrienSamson\GuzzleBundle\DataCollector;

use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

class GuzzleLogger implements SubscriberInterface
{
    private $seenRequests = [];
    private $messages = [];

    public function getEvents()
    {
        return [
            'complete' => ['onComplete', RequestEvents::EARLY]
        ];
    }

    public function onComplete(CompleteEvent $event)
    {
        if (in_array($event->getRequest(), $this->seenRequests, true)) {
            return;
        }
        $this->seenRequests[] = $event->getRequest();
        $this->messages[] = [
            'request' => $this->transformRequest($event->getRequest()),
            'response' => $this->transformResponse($event->getResponse()),
            'transfer_info' => $event->getTransferInfo(),
        ];
    }

    public function getMessages()
    {
        return $this->messages;
    }

    protected function transformRequest(RequestInterface $request)
    {
        return [
            'url' => $request->getUrl(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody() ? $request->getBody()->getContents() : null,
        ];
    }

    protected function transformResponse(ResponseInterface $response)
    {
        return [
            'effective_url' => $response->getEffectiveUrl(),
            'status_code' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody() ? $response->getBody()->getContents() : null,
        ];
    }
}
