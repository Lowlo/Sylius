<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\AttributeType;

use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @author Laurent Paganin-Gioanni <l.paganin@algo-factory.com>
 */
class SelectAttributeType extends AbstractAttributeType
{
    const TYPE = 'select';

    /**
     * {@inheritdoc}
     */
    public function getStorageType()
    {
        if (!is_null($this->getAttribute())) {
            $configuration = $this->getAttribute()->getConfiguration();

            if (isset($configuration['multiple']) && !$configuration['multiple']) {
                return AttributeValueInterface::STORAGE_INTEGER;
            }
        }

        return AttributeValueInterface::STORAGE_JSON;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(AttributeValueInterface $attributeValue, ExecutionContextInterface $context, array $configuration)
    {
        if (!isset($configuration['multiple'])) {
            return;
        }

        $value = $attributeValue->getValue();

        foreach ($this->getValidationErrors($context, $value, $configuration) as $error) {
            $context
                ->buildViolation($error->getMessage())
                ->atPath('value')
                ->addViolation()
            ;
        }
    }

    /**
     * @param ExecutionContextInterface $context
     * @param string $value
     * @param array $validationConfiguration
     *
     * @return ConstraintViolationListInterface
     */
    private function getValidationErrors(ExecutionContextInterface $context, $value, array $validationConfiguration)
    {
        $validator = $context->getValidator();

        if ($validationConfiguration['multiple']) {
            return $validator->validate(
                $value,
                new All([
                    new Type([
                        'type' => 'int',
                    ])
                ])
            );
        }

        return $validator->validate(
            $value,
            new Type([
                'type' => 'int',
            ])
        );
    }
}
