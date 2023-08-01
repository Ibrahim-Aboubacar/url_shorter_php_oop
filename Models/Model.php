<?php

namespace Models;

use PDOStatement;
use Source\Constant;
use Source\Dump;

abstract class Model
{
    protected static \PDO $pdo;
    protected string $table;
    protected $id;
    protected $initialized = false;
    protected $columns = [];

    public function __construct($id = null)
    {
        try {
            static::$pdo = new \PDO(
                'mysql:dbname=' . Constant::DB_NAME . ';host=' . Constant::DB_HOST,
                Constant::DB_USERNAME,
                Constant::DB_PASSWORD,
                [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }

        $this->table = strtolower(explode('\\', get_class($this))[1]) . 's';

        $this->id = $id;

        if ($id) {
            $this->init($id);
        }
    }

    public function all($addon = ''): array
    {
        $statement = $this->getPDO()->query("SELECT * FROM {$this->table} " . $addon);

        return $statement->fetchAll();
    }

    public function where(string $column, string|int $value)
    {
        $statement = $this->getPDO()
            ->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");

        $statement->execute([$value]);

        return $statement->fetchAll();
    }

    public function update(): bool
    {
        if (!count($this->columns)) return false;
        $table = [];

        $query = "UPDATE {$this->table} SET";

        foreach ($this->columns as $column) {
            $query .= " $column=?,";
            $var = "get" . ucfirst(strtolower($column));
            $table[] =  $this->$var();
        }

        $query = trim($query, ',') . " WHERE id=" . $this->getId() . ";";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->execute($table);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete(): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id=?;";

        try {
            $statement = $this->getPDO()->prepare($query);
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

        $query = "INSERT INTO {$this->table} (";
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
            $statement = $this->getPDO()->prepare($query);
            $statement->execute($table);

            // RECUPERER L'ID
            $statement = $this->getPDO()->prepare("SELECT LAST_INSERT_ID() id FROM {$this->table};");
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
        $table = $this->where('id', $id);

        if (count($table)) return $table[0];

        return [];
    }

    protected function getPDO(): \PDO
    {
        return static::$pdo;
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
    public function setId($id)
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
}
