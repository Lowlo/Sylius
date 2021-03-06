<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\GridBundle\Doctrine\PHPCRODM;

use Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder;
use Pagerfanta\Adapter\DoctrineODMPhpcrAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Parameters;

final class DataSource implements DataSourceInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var ExpressionBuilder
     */
    private $expressionBuilder;

    /**
     * @param QueryBuilder $queryBuilder
     * @param ExpressionBuilder $expressionBuilder
     */
    public function __construct(QueryBuilder $queryBuilder, ExpressionBuilder $expressionBuilder = null)
    {
        $this->queryBuilder = $queryBuilder;
        $this->expressionBuilder = $expressionBuilder ?: new ExpressionBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function restrict($expression, $condition = DataSourceInterface::CONDITION_AND)
    {
        switch ($condition) {
            case DataSourceInterface::CONDITION_AND:
                $parentNode = $this->queryBuilder->andWhere();
                break;
            case DataSourceInterface::CONDITION_OR:
                $parentNode = $this->queryBuilder->orWhere();
                break;
            default:
                throw new \RuntimeException(sprintf(
                    'Unknown restrict condition "%s"',
                    $condition
                ));
        }

        $visitor = new ExpressionVisitor($this->queryBuilder);
        $visitor->dispatch($expression, $parentNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpressionBuilder()
    {
        return $this->expressionBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(Parameters $parameters)
    {
        $orderBy = $this->queryBuilder->orderBy();
        foreach ($this->expressionBuilder->getOrderBys() as $field => $direction) {
            if (is_int($field)) {
                $field = $direction;
                $direction = 'asc';
            }

            // todo: validate direction?
            $direction = strtolower($direction);
            $orderBy->{$direction}()->field(sprintf('%s.%s', Driver::QB_SOURCE_ALIAS, $field));
        }

        $paginator = new Pagerfanta(new DoctrineODMPhpcrAdapter($this->queryBuilder));
        $paginator->setCurrentPage($parameters->get('page', 1));

        // This prevents Pagerfanta from querying database from a template
        $paginator->getCurrentPageResults();

        return $paginator;
    }
}
