<?php

declare(strict_types = 1);

namespace App\Model\Category\Facades;

use App\Model\Tree\ITreeManager;
use App\Model\Tree\Mappers\INodeResponseMapper;

/** @phpstan-import-type NodeResponseArray from \App\Model\Tree\Mappers\INodeResponseMapper */
final readonly class CategoryTreeFacade
{

    public function __construct(private ITreeManager $treeManager, private INodeResponseMapper $nodeResponseMapper)
    {
    }

    /** @return array<NodeResponseArray> */
    public function getTrees(): array
    {
        $treeNodes = $this->treeManager->getTrees();

        return $this->nodeResponseMapper->mapCollection($treeNodes);
    }

    /** @return array<NodeResponseArray> */
    public function getTree(int $rootId): array
    {
        $treeNodes = $this->treeManager->getTree($rootId);

        return $this->nodeResponseMapper->mapCollection($treeNodes);
    }

    /** @return NodeResponseArray */
    public function addNode(string $name, int $parentId): array
    {
        $node = $this->treeManager->addNode($name, $parentId);

        return $this->nodeResponseMapper->map($node);
    }

    /** @return NodeResponseArray */
    public function addRootNode(string $name): array
    {
        $node = $this->treeManager->addRootNode($name);

        return $this->nodeResponseMapper->map($node);
    }

    public function updateNode(int $id, string $name): void
    {
        $this->treeManager->updateNode($id, $name);
    }

    public function deleteNode(int $id): void
    {
        $this->treeManager->deleteNode($id);
    }

}