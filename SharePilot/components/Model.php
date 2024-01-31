<?php
namespace SharePilotV2\Components;

abstract class Model
{
    protected $data = [];
    private static $pdo = null;

    public function __construct()
    {
        if (self::$pdo === null) {
            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            //$pdo = new \PDO('mysql:host=0fd0249c7d97;dbname=sharepilot', 'root', '526996');


            //die();
            self::$pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);
        }

    }

    abstract public function getTable();

    public static function getPdo()
    {
        return self::$pdo;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name){
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    public function insert()
    {
        $table = $this->getTable();
        $fields = implode(',', array_keys($this->data));
        $placeholders = ':' . implode(', :', array_keys($this->data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";

        try {
            $stmt = self::$pdo->prepare($sql);

            foreach ($this->data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public function update()
    {
        $query = new Query($this, 'update');

        // bind parameters for the update
        foreach ($this->data as $key => $value) {
            $query->setParam($key, $value);
        }

        return $query;
    }

    public function select()
    {
        return new Query($this, 'select');
    }

    public function delete()
    {
        return new Query($this, 'delete');
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = self::$pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();

            // Check if it's a SELECT statement, if so return results
            if (stripos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            // Otherwise return the number of affected rows
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return false;
        }
    }

    public function callStoredProcedure($procedure, $params)
    {
        $inPlaceholders = implode(',', array_fill(0, count($params), '?'));

        // Adding an OUT parameter
        $outPlaceholder = "@outParam";

        $placeholders = $inPlaceholders . ", " . $outPlaceholder;

        try {
            // Calling the stored procedure
            $stmt = self::$pdo->prepare("CALL $procedure($placeholders)");
            $stmt->execute(array_values($params));

            // Getting the OUT parameter value
            $outStmt = self::$pdo->query("SELECT $outPlaceholder");
            $procedureSuccess = $outStmt->fetchColumn();

            // Return both the fetched rows and the OUT parameter value
            return  (bool)$procedureSuccess;

        } catch (\PDOException $e) {
            echo "Stored procedure call failed: " . $e->getMessage();
            return false;
        }
    }


    public function loadProperties($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    public function beginTransaction()
    {
        return self::$pdo->beginTransaction();
    }

    public function commit()
    {
        return self::$pdo->commit();
    }

    public function rollBack()
    {
        return self::$pdo->rollBack();
    }
}
