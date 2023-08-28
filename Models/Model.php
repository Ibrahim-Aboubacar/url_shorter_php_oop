<?php

namespace Models;

use Exception;
use PDO;
use Source\App;
use Source\Dump;
use Source\QueryBuilder;

abstract class Model
{
    // use QueryBuilder;
    protected string $table = '';
    public $id;
    protected $initialized = false;
    protected $columns = [];
    // QUERY BUILDER
    protected array $selects = ['*'];
    protected string $from;
    protected array $wheres = [];
    protected array $orderBys = [];
    protected $alowedDir = ['ASC', 'DESC'];
    protected int $limit = 0;
    protected int $offset = 0;
    protected int $page = 0;
    protected array $params = [];

    public function __construct($id = null)
    {
        $this->id = $id;

        if ($id) {
            $this->init($id);
        }
    }

    public function all(): array
    {
        $statement = App::getPDO()->query("SELECT * FROM {$this->getTableName()} ");
        return $statement->fetchAll();
    }

    // public function where(string $column, string|int $value)
    // {
    //     $statement = App::getPDO()
    //         ->prepare("SELECT * FROM {$this->getTableName()} WHERE {$column} = ?");

    //     $statement->execute([$value]);

    //     return $statement->fetchAll();
    // }

    public function update(): bool
    {
        if (!count($this->columns)) return false;
        $table = [];

        $query = "UPDATE {$this->getTableName()} SET";

        foreach ($this->columns as $column) {
            if ($column != 'id') {
                $query .= " $column=?,";
                $var = "get" . ucfirst(strtolower($column));
                $table[] =  $this->$var();
            }
        }

        $query = trim($query, ',') . " WHERE id=" . $this->getId() . ";";

        try {
            $statement = App::getPDO()->prepare($query);
            $statement->execute($table);
            return true;
        } catch (\PDOException $e) {
            Dump::log($e->getMessage());
            return false;
        }
    }

    public function delete(): bool
    {
        $query = "DELETE FROM {$this->getTableName()} WHERE id=?;";

        try {
            $statement = App::getPDO()->prepare($query);
            $statement->execute([$this->getId()]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function save($columns, $values): bool
    {

        if (count($columns) !== count($values)) return false;
        $table = [];

        $query = "INSERT INTO {$this->getTableName()} (";
        for ($i = 0; $i < count($columns); $i++) {
            $end = $i + 1 === count($columns) ? '' : ',';

            $query .= $columns[$i] . $end;
        }

        $query .= ') VALUES (';

        for ($i = 0; $i < count($columns); $i++) {
            $end = $i + 1 === count($columns) ? '' : ',';

            $query .= '?' . $end;

            $table[] = $values[$i];
        }

        $query .= ') ;';

        try {
            $statement = App::getPDO()->prepare($query);
            $statement->execute($table);

            // RECUPERER L'ID
            $statement = App::getPDO()->prepare("SELECT LAST_INSERT_ID() id FROM {$this->getTableName()};");
            $statement->execute();
            $id = $statement->fetch()->id;

            $this->init($id);

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function find($id)
    {
        return $this->where("id = :id")->setParam('id', $id)->fetch();
    }

    public function init($id)
    {
        return $this;
    }

    /**
     * Get the value of initialized
     */
    public function getInitialized()
    {
        return $this->initialized;
    }

    // public function __get($key)
    // {
    //     return $this->$key . ' ACCC';
    //     if (isset($this->$$key)) {
    //     }
    // }
    // public function __set($key, $value)
    // {
    //     return $this->$key = $value . ' __set($key $value)';
    //     // if (isset($this->$$key)) {
    //     // }
    // }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    protected function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of columns
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set the value of columns
     *
     * @return  self
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    // ////////////////////////////////////////////////////////////////////
    // the query builder


    // protected function from(string $table, string $alias = '')
    // {
    //     $this->from = trim("$table $alias");
    //     return $this;
    // }

    public function getTableName()
    {
        if (!$this->table) {
            $this->table = strtolower(explode('\\', get_class($this))[1]) . 's';
        }
        return $this->table;
    }

    public function toSQL(): string
    {

        $sql = $this->getSelects();
        $sql .= " FROM ";
        $sql .= $this->getTableName();
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

    public function resetSQL()
    {
        $this->selects = ['*'];
        $this->wheres = [];
        $this->orderBys = [];
        $this->limit = 0;
        $this->offset = 0;
        $this->page = 0;
        $this->params = [];
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

    public function getParams()
    {
        return $this->params;
    }

    public function select(string|array ...$columns)
    {
        if (empty($columns)) {
            return $this;
        } else {
            if (is_array($columns[0])) {
                $columns = $columns[0];
            }
            if ($this->selects === ['*']) {
                // if (!$this->columsValid($columns)) {
                //     throw new Exception("Error: Columns passed not found");
                // }
                $this->selects = $columns;
            } else {
                $this->selects = array_merge($this->selects, array_diff($columns, $this->selects));
            }
        }
        return $this;
    }

    private function columsValid(array $columns): bool
    {
        return empty(array_diff($columns, $this->getColumns()));
    }

    private function getSelects(): string
    {
        return 'SELECT ' . implode(', ', $this->selects);
    }

    public function fetchAll(Bool $loadClass = false): array|null
    {
        $pdo = App::getPDO();
        $statment = $pdo->prepare($this->toSQL());
        $statment->execute($this->params);
        if ($loadClass) {
            $res = $statment->fetchAll(PDO::FETCH_CLASS, get_called_class());
        } else {
            $res = $statment->fetchAll();
        }
        if ($res === false) {
            return null;
        }
        return $res ?? null;
    }
    public function fetch($column = null, Bool $loadClass = false)
    {
        $pdo = App::getPDO();
        $sql = $this->toSQL();
        $statment = $pdo->prepare($sql);
        $statment->execute($this->params);
        if ($loadClass) {
            $res = $statment->fetchAll(PDO::FETCH_CLASS, get_called_class());
            if (count($res)) {
                $res = $res[0];
            }
        } else {
            $res = $statment->fetch();
        }
        if ($res === false) {
            return null;
        }
        if ($column) {
            return $res?->$$column ?? null;
        } else {
            return $res;
        }
    }

    public function count(): int
    {
        $pdo = App::getPDO();
        return (int)(clone $this)->select('COUNT(id) count')->fetch($pdo, 'count');
    }
}
