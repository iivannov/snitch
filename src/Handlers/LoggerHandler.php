<?php


namespace Iivannov\Snitch\Handlers;

use Iivannov\Snitch\BaseHandler;
use Psr\Log\LoggerInterface;


class LoggerHandler extends BaseHandler
{
    /**
     * A PSR compatible logger instance
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Closure
     */
    private $formatter;

    /**
     * DefaultHandler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, \Closure $formatter = null)
    {
        $this->logger = $logger;
        $this->formatter = $formatter;
    }

    public function handle(\Throwable $e, array $data = [])
    {
        $message = (string) ($this->formatter ? call_user_func($this->formatter, $e) : $e);
        $this->logger->error($message, $data);
    }

}