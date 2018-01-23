<?php

namespace Nohponex\MonologDataDogHandler;

use DataDog\DogStatsd;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Monolog Handler that uses Datadog
 * Implemented for TCP Submission to Datadog API
 * @link https://app.datadoghq.com
 * @link https://github.com/DataDog/php-datadogstatsd
 * @link https://app.datadoghq.com/account/settings#api To get api and app key
 * @link https://github.com/Seldaek/monolog
 *
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class DataDogHandler extends AbstractProcessingHandler
{
    /**
     * Map Monolog\Logger logging levels to Datadog alert_type
     */
    protected const ALERT_TYPE_MAP = [
        Logger::DEBUG     => 'info',
        Logger::INFO      => 'info',
        Logger::NOTICE    => 'warning',
        Logger::WARNING   => 'warning',
        Logger::ERROR     => 'error',
        Logger::ALERT     => 'error',
        Logger::CRITICAL  => 'error',
        Logger::EMERGENCY => 'error',
    ];

    /**
     * @var string[]
     */
    protected $tags;

    /**
     * @var DogStatsd
     */
    protected $dogStatsd;

    /**
     * @param string $appKey datadoghq.com app key
     * @param string $apiKey datadoghq.com api key
     * @param string[] $tags  Tags to sent to DataDog
     * @param int     $level  The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(
        string $appKey,
        string $apiKey,
        array $tags = [],
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $this->dogStatsd = new DogStatsd([
            'app_key' => $appKey,
            'api_key' => $apiKey,
        ]);

        $this->tags = $tags;
    }

    /**
     * @inheritdoc
     */
    protected function write(array $record)
    {
        $this->dogStatsd->event(
            $record['message'],
            [
                'text'       => $record['formatted'],
                'alert_type' => static::ALERT_TYPE_MAP[$record['level']] ?? 'info',
                'tags'       => $this->tags,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter();
    }
}
