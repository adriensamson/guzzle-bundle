<?php

namespace AdrienSamson\GuzzleBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class GuzzleDataCollector extends DataCollector
{
    /**
     * @var GuzzleLogger
     */
    private $logger;

    public function __construct(GuzzleLogger $logger)
    {
        $this->logger = $logger;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['messages'] = $this->logger->getMessages();
    }

    public function getName()
    {
        return 'guzzle';
    }

    public function getMessages()
    {
        return $this->data['messages'];
    }

    public function getTotalTime()
    {
        return array_sum(array_map(function ($m) { return $m['transfer_info']['total_time']; }, $this->data['messages']));
    }
}
