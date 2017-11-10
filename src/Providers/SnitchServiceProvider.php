<?php

namespace Iivannov\Snitch\Providers;

use Iivannov\Snitch\Contracts\AcceptRestrictions;
use Iivannov\Snitch\Handlers\LoggerHandler;
use Iivannov\Snitch\Handlers\SentryHandler;
use Iivannov\Snitch\Snitch;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;


class SnitchServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/laravel.php' => config_path('snitch.php')], 'snitch');
    }

    public function register()
    {
        $this->app->singleton('snitch', function ($app) {
            return $this->binding();
        });
    }

    protected function binding(): Snitch
    {
        $snitch = new Snitch();

        if (config('snitch.default.enabled')) {
            $this->registerDefaultHandler($snitch);
        }

        if (config('snitch.rotating')) {
            $this->registerRotatingHandlers($snitch);
        }

        if (config('snitch.sentry.enabled')) {
            $this->registerSentryHandler($snitch);
        }

        return $snitch;
    }


    private function registerDefaultHandler(Snitch $snitch)
    {
        $handler = new LoggerHandler($this->app->make(LoggerInterface::class), $this->formatter(config('snitch.default.trace')));
        $this->applyRestrictions($handler, config('snitch.default.ignore'), config('snitch.default.accept'));

        $snitch->handler($handler);
    }

    private function registerRotatingHandlers(Snitch $snitch)
    {
        foreach (config('snitch.rotating') as $key => $config) {

            $logger = new \Illuminate\Log\Writer(new \Monolog\Logger($key));
            $logger->useDailyFiles(storage_path("/logs/{$key}.log"), $config['keep'] ?? 5);

            $handler = new LoggerHandler($logger, $this->formatter($config['trace']));
            $this->applyRestrictions($handler, $config['ignore'], $config['accept']);

            $snitch->handler($handler);
        }
    }

    private function registerSentryHandler(Snitch $snitch)
    {
        $handler = new SentryHandler(config('snitch.sentry.dsn'));
        $this->applyRestrictions($handler, config('snitch.sentry.ignore'), config('snitch.sentry.accept'));

        $snitch->handler($handler);
    }

    protected function applyRestrictions(AcceptRestrictions $handler, array $ignored = [], array $accepted = [])
    {
        if ($ignored) {
            array_walk($ignored, function ($abstract) use ($handler) {
                $handler->ignore($abstract);
            });
        }

        if ($accepted) {
            array_walk($accepted, function ($abstract) use ($handler) {
                $handler->accept($abstract);
            });
        }
    }

    protected function formatter($trace = true): ?\Closure
    {
        if ($trace) {
            return null;
        }

        return function (\Throwable $e) {
            return $e->getCode() . ' ' . get_class($e) . ' ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        };
    }

}
