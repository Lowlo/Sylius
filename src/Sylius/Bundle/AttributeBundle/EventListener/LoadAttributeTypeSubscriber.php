<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AttributeBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * @author Laurent Paganin-Gioanni <l.paganin@algo-factory.com>
 */
class LoadAttributeTypeSubscriber implements EventSubscriber
{
    /**
     * @var ServiceRegistryInterface
     */
    private $attributeTypesRegistry;

    /**
     * @param ServiceRegistryInterface $attributeTypesRegistry
     */
    public function __construct(ServiceRegistryInterface $attributeTypesRegistry)
    {
        $this->attributeTypesRegistry = $attributeTypesRegistry;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'postLoad',
        ];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $item = $args->getEntity();

        if (!$this->supports($item)) {
            return;
        }

        $item->setAttributeType($this->attributeTypesRegistry->get($item->getType()));
    }

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    protected function supports($entity)
    {
        return $entity instanceof AttributeInterface;
    }
}
