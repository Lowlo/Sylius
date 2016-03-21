<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Attribute\AttributeType;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Attribute\AttributeType\AttributeTypeInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class TextAttributeTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Attribute\AttributeType\TextAttributeType');
    }

    function it_implements_attribute_type_interface()
    {
        $this->shouldImplement(AttributeTypeInterface::class);
    }

    function its_storage_type_is_text()
    {
        $this->getStorageType()->shouldReturn('text');
    }

    function its_type_is_text()
    {
        $this->getType()->shouldReturn('text');
    }
}
