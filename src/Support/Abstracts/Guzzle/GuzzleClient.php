<?php

namespace Rice\Basic\Support\Abstracts\Guzzle;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Carbon\CarbonInterface;
use GuzzleHttp\TransferStats;
use GuzzleHttp\RequestOptions;
use Rice\Basic\Contracts\LogContract;

abstract class GuzzleClient
{
    protected Client $client;
    protected LogContract $log;
    protected array $options;
    protected Carbon $startAt;

    /**
     * 请求是否报错标识.
     *
     * @var bool
     */
    protected bool $error = false;
    /**
     * 业务是否成功标识.
     *
     * @var bool
     */
    protected bool $success = false;

    /**
     * 业务请求报错是否打印 error.
     *
     * @var bool
     */
    protected bool $bizReportError = true;
    protected string $message      = 'GuzzleLog: ';

    public function __construct(LogContract $log)
    {
        $this->client = new Client();
        $this->log    = $log;
        $this->init();
        $this->logs();
        $this->startAt = Carbon::now();
        $this->send();
    }

    public function setBizReportError(bool $val): self
    {
        $this->bizReportError = $val;

        return $this;
    }

    /**
     * 请求发送
     *
     * @return void
     */
    abstract public function send(): void;

    /**
     * 变量初始化.
     *
     * @return void
     */
    abstract public function init(): void;

    /**
     * 是否成功判断.
     *
     * @return bool
     */
    abstract public function isSuccess(): bool;

    /**
     * 写日志.
     *
     * @return void
     */
    public function logs(): void
    {
        $this->options[RequestOptions::ON_STATS] = $this->getLogClosure();
    }

    public function getLogClosure(): \Closure
    {
        return function (TransferStats $stats) {
            $errorData = $stats->getHandlerErrorData();

            // https://curl.se/libcurl/c/libcurl-errors.html
            if (($errorData instanceof \Throwable) || (is_int($errorData) && $errorData > 0)) {
                $this->error = true;
            }

            if (!$this->error) {
                $this->success = $this->isSuccess();
            }

            $logInfo = $this->getLogInfo($stats);

            $this->logHandle($logInfo);
        };
    }

    /**
     * 日志信息处理.
     *
     * @param array $logInfo
     * @return void
     */
    public function logHandle(array $logInfo): void
    {
        // 成功
        if ($this->success) {
            $this->log::info($this->message, $logInfo);
        }
        // curl 请求报错
        if ($this->error) {
            $this->log::warning($this->message, $logInfo);
        }
        // 业务 请求报错
        if (!$this->error) {
            if ($this->bizReportError) {
                $this->log::error($this->message, $logInfo);
            } else {
                $this->log::debug($this->message, $logInfo);
            }
        }
    }

    public function getLogInfo(TransferStats $stats): array
    {
        $request  = $stats->getRequest();
        $response = $stats->getResponse();

        $this->message .= Uri::composeComponents($request->getUri()->getScheme(), $request->getUri()->getAuthority(), $request->getUri()->getPath(), '', '');

        return [
            //是否请求成功
            'succeed'      => $this->success,
            //请求相关数据
            'request'      => [
                'uri'     => (string) $request->getUri(),
                'method'  => $request->getMethod(),
                'headers' => $request->getHeaders(),
                'body'    => (string) $request->getBody(),
            ],
            //响应相关数据
            'response'     => [
                'statusCode'   => $response ? $response->getStatusCode() : -1,
                'reasonPhrase' => $response ? $response->getReasonPhrase() : '',
                'body'         => $response ? (string) $response->getBody() : '',
            ],
            //请求耗时等详细数据
            'handlerStats' => $stats->getHandlerStats(),
            //请求总耗时
            'transferTime' => $stats->getTransferTime(),
            //请求开始时间
            'startAt'      => $this->startAt->format(CarbonInterface::MOCK_DATETIME_FORMAT),
            //请求结束时间
            'endAt'        => Carbon::now()->format(CarbonInterface::MOCK_DATETIME_FORMAT),
            //发送请求时相关的上下文变量
            'extraContext' => $this->getContent(),
        ];
    }

    /**
     * 需要添加上下文变量，可重写该函数.
     *
     * @return array
     */
    public function getContent(): array
    {
        return [];
    }
}
