<?php

namespace App\BaseClasses;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Controller{
    protected mixed $db;
    protected ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->db = $container->get('database');
    }

}