<?php


namespace App\Api;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController
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
     * UserInfoApiController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new Response();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function render($data): bool
    {
        $content = json_encode($data);
        $this->response->setContent($content);
        return true;
    }
}
