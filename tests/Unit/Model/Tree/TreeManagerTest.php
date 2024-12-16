<?php


namespace Unit\Model\Tree;


use App\Model\Tree\Node;
use App\Model\Tree\Storages\ITreeStorage;
use App\Model\Tree\TreeManager;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;

final class TreeManagerTest extends Unit
{

    public function testGetTrees(): void
    {
        $node = new Node(1, 'Root node', null, 1, 2, 0, 1);
        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'findAllTreesNodes' => Expected::once([$node]),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $result = $treeManager->getTrees();

        self::assertSame([$node], $result);
    }

    public function testGetTree(): void
    {
        $node = new Node(1, 'Root node', null, 1, 2, 0, 1);
        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'findAllTreeNodes' => Expected::once([$node]),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $result = $treeManager->getTree(1);

        self::assertSame([$node], $result);
    }

    public function testAddRootNode(): void
    {
        $newNode = new Node(1, 'New node', null, 1, 2, 0, 1);

        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'insertRootNode' => Expected::once(1),
            'getNode' => Expected::once($newNode),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $result = $treeManager->addRootNode('Root Node');

        self::assertSame($newNode, $result);
    }

    public function testAddNode(): void
    {
        $parentNode = new Node(1, 'Root node', null, 1, 2, 0, 1);
        $newNode = new Node(2, 'New node', 1, 2, 3, 1, 1);

        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'getNode' => Stub::consecutive($parentNode, $newNode),
            'shiftRightEdgesUp' => Expected::once(static function (int $rootId, int $minRgt, int $shiftAmount) use ($parentNode) {
                self::assertSame($parentNode->rootId, $rootId);
                self::assertSame($parentNode->rgt, $minRgt);
                self::assertSame(2, $shiftAmount);
            }),
            'shiftLeftEdgesUp' => Expected::once(static function (int $rootId, int $greaterThanLft, int $shiftAmount) use ($parentNode) {
                self::assertSame($parentNode->rootId, $rootId);
                self::assertSame($parentNode->rgt, $greaterThanLft);
                self::assertSame(2, $shiftAmount);
            }),
            'insertNode' => Expected::once(static function (int $rootId, ?int $parentId, int $lft, int $rgt, string $name, int $level) use ($parentNode) {
                self::assertSame($parentNode->rootId, $rootId);
                self::assertSame($parentNode->id, $parentId);
                self::assertSame($parentNode->rgt, $lft);
                self::assertSame($parentNode->rgt + 1, $rgt);
                self::assertSame('Child Node', $name);
                self::assertSame($parentNode->level + 1, $level);

                return 2;
            }),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $result = $treeManager->addNode('Child Node', $parentNode->id);

        self::assertSame($newNode, $result);
    }

    public function testDeleteNode(): void
    {
        $node = new Node(1, 'Root node', null, 1, 2, 0, 1);

        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'getNode' => $node,
            'deleteNodesInRange' => Expected::once(static function (int $rootId, int $lft, int $rgt) use ($node) {
                self::assertSame($node->rootId, $rootId);
                self::assertSame($node->lft, $lft);
                self::assertSame($node->rgt, $rgt);
            }),
            'shiftRightEdgesDown' => Expected::once(static function (int $rootId, int $greaterThanRgt, int $shiftAmount) use ($node) {
                self::assertSame($node->rootId, $rootId);
                self::assertSame($node->rgt, $greaterThanRgt);
                self::assertSame(2, $shiftAmount);
            }),
            'shiftLeftEdgesDown' => Expected::once(static function (int $rootId, int $greaterThanLft, int $shiftAmount) use ($node) {
                self::assertSame($node->rootId, $rootId);
                self::assertSame($node->rgt, $greaterThanLft);
                self::assertSame(2, $shiftAmount);
            }),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $treeManager->deleteNode($node->id);
    }

    public function testUpdateNode(): void
    {
        $treeStorage = $this->makeEmpty(ITreeStorage::class, [
            'updateNode' => Expected::once(),
        ]);

        $treeManager = new TreeManager($treeStorage);

        $treeManager->updateNode(1, 'Updated Name');
    }
}
