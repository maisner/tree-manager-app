<?php

declare(strict_types=1);

namespace App\Model\Tree;

use App\Model\Tree\Storages\ITreeStorage;

readonly class TreeManager implements ITreeManager
{

    public function __construct(protected ITreeStorage $treeStorage)
    {
    }


    public function getTrees(): array
    {
        return $this->treeStorage->findAllTreesNodes();
    }

    public function getTree(int $rootId): array
    {
        return $this->treeStorage->findAllTreeNodes($rootId);
    }

    public function addRootNode(string $name): Node
    {
        $nodeId = $this->treeStorage->insertRootNode($name);

        return $this->treeStorage->getNode($nodeId);
    }

    public function addNode(string $name, int $parentId): Node
    {
        $parent = $this->treeStorage->getNode($parentId);
        $this->treeStorage->shiftRightEdgesUp($parent->rootId, $parent->rgt, 2);
        $this->treeStorage->shiftLeftEdgesUp($parent->rootId, $parent->rgt, 2);
        $nodeId = $this->treeStorage->insertNode(
            $parent->rootId,
            $parent->id,
            $parent->rgt,
            $parent->rgt + 1,
            $name,
            $parent->level + 1
        );

        return $this->treeStorage->getNode($nodeId);
    }

    public function deleteNode(int $id): void
    {
        $node = $this->treeStorage->getNode($id);
        $size = $node->rgt - $node->lft + 1;
        $this->treeStorage->deleteNodesInRange($node->rootId, $node->lft, $node->rgt);
        $this->treeStorage->shiftRightEdgesDown($node->rootId, $node->rgt, $size);
        $this->treeStorage->shiftLeftEdgesDown($node->rootId, $node->rgt, $size);
    }

    public function updateNode(int $id, string $name): void
    {
        $this->treeStorage->updateNode($id, $name);
    }


}