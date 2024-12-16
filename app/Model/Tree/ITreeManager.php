<?php

declare(strict_types = 1);

namespace App\Model\Tree;

interface ITreeManager
{

    /** @return array<\App\Model\Tree\Node> */
    public function getTrees(): array;

    /** @return array<\App\Model\Tree\Node> */
    public function getTree(int $rootId): array;

    public function addRootNode(string $name): Node;

    public function addNode(string $name, int $parentId): Node;

    public function deleteNode(int $id): void;

    public function updateNode(int $id, string $name): void;

}