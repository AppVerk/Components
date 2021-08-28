<?php

namespace AppVerk\Components\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

interface ManagerInterface
{
    public function __construct($className, EntityManagerInterface $objectManager);

    /**
     * @return EntityRepository
     */
    public function getRepository();

    public function flush();

}
