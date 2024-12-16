<?php

declare(strict_types = 1);

namespace App\Model\Tree\Storages;

use App\Model\Tree\Node;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Webmozart\Assert\Assert;

readonly final class DatabaseTreeStorage implements ITreeStorage
{

    public function __construct(public string $table, private Explorer $database)
    {
    }

    public function findAllTreesNodes(): array
    {
        $rows = $this->database->table($this->table)
            ->order('root_id, lft')
            ->fetchAll();

        $result = [];

        foreach ($rows as $row) {
            $result[] = Node::fromActiveRow($row);
        }

        return $result;
    }

    public function findAllTreeNodes(int $rootId): array
    {
        $rows = $this->database->table($this->table)
            ->where('root_id', $rootId)
            ->order('lft')
            ->fetchAll();

        $result = [];

        foreach ($rows as $row) {
            $result[] = Node::fromActiveRow($row);
        }

        return $result;
    }

    public function insertNode(int $rootId, ?int $parentId, int $lft, int $rgt, string $name, int $level): int
    {
        $result = $this->database->table($this->table)->insert([
            'level' => $level,
            'lft' => $lft,
            'name' => $name,
            'parent_id' => $parentId,
            'rgt' => $rgt,
            'root_id' => $rootId,
        ]);

        Assert::isInstanceOf($result, ActiveRow::class);
        $id = $result->offsetGet('id');
        Assert::numeric($id);

        return (int)$id;
    }

    public function insertRootNode(string $name): int
    {
        $result = $this->database->table($this->table)->insert([
            'level' => 0,
            'lft' => 1,
            'name' => $name,
            'parent_id' => null,
            'rgt' => 2,
            'root_id' => null,
        ]);

        Assert::isInstanceOf($result, ActiveRow::class);
        $id = $result->offsetGet('id');
        Assert::numeric($id);

        $this->database->table($this->table)->get($id)?->update(['root_id' => $id]);

        return (int)$id;
    }

    public function getNode(int $id): Node
    {
        $row = $this->database->table($this->table)->get($id);

        if ($row === null) {
            throw new \App\Model\Exceptions\NotFoundException('Node not found');
        }

        return Node::fromActiveRow($row);
    }

    public function updateNode(int $id, string $name): void
    {
        $this->database->table($this->table)->where('id', $id)->update(['name' => $name]);
    }

    public function shiftRightEdgesUp(int $rootId, int $minRgt, int $shiftAmount): void
    {
        $this->database->table($this->table)
            ->where('rgt >= ?', $minRgt)
            ->where('root_id', $rootId)
            ->update(['rgt+=' => $shiftAmount]);
    }

    public function shiftRightEdgesDown(int $rootId, int $greaterThanRgt, int $shiftAmount): void
    {
        $this->database->table($this->table)
            ->where('rgt > ?', $greaterThanRgt)
            ->where('root_id', $rootId)
            ->update(['rgt-=' => $shiftAmount]);
    }

    public function shiftLeftEdgesUp(int $rootId, int $greaterThanLft, int $shiftAmount): void
    {
        $this->database->table($this->table)
            ->where('lft > ?', $greaterThanLft)
            ->where('root_id', $rootId)
            ->update(['lft+=' => $shiftAmount]);
    }

    public function shiftLeftEdgesDown(int $rootId, int $greaterThanLft, int $shiftAmount): void
    {
        $this->database->table($this->table)
            ->where('lft > ?', $greaterThanLft)
            ->where('root_id', $rootId)
            ->update(['lft-=' => $shiftAmount]);
    }

    public function deleteNodesInRange(int $rootId, int $lft, int $rgt): void
    {
        $this->database->table($this->table)
            ->where('lft BETWEEN ? AND ?', $lft, $rgt)
            ->where('root_id', $rootId)
            ->delete();
    }

}