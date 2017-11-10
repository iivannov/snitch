<?php


namespace Iivannov\Snitch\Handlers;

use Iivannov\Snitch\BaseHandler;

class SentryHandler extends BaseHandler
{
    /**
     * The Raven client instance
     *
     * @var \Raven_Client
     */
    protected $client;

    /**
     * SentryHandler constructor.
     * @param string $dsn - DSN for your Sentry account
     */
    public function __construct(string $dsn)
    {
        $this->client = new \Raven_Client($dsn);
    }


    public function handle(\Throwable $e, array $data = [])
    {
        $this->client->captureException($e, $data);
    }

}