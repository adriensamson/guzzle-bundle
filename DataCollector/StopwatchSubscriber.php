<?php

namespace AdrienSamson\GuzzleBundle\DataCollector;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class StopwatchSubscriber implements SubscriberInterface
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public function getEvents()
    {
        return [
            'before' => ['onBefore', RequestEvents::LATE],
            'complete' => ['onComplete', RequestEvents::EARLY],
        ];
    }

    public function onBefore(BeforeEvent $event)
    {
        $this->stopwatch->start($event->getRequest()->getUrl(), 'guzzle');
    }

    public function onComplete(CompleteEvent $event)
    {
        if ($this->stopwatch->isStarted($event->getRequest()->getUrl())) {
            $this->stopwatch->stop($event->getRequest()->getUrl());
        }
    }
}
