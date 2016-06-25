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

use Sylius\Component\Attribute\Model\AttributeInterface;

/**
 * @author Laurent Paganin-Gioanni <l.paganin@algo-factory.com>
 */
abstract class AbstractAttributeType implements AttributeTypeInterface
{
    /**
     * @var AttributeInterface
     */
    protected $attribute;

    /**
     * @return AttributeInterface
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function setAttribute(AttributeInterface $attribute)
    {
        $this->attribute = $attribute;
    }
}
