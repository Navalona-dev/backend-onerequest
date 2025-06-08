<?php

namespace App\ApiResource\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Metadata\Operation;

class RoleFilter extends AbstractFilter
{
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        if ($property !== 'role') {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $parameterName = $queryNameGenerator->generateParameterName('role');

        $queryBuilder
            ->andWhere(":$parameterName MEMBER OF $alias.roles")
            ->setParameter($parameterName, $value);
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'role' => [
                'property' => 'role',
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Filtrer par rôle (ex: ROLE_USER)',
                    'name' => 'Role',
                    'type' => 'string',
                ],
            ],
        ];
    }
}
