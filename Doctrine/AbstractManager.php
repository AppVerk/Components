<?php

namespace AppVerk\Components\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;

abstract class AbstractManager implements ManagerInterface
{
    protected $objectManager;

    protected $className;

    public function __construct($className, $objectManager)
    {
        $this->className = $className;
        $this->objectManager = $objectManager;
    }

    public function persistAndFlash(EntityInterface $entity)
    {
        $this->objectManager->persist($entity);
        $this->objectManager->flush();
    }

    public function persist(EntityInterface $entity)
    {
        $this->objectManager->persist($entity);
    }

    public function flush()
    {
        $this->objectManager->flush();
    }

    public function remove(EntityInterface $entity)
    {
        $this->objectManager->remove($entity);
        $this->objectManager->flush();
    }

    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    public function refresh(EntityInterface $Field)
    {
        $this->objectManager->refresh($Field);
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        /** @var ObjectRepository $objectRepository */
        $objectRepository = $this->objectManager->getRepository($this->className);

        return $objectRepository;
    }
}
