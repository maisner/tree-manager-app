<?php

declare(strict_types = 1);

namespace App\Model\Tree\Storages;

use App\Model\Tree\Node;

interface ITreeStorage
{

    /**
     * Finds all trees nodes.
     *
     * @return array<\App\Model\Tree\Node>
     */
    public function findAllTreesNodes(): array;

    /**
     * Finds all tree nodes.
     *
     * @return array<\App\Model\Tree\Node>
     */
    public function findAllTreeNodes(int $rootId): array;

    /**
     * Inserts a new node into the tree structure.
     *
     * @return int The ID of the newly inserted node.
     */
    public function insertNode(int $rootId, ?int $parentId, int $lft, int $rgt, string $name, int $level): int;

    /**
     * Inserts a new root node into the tree structure.
     *
     * @return int The ID of the newly inserted root node.
     */
    public function insertRootNode(string $name): int;

    /**
     * Update node in the tree structure.
     */
    public function updateNode(int $id, string $name): void;

    /**
     * Get node by id.
     */
    public function getNode(int $id): Node;

    /**
     * Shifts the right edges (rgt) of tree nodes up by a specified amount.
     *
     * @param int $minRgt The rgt value above which nodes will be shifted (exclusive).
     * @param int $shiftAmount The amount to shift the nodes by.
     */
    public function shiftRightEdgesUp(int $rootId, int $minRgt, int $shiftAmount): void;

    /**
     * Shifts the right edges (rgt) of tree nodes down by a specified amount.
     *
     * @param int $greaterThanRgt The rgt value above which nodes will be shifted (exclusive).
     * @param int $shiftAmount The amount to shift the nodes by.
     */
    public function shiftRightEdgesDown(int $rootId, int $greaterThanRgt, int $shiftAmount): void;

    /**
     * Shifts the left edges (lft) of tree nodes up by a specified amount.
     *
     * @param int $greaterThanLft The lft value above which nodes will be shifted (exclusive).
     * @param int $shiftAmount The amount to shift the nodes by.
     */
    public function shiftLeftEdgesUp(int $rootId, int $greaterThanLft, int $shiftAmount): void;

    /**
     * Shifts the left edges (lft) of tree nodes down by a specified amount.
     *
     * @param int $greaterThanLft The lft value above which nodes will be shifted (exclusive).
     * @param int $shiftAmount The amount to shift the nodes by.
     */
    public function shiftLeftEdgesDown(int $rootId, int $greaterThanLft, int $shiftAmount): void;

    /**
     * Deletes nodes in the tree structure within the specified lft and rgt range.
     *
     * @param int $lft The starting lft value of the range.
     * @param int $rgt The ending rgt value of the range.
     */
    public function deleteNodesInRange(int $rootId, int $lft, int $rgt): void;

}