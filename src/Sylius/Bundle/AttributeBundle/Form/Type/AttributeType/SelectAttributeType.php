<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AttributeBundle\Form\Type\AttributeType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @author Laurent Paganin-Gioanni <l.paganin@algo-factory.com>
 */
class SelectAttributeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('configuration')
            ->setNormalizer('choices', function(Options $options){
                return $options['configuration']['options'];
            })
            ->setNormalizer('multiple', function(Options $options){
                return $options['configuration']['multiple'];
            })
            ->setNormalizer('constraints', function(Options $options){
                if ($options['configuration']['multiple']) {
                    return new All([
                        new Type([
                            'type' => 'int',
                        ])
                    ]);
                }

                return new Type([
                    'type' => 'int',
                ]);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_attribute_type_select';
    }
}
