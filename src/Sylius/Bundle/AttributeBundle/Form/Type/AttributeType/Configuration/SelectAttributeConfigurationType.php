<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AttributeBundle\Form\Type\AttributeType\Configuration;

use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Laurent Paganin-Gioanni <l.paganin@algo-factory.com>
 */
class SelectAttributeConfigurationType extends AbstractType
{
    /**
     * @var array
     */
    protected $attributeTypes;

    /**
     * @param array $attributeTypes
     */
    public function __construct(array $attributeTypes)
    {
        unset($attributeTypes[SelectAttributeType::TYPE]);
        $this->attributeTypes = $attributeTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('multiple', 'checkbox', [
                'label' => 'sylius.attribute_type_configuration.select.multiple',
            ])
            ->add('type', 'choice', [
                'label' => 'sylius.attribute_type_configuration.select.type',
                'choices' => $this->attributeTypes,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $configuration = $event->getData();
            $form = $event->getForm();
            $type = (isset($configuration['type'])) ? $configuration['type'] : TextAttributeType::TYPE;
            $formType = sprintf('sylius_attribute_type_%s', $type);

            try {
                $configurationType = sprintf('sylius_attribute_type_configuration_%s', $type);
                $form->add('configuration', $configurationType, []);
            } catch (\Exception $e) {}

            $form
                ->add('options', 'collection', [
                    'type' => $formType,
                    'options' => [
                        'configuration' => (isset($configuration['configuration'])) ? $configuration['configuration'] : []
                    ],
                    'label' => 'sylius.form.attribute_type_configuration.select.values',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'cascade_validation' => true,
                ])
            ;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_attribute_type_configuration_select';
    }
}
