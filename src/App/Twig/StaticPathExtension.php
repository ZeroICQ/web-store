<?php


namespace App\Twig;


class StaticPathExtension extends \Twig_Extension
{
    /**
     * Path to static directory
     * @var string
     */
    private $dir;


    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    public function getPath($filePath)
    {
        return $this->dir.'/'.$filePath;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('static_path', [$this, 'getPath'])
        ];
    }

}