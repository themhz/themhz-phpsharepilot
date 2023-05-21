<?php

namespace SharePilotV2\Components;

use SharePilotV2\Libs\Database;

class Model
{

    protected $__tablename;
    private $rules = [];
    public function __construct(string $tablename, array $rules = [])
    {
        $this->__tablename = $tablename;
        $this->rules = $rules;
    }
    public function loadData($data)
    {

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {

                if (isset($value)) {
//                    if(strtotime($value)){
//                        $this->{$key} = new \DateTime( $value. ' GMT');
//                    }else{
                    $this->{$key} = $value;
                    //}
                }
            }
        }
    }
    public function select(array $params = [], array $order=[], $debug = false): array
    {
        $first = true;
        $db = Database::getInstance();
        $sql = "select * from $this->__tablename ";

        foreach ($params  as $key => $value) {
            if ($first == true) {
                $first = false;
                $sql .= " where $key ";
            } else {

                $sql .= " $key ";
            }

            if (is_numeric($value)) {
                $sql .= $value;
            } else {
                $sql .= "'" . $value . "'";
            }
        }

        $first = true;

        foreach ($order  as $key => $value) {
            if ($first == true) {
                $first = false;
                $sql .= " order by $key ";
                $sql .= " $value ";
            } else {

                $sql .= " ,$key ";
                $sql .= " $value ";
            }

        }


        if($debug == true){
            echo $sql;
            die();
        }
        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_OBJ);

        return $results;
    }
    public function callStoredProcedure($procedure, $parameters = []): array
    {


        $db = Database::getInstance();
        $stmt = $db->prepare($procedure);

        if (!$stmt) {
            return ["message" => "Failed to prepare the statement for the stored procedure."];
        }

        $this->bindParams($stmt, $parameters);

        if (!$stmt->execute()) {
            return ["message" => "Failed to execute the stored procedure."];
        }else{
            return ["message" => "Posts scheduled successfully."];
        }

    }


    public function bindParams($stmt, $parameters) {
        foreach ($parameters as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
    }

    public function placeholders($count){
        return implode(',', array_fill(0, $count, '?'));
    }

    public function customselect($sql, $params = []): array
        {

            $db = Database::getInstance();

            $sth = $db->prepare($sql);

            $sth->execute($params);

            $results = $sth->fetchAll(\PDO::FETCH_OBJ);

            return $results;
        }
    public function update($debug=false, $dontupdate = []): array
    {
        $db = Database::getInstance();
        $sql = "update $this->__tablename set ";

        $params = array();
        $first = true;

        foreach ($this as $key => $value) {
            if ($key != 'id' && $key != '__tablename' && $key != 'rules' && !in_array($key, $dontupdate)) {
                $params += [$key => $value];

                if ($first == true) {
                    $sql .= "$key = :$key ";
                    $first = false;
                } else {
                    $sql .= ",$key = :$key ";
                }
            }
        }

        if (empty($this->id)) {
            return ['result' => false];
        }

        $params['id'] = $this->id;
        $sql .= "where id = :id ";
        if($debug == true){
            echo $sql;
            die();
        }
        $sth = $db->prepare($sql);
        $sth->execute($params);

        $count = $sth->rowCount();

        if ($count == '0') {
            return ['result' => false];
        } else {
            return ['result' => true];
        }
    }
    public function insert($debug=false): int
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO $this->__tablename (";
        $sqlValues = " values (";

        $params = array();
        $first = true;
        foreach ($this as $key => $value) {
            if ($this->filterInsert($key, $value)) {
                $params += [$key => $value];

                if ($first == true) {
                    $sql .= "$key ";
                    $sqlValues .= ":$key ";
                    $first = false;
                } else {
                    $sql .= ",$key";
                    $sqlValues .= ",:$key ";
                }
            }
        }
        $sql .= ', regdate)';
        $sqlValues .= ' , :regdate)';
        $sql = $sql . $sqlValues;
        if($debug == true){
            echo $sql;
            die();
        }
        $sth = $db->prepare($sql);
        $params['regdate'] = date("Y-m-d H:i:s");

        $sth->execute($params);

        return $db->lastInsertId();
    }
    //Makes the selection using all the foreign keys and brings a dataset containing everything about the specific entity
    public function selectWithRefs(array $params = [], array $order=[])
    {
        $db = Database::getInstance();
        $sql = "select a.COLUMN_NAME, a.REFERENCED_TABLE_NAME, a.REFERENCED_COLUMN_NAME from INFORMATION_SCHEMA.KEY_COLUMN_USAGE a
            where a.CONSTRAINT_SCHEMA = '" . CONFIG['db.name'] . "'
            AND a.TABLE_NAME = '$this->__tablename'
            AND a.REFERENCED_TABLE_NAME is not null";

        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_OBJ);


        $sqljoin = "";
        $tables = [];
        for ($i = 0; $i < count($results); $i++) {

            if (!in_array($results[$i]->REFERENCED_TABLE_NAME, $tables)) {
                $sqljoin .= " inner join " . $results[$i]->REFERENCED_TABLE_NAME . " on $this->__tablename." . $results[$i]->COLUMN_NAME;
                $sqljoin .= " = " . $results[$i]->REFERENCED_TABLE_NAME . "." . $results[$i]->REFERENCED_COLUMN_NAME;
                $tables[] = $results[$i]->REFERENCED_TABLE_NAME;
            } else {
                $sqljoin .= " inner join " . $results[$i]->REFERENCED_TABLE_NAME . " r$i on $this->__tablename." . $results[$i]->COLUMN_NAME;
                $sqljoin .= " = r$i." . $results[$i]->REFERENCED_COLUMN_NAME;
            }
        }

        $columns = [];
        $select = "select ";
        $firstcol = true;

        for ($i = 0; $i < count($tables); $i++) {

            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . CONFIG['db.name'] . "' AND TABLE_NAME = '" . $tables[$i] . "'";
            $sth = $db->prepare($sql);

            $sth->execute();
            $results = $sth->fetchAll(\PDO::FETCH_OBJ);

            for ($j = 0; $j < count($results); $j++) {
                if (!in_array($results[$j]->COLUMN_NAME, $columns)) {
                    $columns[] = $results[$j]->COLUMN_NAME;
                    if($firstcol == true){
                        $firstcol = false;
                        $select.= ' '.$tables[$i].'.'.$results[$j]->COLUMN_NAME. ' ';
                    }else{
                        $select.= ' ,'.$tables[$i].'.'.$results[$j]->COLUMN_NAME. ' ';
                    }

                } else {
                    $columns[] = $tables[$i].".".$results[$j]->COLUMN_NAME . ' as ' . $tables[$i] . '_' . $results[$j]->COLUMN_NAME;
                    $select.= ' ,'.$tables[$i].".".$results[$j]->COLUMN_NAME . ' as ' . $tables[$i] . '_' . $results[$j]->COLUMN_NAME;
                }
            }
        }

        $sql = " $select ";
        $sql .= " from $this->__tablename";
        $sql = $sql.$sqljoin;


        $first = true;

        foreach ($params  as $key => $value) {
            if ($first == true) {
                $first = false;
                $sql .= " where $key ";
            } else {

                $sql .= " $key ";
            }

            if (is_numeric($value)) {
                $sql .= $value;
            } else {
                $sql .= "'" . $value . "'";
            }
        }

        $first = true;
        foreach ($order  as $key => $value) {
            if ($first == true) {
                $first = false;
                $sql .= " order by $key ";
                $sql .= " $value ";
            } else {

                $sql .= " ,$key ";
                $sql .= " $value ";
            }

        }


        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_OBJ);

        return $results;
    }
    public function getRegdate()
    {
        $date = new \DateTime();
        return $date->format('Y/m/d H:i:s');
    }
    public function counter($sql, $params){
        $db = Database::getInstance();

        $sql = "select count(*) totalrows from ( ". $sql .") as selector";
        $sth = $db->prepare($sql);
        $sth->execute($params);
        $results = $sth->fetchColumn();

        return $results;

    }
    public function calculateNumberOfPages($rows, $pagelength){
        $pages = $rows/$pagelength;
        return $pages;
    }
    public function delete(Array $params=[], $debug=false){
        $db = Database::getInstance();
        $sql = "DELETE from $this->__tablename ";
        $first = true;
        foreach ($params  as $key => $value) {
            if ($first == true) {
                $first = false;
                $sql .= " where $key ";
            } else {

                $sql .= " $key ";
            }

            if (is_numeric($value)) {
                $sql .= $value;
            } else {
                $sql .= "'" . $value . "'";
            }
        }

        if($debug == true){
            echo $sql;
            die();
        }

        $sth = $db->prepare($sql);

        $sth->execute();

        $count = $sth->rowCount();

        if ($count == '0') {
            return ['result' => false];
        } else {
            return ['result' => true];
        }


    }
    public function validate() : array{

        $errors = [];
        $validator = new Validator();
        foreach($this->rules as $rules){
            foreach($rules[1] as $rule){


                $obj = $validator->selectValidation($rule);
                if(!$obj->validate($this->{$rules[0]})){
                    array_push($errors, [$rules[0], $rule]);
                }
            }
        }

        return $errors;
    }
    public function filterInsert($key, $value) : bool{
        return !empty($value) &&
            $key != '__tablename' &&
            $key != 'rules'  &&
            $key != 'id'
            ;
    }

}