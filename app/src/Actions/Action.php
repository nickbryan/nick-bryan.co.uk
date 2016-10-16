<?php

namespace App\Actions;

use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Action
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->contianer = $container;
    }

    abstract public function __invoke(Request $request, Response $response);
}
