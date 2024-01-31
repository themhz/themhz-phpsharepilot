<?php

namespace SharePilotV2\Components;

class Query
{
    private $model;
    private $type;
    private $params = [];
    private $conditions = [];
    private $orderBy = [];
    private $groupBy = [];
    private $selectFields = ['*'];
    private $joins =[];

    public function __construct(Model $model, $type)
    {
        $this->model = $model;
        $this->type = $type;
    }
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }
    public function fields(...$fields)
    {
        $this->selectFields = $fields;
        return $this;
    }
    public function where($field, $operator, $value)
    {
        $this->conditions[] = [$field, $operator, $value, "AND"];

        return $this;
    }
    public function orWhere($field, $operator, $value)
    {
        $this->conditions[] = [$field, $operator, $value, 'OR'];
        return $this;
    }
    public function join($type, $table, $on)
    {
        $this->joins[] = [$type, $table, $on];
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->orderBy[] = [$field, $direction];
        return $this;
    }
    public function groupBy(...$fields)
    {
        $this->groupBy = $fields;
        return $this;
    }
    private function getOrderByClause()
    {
        if (empty($this->orderBy)) {
            return '';
        }

        $parts = [];
        foreach ($this->orderBy as [$field, $direction]) {
            $parts[] = "$field $direction";
        }

        return 'ORDER BY ' . implode(', ', $parts);
    }
    private function getGroupByClause()
    {
        if (empty($this->groupBy)) {
            return '';
        }

        return 'GROUP BY ' . implode(', ', $this->groupBy);
    }
    public function execute()
    {
        switch ($this->type) {
            case 'select':
                return $this->performSelect();
            case 'update':
                return $this->performUpdate();
            case 'delete':
                return $this->performDelete();
        }
    }
    private function performUpdate()
    {
        $table = $this->model->getTable();
        $setClause = "";
        $whereClause = "";
        $conditions = "";
        $values = [];

        foreach ($this->params as $field => $value) {
            $setClause .= ($setClause !== "" ? ", " : "") . "$field = :$field";
            $values[":$field"] = $value;
        }



        foreach ($this->conditions as $i => $condition) {
            list($field, $operator, $value, $andOr) = $condition;

            $conditions .= ($conditions !== "" ? " $andOr " : "") . "$field $operator :where$i";
            $values[":where$i"] = $value;
        }
        $whereClause = ($conditions !== "") ? "WHERE $conditions" : "";
        $sql = "UPDATE $table SET $setClause $whereClause";

        try {
            $stmt = Model::getPdo()->prepare($sql);

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo 'Update failed: ' . $e->getMessage();
            return false;
        }
    }
    private function performDelete()
    {
        $table = $this->model->getTable();
        $whereClause = "";
        $conditions = "";
        $values = [];

        foreach ($this->conditions as $i => $condition) {
            list($field, $operator, $value, $andOr) = $condition;

            $conditions .= ($conditions !== "" ? " $andOr " : "") . "$field $operator :where$i";
            $values[":where$i"] = $value;
        }
        $whereClause = ($conditions !== "") ? "WHERE $conditions" : "";

        try {
            $stmt = Model::getPdo()->prepare("DELETE FROM $table $whereClause");

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo 'Delete failed: ' . $e->getMessage();
            return false;
        }
    }
    private function performSelect()
    {
        $table = $this->model->getTable();
        $conditions = "";
        $values = [];
        $fields = implode(', ', $this->selectFields); // added this line

        foreach ($this->conditions as $i => $condition) {

            list($field, $operator, $value, $andOr) = $condition;


            $conditions .= ($conditions !== "" ? " $andOr " : "") . "$field $operator :where$i";
            $values[":where$i"] = $value;
        }
        $whereClause = ($conditions !== "") ? "WHERE $conditions" : "";

        try {
            $orderBy = $this->getOrderByClause();
            $groupBy = $this->getGroupByClause();

            $joins = '';
            foreach ($this->joins as $join) {
                list($type, $jtable, $on) = $join;
                $joins .= " $type JOIN $jtable ON $on";
            }

            $stmt = Model::getPdo()->prepare("SELECT $fields FROM $table $joins $whereClause $groupBy $orderBy");

            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Select failed: ' . $e->getMessage();
            return false;
        }
    }

}
