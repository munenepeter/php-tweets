<?php
namespace App\Database;

class DB {

    private static $instance;
    private $pdo;

    private function __construct($pdo)    {
        $this->pdo = $pdo;
    }

    public static function getInstance($pdo)    {
        if (self::$instance === null) {
            self::$instance = new self($pdo);
        }

        return self::$instance;
    }
   /**
     * Insert a record into the database table.
     *
     * @param string $table The name of the table.
     * @param array $parameters An associative array of column names and values.
     * @throws \Exception If there is an error with the query.
     */
    public function insert(string $table, array $parameters) {

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(', ', array_keys($parameters)) , ':' . implode(', :', array_keys($parameters)));

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($parameters);
            return true;
        }
        catch(\Exception $e) {
            throw new \Exception("Database: Error with Query" . $e->getCode());
        }
    }

    public function selectAll(string $table) {
        $sql = "select * from {$table} ORDER BY `created_at` DESC;";
        return $this->runQuery($sql, $table);
    }

    public function update(string $table, $dataToUpdate, $where, $isValue) {
        $sql = "UPDATE {$table} SET $dataToUpdate WHERE `$where` = \"$isValue\"";
        return $this->runQuery($sql, $table);
    }

    //DELETE FROM table_name WHERE condition;
    public function delete(string $table, $where, $isValue) {
        $sql = "DELETE FROM {$table} WHERE `$where` = \"$isValue\"";
        return $this->runQuery($sql, $table);
    }

      /**
     * Run a SQL query and return the results or true for update/delete queries.
     *
     * @param string $sql The SQL query.
     * @param string $table The name of the table.
     * @return array|bool The results of the query or true for update/delete queries.
     * @throws \Exception If there is an error with the query.
     */
    public function query(string $sql) {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
        }
        catch(\Exception $e) {
            throw new \Exception("There seems to be something wrong with the query!" . PHP_EOL);
        }

        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($this->isUpdateOrDeleteQuery($sql) && empty($results)) {
            return true;
        }

        return $results;
    }

    public function count(string $table, array $condition) {
        //SELECT COUNT(*) FROM $table WHERE $condition[0] = $condition[2];
        list($column, $value) = $condition;
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE $column = \"$value\"";

        return $this->runQuery($sql, $table);
    }
    /**
     * Run a SQL query and return the results or true for update/delete queries.
     *
     * @param string $sql The SQL query.
     * @param string $table The name of the table.
     * @return array|bool The results of the query or true for update/delete queries.
     * @throws \Exception If there is an error with the query.
     */
    private function runQuery(string $sql, string $table) {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
        }
        catch(\Exception $e) {
            throw new \Exception("There seems to be something wrong with the query!" . PHP_EOL);
        }

        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($this->isUpdateOrDeleteQuery($sql) && empty($results)) {
            return true;
        }

        return $results;
    }

    private function isUpdateOrDeleteQuery(string $sql):bool {
       return str_contains($sql, "update") || str_contains($sql, "delete");
    }
}
    
