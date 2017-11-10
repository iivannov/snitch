<?php


namespace Iivannov\Snitch;


use Iivannov\Snitch\Contracts\ErrorHandler;

class Snitch
{
    /**
     * An array of error handlers
     *
     * @var ErrorHandler[]
     */
    protected $handlers;


    /**
     * Add a handler to the handler list
     *
     * @param ErrorHandler $handler
     */
    public function handler(ErrorHandler $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Report an exception/error with all registered handlers
     * that are able to accept and handle it.
     *
     * @param \Throwable $e - The exception to be reported
     * @param array $data - Any additional data to be reported
     */
    public function report(\Throwable $e, array $data = [])
    {
        if (!$this->handlers) {
            return;
        }

        foreach ($this->handlers as $handler) {
            if ($handler->isAllowed($e)) {
                $handler->handle($e, $data);
            }
        }
    }
}