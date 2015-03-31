<?php

namespace AdrienSamson\GuzzleBundle;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\SubscriberInterface;

class ClientConfigurator
{
    private $parentConfigurator;
    private $subscribers = [];

    public function __construct(ClientConfigurator $parentConfigurator = null)
    {
        $this->parentConfigurator = $parentConfigurator;
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }

    public function configure(ClientInterface $client)
    {
        if ($this->parentConfigurator) {
            $this->parentConfigurator->configure($client);
        }
        $emitter = $client->getEmitter();
        foreach ($this->subscribers as $subscriber) {
            $emitter->attach($subscriber);
        }
    }
}
