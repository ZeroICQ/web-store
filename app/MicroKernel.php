<?php


use App\Routing\Router;

class MicroKernel
{
    /**
     * @param string $URI
     * @return string
     */
    public function handleRequest($URI) : string
    {
        return Router::handleUri($URI);
    }
}