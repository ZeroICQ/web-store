<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

abstract class BaseController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * BaseController constructor.
     * @param Request $request
     * @param Twig_Environment $twig
     */
    public function __construct(Request $request, Twig_Environment $twig)
    {
        $this->request = $request;
        $this->twig = $twig;

        $this->response = new Response();
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->isMethod('POST');
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->request->isMethod('GET');
    }

    /**
     * @param string $templateName
     * @param array $params
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $templateName, array $params = []): bool
    {
        $content = $this->twig->render($templateName, $params);

        $this->response->setContent($content);
        return true;
    }
}