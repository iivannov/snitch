<?php


namespace Iivannov\Snitch;


use Iivannov\Snitch\Contracts\AcceptRestrictions;
use Iivannov\Snitch\Contracts\ErrorHandler;

abstract class BaseHandler implements ErrorHandler, AcceptRestrictions
{

    /**
     * An array of ignored exceptions/errors
     *
     * @var array
     */
    protected $ignores = [];

    /**
     * An array of exceptions/error that are accepted for processing
     * @var array
     */
    protected $accepts = [];

    /**
     * Add a class to the list of ignored exceptions/errors
     *
     * @param string $abstract - The name of the class
     */
    public function ignore(string $abstract)
    {
        $this->ignores[] = $abstract;
    }


    /**
     * Add a class to the list of accepted exceptions/errors
     *
     * @param string $abstract - The name of the class
     */
    public function accept(string $abstract)
    {
        $this->accepts[] = $abstract;
    }

    /**
     * Check if the current handler should handle the given exception/error
     *
     * 1. If the $accepts collection has records only those will be processed.
     *
     * 2. If the $accepts collection is empty all exceptions/errors will be processed
     * except the ones present in the $ignores collection
     *
     * @param \Throwable $e
     * @return bool
     */
    public function isAllowed(\Throwable $e): bool
    {
        if(!empty($this->accepts)) {
            return in_array(get_class($e), $this->accepts);
        }

        if (empty($this->ignores)) {
            return true;
        }

        return !in_array(get_class($e), $this->ignores);
    }

    /**
     * Main method that will perform the actual logging
     * or reporting of the exception/error
     *
     * @param \Throwable $e
     * @return mixed
     */
    abstract public function handle(\Throwable $e, array $data = []);

}