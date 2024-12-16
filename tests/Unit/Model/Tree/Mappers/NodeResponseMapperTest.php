<?php

declare(strict_types=1);

namespace Unit\Model\Tree\Mappers;

use App\Model\Tree\Mappers\NodeResponseMapper;
use App\Model\Tree\Node;
use Codeception\Test\Unit;

final class NodeResponseMapperTest extends Unit
{

    public function testMap(): void
    {
        $node = new Node(1, 'Root node', null, 1, 2, 0, 1);

        $expected = [
            'id' => 1,
            'name' => 'Root node',
            'parent_id' => null,
            'lft' => 1,
            'rgt' => 2,
            'level' => 0,
        ];

        $mapper = new NodeResponseMapper();

        $result = $mapper->map($node);

        self::assertSame($expected, $result);
    }

    public function testMapCollection(): void
    {
        $node = new Node(1, 'Root node', null, 1, 4, 0, 1);
        $childNode = new Node(2, 'Child node', 1, 2, 3, 1, 1);

        $nodes = [
            $node,
            $childNode,
        ];

        $expected = [
            [
                'id' => 1,
                'name' => 'Root node',
                'parent_id' => null,
                'lft' => 1,
                'rgt' => 4,
                'level' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Child node',
                'parent_id' => 1,
                'lft' => 2,
                'rgt' => 3,
                'level' => 1,
            ],
        ];

        $mapper = new NodeResponseMapper();

        $result = $mapper->mapCollection($nodes);

        self::assertSame($expected, $result);
    }

}