<?php

namespace Rice\Basic\Support\Abstracts\Guzzle;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Carbon\CarbonInterface;
use GuzzleHttp\TransferStats;
use GuzzleHttp\RequestOptions;
use Rice\Basic\Contracts\LogContract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class GuzzleClient
{
    protected Client $client;
    protected RequestInterface $request;
    protected ?ResponseInterface $response;
    protected LogContract $log;
    protected Carbon $startAt;

    protected \Closure $callback;

    /**
     * 请求选项.
     *
     * @var array
     */
    private array $options = [];
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
    }

    public function setBizReportError(bool $val): self
    {
        $this->bizReportError = $val;

        return $this;
    }

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
    public function isSuccess(): bool
    {
        return $this->success;
    }

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
            $this->request  = $stats->getRequest();
            $this->response = $stats->getResponse();
            $errorData      = $stats->getHandlerErrorData();

            // https://curl.se/libcurl/c/libcurl-errors.html
            if (($errorData instanceof \Throwable) || (is_int($errorData) && $errorData > 0)) {
                $this->error          = true;
                $this->bizReportError = false;
            }

            if (!$this->error) {
                $this->success = $this->getCallback();
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
            $this->log->info($this->message, $logInfo);
            return;
        }
        // curl 请求报错
        if ($this->error) {
            $this->log->warning($this->message, $logInfo);
            return;
        }

        // 业务 请求报错
        if ($this->bizReportError) {
            $this->log->error($this->message, $logInfo);
            return;
        }

        $this->log->debug($this->message, $logInfo);

    }

    public function getLogInfo(TransferStats $stats): array
    {
        $this->message .= Uri::composeComponents(
            $this->request->getUri()->getScheme(),
            $this->request->getUri()->getAuthority(),
            $this->request->getUri()->getPath(),
            '',
            ''
        );

        return [
            //是否请求成功
            'succeed'      => $this->success,
            //请求相关数据
            'request'      => [
                'uri'     => (string) $this->request->getUri(),
                'method'  => $this->request->getMethod(),
                'headers' => $this->request->getHeaders(),
                'body'    => (string) $this->request->getBody(),
            ],
            //响应相关数据
            'response'     => [
                'statusCode'   => $this->response ? $this->response->getStatusCode() : -1,
                'reasonPhrase' => $this->response ? $this->response->getReasonPhrase() : '',
                'body'         => $this->response ? (string) $this->response->getBody() : '',
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

    public function mergeOption(string $key, array $value): void
    {
        if (!isset($this->options[$key])) {
            $this->options[$key] = $value;

            return;
        }
        $this->options[$key] = array_merge($this->options[$key], $value);
    }

    /**
     * @return bool
     */
    public function getCallback(): bool
    {
        return ($this->callback)($this->response);
    }

    /**
     * 设置请求结果是否成功闭包函数.
     *
     * @param \Closure $callback
     */
    public function setSuccessCondition(\Closure $callback): void
    {
        $this->callback = $callback;
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

    public function getOptions(): array
    {
        return $this->options;
    }
}
