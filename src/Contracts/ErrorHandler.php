<?php


namespace Iivannov\Snitch\Contracts;


interface ErrorHandler
{

    /**
     * Main method that will perform the actual logging
     * or reporting of the exception/error
     *
     * @param \Throwable $e
     * @return mixed
     */
    public function handle(\Throwable $e, array $data = []);

    /**
     * Check if the current handler should handle the given exception/error
     *
     * @param \Throwable $e
     * @return bool
     */
    public function isAllowed(\Throwable $abstract): bool;


}