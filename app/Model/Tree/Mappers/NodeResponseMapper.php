<?php

declare(strict_types = 1);

namespace App\Model\Tree\Mappers;

use App\Model\Tree\Node;

readonly final class NodeResponseMapper implements INodeResponseMapper
{

    public function map(Node $node): array
    {
        return [
            'id' => $node->id,
            'name' => $node->name,
            'parent_id' => $node->parentId,
            'lft' => $node->lft,
            'rgt' => $node->rgt,
            'level' => $node->level,
        ];
    }

    public function mapCollection(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            $result[] = $this->map($node);
        }

        return $result;
    }

}