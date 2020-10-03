<?php


namespace App\Handler;


use Bermuda\Templater\RendererInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


use function App\response_write_html;


/**
 * Class ViewHandler
 * @package App\Handler
 */
class ViewHandler extends Handler
{
    protected RendererInterface $render;
    protected string $template = 'app::home';

    public function __construct(ResponseFactoryInterface $factory, RendererInterface $render)
    {
        $this->render = $render;
        parent::__construct($factory);
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view($this->template, $this->getParams($request));
    }

    /**
     * @param string $template
     * @param array $params
     * @return ResponseInterface
     */
    protected function view(string $template, array $params = []): ResponseInterface
    {
        return response_write_html(
            $this->responseFactory->createResponse(),
            $this->render->render($template, $params)
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function getParams(ServerRequestInterface $request): array
    {
        return [];
    }
}