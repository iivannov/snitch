# Snitch - Report and log exceptions to multiple different places

The package gives you the ability to define multiple handlers and subscribe them to accept different Exceptions that will be logged or reported.
This way you can log different errors in different files, to ignore some of the errors or to save specific errors in a database and send others to a third party service.



## Installation

The package can be installed with Composer. Just run this command:

``` bash
$ composer require iivannov/snitch
```


## Supported Handlers
Currently we support only two handlers:

- LoggerHandler - accepting PSR LoggerInterface::class, so you can inject any other compatible logger instance.
- SentryHandler - sends debug data to Sentry service

Any contributions are welcomed :)


## Usage

### Initialization
We define a new SentryHandler and we tell it that all exceptions other than an \InvalidArgumentException::class will be sent to Sentry service
Then we create a new Snitch instance and add the handler.

``` php
$handler = new SentryHandler(YOUR_SENTRY_DSN);
$handler->ignore(\InvalidArgumentException::class);
 
$snitch = new Snitch();
$snitch->handler($handler);
```


### Report exceptions
Snitch will check which registered handlers are accepting the given exception and they will log or report it.

``` php
try {
    ...
} catch (\Exception $e) {
    $snitch->report($exception, ['foo' => 'bar']);
}
```
