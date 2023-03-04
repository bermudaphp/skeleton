<?php

use Bermuda\ErrorHandler\Renderer\WhoopsRenderer;

final class ErrorHandler
{
    public function __construct(
        private ?string $logDir = null,
        private ?string $template = null
    ) {
        if (!$this->logDir) $this->logDir = dirname(__DIR__) . '/logs';
        if (!$this->template) $this->template = dirname(__DIR__) . '/public/template/error.phtml';
    }

    /**
     * @param Throwable $e
     * @param bool $devMode
     * @return never
     */
    public function handle(\Throwable $e, bool $devMode = true): never
    {
        $logFile = $this->logDir . '\\' . ($now = new \DateTime())->format('Y-m-d') . '.log';

        if (!file_exists($logFile) && is_writable($this->logDir)) {
            write:
            file_put_contents($logFile, $this->renderException($e, $now->format('H:i:s p')), FILE_APPEND);
        } elseif (!is_writable($logFile) && chmod($logFile, 0600)) {
            goto write;
        } else {
            if (!chmod($this->logDir, 0600)) {
                http_response_code(500);
                $output = PHP_SAPI === 'cli' ? $this->renderException($e, $now->format('H:i:s p'))
                    : $this->renderTemplate($e, $devMode);
                exit($output);
            }

            goto write;
        }

        http_response_code(500);
        $output = PHP_SAPI === 'cli' ? $this->renderException($e, $now->format('H:i:s p'))
            : $this->renderTemplate($e, $devMode);

        exit($output);
    }

    private function renderException(Throwable $e, string $time): string
    {
        return sprintf(
                '[%s][Exception: %s, File: %s, Line: %s, Message: %s, Code: %s]',
                $time, $e::class,
                $e->getFile(), $e->getLine(),
                $e->getMessage(), $e->getCode()
            ) . PHP_EOL;
    }

    private function renderTemplate(Throwable $e, bool $devMode): string
    {
        if ($devMode) return (new WhoopsRenderer())->renderException($e);
        extract(['msg' => 'The application ended with an error']);
        ob_start();
        require_once $this->template;
        return ob_get_clean();
    }
}
