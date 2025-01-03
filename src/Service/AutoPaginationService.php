<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class AutoPaginationService
{
    public const ITEMS_PER_PAGE = 12;
    public function paginate(Request $request, QueryBuilder $queryBuilder, int $limit = this::ITEMS_PER_PAGE): array
    {
        $page = $request->query->get('page', '1');

        $paginator = new Paginator($queryBuilder);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        
        $totalItems = count($paginator);
        $maxPages = ceil($totalItems / $limit);

        return [
            "items" => iterator_to_array($paginator),
            "page" => $page,
            "totalItems" => $totalItems,
            "maxPages" => $maxPages,
            "hasNextPage" => $page < $maxPages,
            "hasPreviousPage" => $page > 1,
        ];
    }
}
