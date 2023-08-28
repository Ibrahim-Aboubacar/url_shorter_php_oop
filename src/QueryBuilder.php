<?php

namespace Source;

use PDO;

trait QueryBuilder
{
    protected array $selects = ['*'];
    protected string $from;
    protected array $wheres = [];
    protected array $orderBys = [];
    protected $alowedDir = ['ASC', 'DESC'];
    protected int $limit = 0;
    protected int $offset = 0;
    protected int $page = 0;
    protected array $params = [];

    public function from(string $table, string $alias = '')
    {
        $this->from = trim("$table $alias");
        return $this;
    }

    public function toSQL(): string
    {
        // $sql = "SELECT ";
        $sql = $this->getSelects();
        $sql .= " FROM ";
        $sql .= $this->from;
        if ($this->wheres) {
            $sql .= ' ' . $this->getWheres();
        }
        if ($this->orderBys) {
            $sql .= ' ' . $this->getOrderBys();
        }
        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;

            if ($this->page) {
                $offset = $this->limit * ($this->page - 1);
                $sql .= ' OFFSET ' . $offset;
            } else {
                if ($this->offset) {
                    $sql .= ' OFFSET ' . $this->offset;
                }
            }
        }

        return $sql;
    }

    public function orderBy($column, $dir)
    {
        $dir = strtoupper($dir);
        if (!in_array($dir, $this->alowedDir)) $dir = '';
        $this->orderBys[] = trim("$column $dir");
        return $this;
    }

    private function getOrderBys(): string
    {
        return 'ORDER BY ' . implode(', ', $this->orderBys);
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function page(int $page)
    {
        $this->page = $page;
        return $this;
    }

    public function where(string $whereClause)
    {
        $this->wheres[] = $whereClause;
        return $this;
    }

    private function getWheres(): string
    {
        return 'WHERE ' . implode(', ', $this->wheres);
    }

    public function setParam($param, $value)
    {
        $this->params[$param] =  $value;
        return $this;
    }

    public function select(string|array ...$columns)
    {
        if (is_array($columns[0])) {
            if ($this->selects === ['*']) {
                $this->selects = $columns[0];
            } else {
                $this->selects = array_merge($this->selects, array_diff($columns[0], $this->selects));
            }
        } else {
            if ($this->selects === ['*']) {
                $this->selects = $columns;
            } else {
                $this->selects = array_merge($this->selects, array_diff($columns, $this->selects));
            }
        }
        return $this;
    }

    private function getSelects(): string
    {
        return 'SELECT ' . implode(', ', $this->selects);
    }

    public function fetch($column): string|null
    {
        $pdo = App::$pdo;
        $sql = $this->toSQL();
        $statment = $pdo->prepare($sql);
        $statment->execute($this->params);
        $res = $statment->fetch(PDO::FETCH_ASSOC);
        if ($res === false) {
            return null;
        }
        return $res[$column] ?? null;
    }

    public function count(): int
    {
        $pdo = App::$pdo;
        return (int)(clone $this)->select('COUNT(id) count')->fetch($pdo, 'count');
    }
}
