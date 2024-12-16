<?php

declare(strict_types = 1);

namespace App\Model\Tree\Mappers;

use App\Model\Tree\Node;

/** @phpstan-type NodeResponseArray array{id: int, name: string, parent_id: ?int, lft: int, rgt: int, level: int} */
interface INodeResponseMapper
{

    /** @return NodeResponseArray */
    public function map(Node $node): array;

    /**
     * @param array<\App\Model\Tree\Node> $nodes
     * @return array<NodeResponseArray>
     */
    public function mapCollection(array $nodes): array;

}