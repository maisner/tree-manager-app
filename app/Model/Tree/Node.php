<?php

declare(strict_types=1);

namespace App\Model\Tree;

use Nette\Database\Table\ActiveRow;
use Webmozart\Assert\Assert;

readonly class Node
{

    public function __construct(
        public int $id,
        public string $name,
        public ?int $parentId,
        public int $lft,
        public int $rgt,
        public int $level,
        public int $rootId,
    )
    {
    }

    public function isRoot(): bool
    {
        return $this->parentId === null;
    }

    public static function fromActiveRow(ActiveRow $row): self
    {
        $id = $row->offsetGet('id');
        Assert::integer($id);

        $name = $row->offsetGet('name');
        Assert::string($name);

        $parentId = $row->offsetGet('parent_id');
        Assert::nullOrInteger($parentId);

        $lft = $row->offsetGet('lft');
        Assert::integer($lft);

        $rgt = $row->offsetGet('rgt');
        Assert::integer($rgt);

        $level = $row->offsetGet('level');
        Assert::integer($level);

        $rootId = $row->offsetGet('root_id');
        Assert::integer($rootId);

        return new self(
            $id,
            $name,
            $parentId,
            $lft,
            $rgt,
            $level,
            $rootId,
        );
    }

}