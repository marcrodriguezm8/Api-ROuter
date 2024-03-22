<?php

namespace App\Database;

class QueryBuilder {
    private $selectables = [];
    private $query;
    private $table;
    private $whereClause;
    private $limit;
    private $params = [];
    protected $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Set up a SELECT * FROM query for the given table.
     *
     * @param string $table The table to select from.
     * @return $this
     */
    function selectAll($table) {
        $this->query = "SELECT * FROM {$table}";
        return $this;
    }

    /**
     * Set up a SELECT query with specified fields for the given table.
     *
     * @param string $table The table to select from.
     * @param array $fields The fields to select.
     * @return $this
     */
    public function select($table, $fields) {
        $columns = implode(', ', $fields);
        $this->query = "SELECT $columns FROM {$table}";
        return $this;
    }
    public function find($table, $value){
        $result = $this->select($table, ['id'])
        ->condition(['id'], $table, [$value], '=')
        ->get();
        
    }
    public function delete($table){
        $this->query .= "DELETE FROM $table ";
        return $this;
    }

    /**
     * Set up a WHERE condition in the query.
     *
     * @param array $conditionFieldNames The field names for conditions.
     * @param string $table The table to apply conditions on.
     * @param array $values The values for the conditions.
     * @param string $symbol The comparison symbol (e.g., '=', '>', '<').
     * @return $this
     */
    public function condition(array $conditionFieldNames, string $table, array $values, string $symbol) {
        $this->whereClause = " WHERE";
        
        $this->generateBindParams($conditionFieldNames, $values);
        
        $i = 0;
        foreach ($conditionFieldNames as $condition) {
            if ($i == 0) {
                $this->whereClause .=  " $table.$condition $symbol :$condition";
            } else {
                $this->whereClause .=  " AND $table.$condition $symbol :$condition ";
            }
            $i++;
        }
        $this->query .= $this->whereClause;
        return $this;
    }

    /**
     * Set up an INSERT query for the given table with specified fields.
     *
     * @param string $table The table to insert into.
     * @param array $fields The fields and values to insert.
     * @return $this
     */
    public function insert(string $table, array $fields) {
        $columns = implode(', ', array_keys($fields));
        
        $values = array_map(function ($value) {
            return is_string($value) ? "'" . $value ."'" : $value;
        }, $fields);
        
        $this->generateBindParams(array_keys($fields), array_values($fields));
        
        $values = implode(', ', array_keys($this->params));
        
        $this->query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        
        return $this;
    }

    /**
     * Set up an UPDATE query for the given table with specified fields.
     *
     * @param string $table The table to update.
     * @param array $fields The fields and values to update.
     * @return $this
     */
    public function update(string $table, array $fields) {
        $this->query = "UPDATE {$table} SET ";

        $this->generateBindParams(array_keys($fields), array_values($fields));

        foreach ($fields as $field => $value) {
            $this->query .= $field ." = :". $field.", ";
        }
        $this->query = rtrim($this->query, ', ');
        
        return $this;
    }

    /**
     * Set up a JOIN clause in the query.
     *
     * @param string $table1 The first table for the JOIN.
     * @param string $table2 The second table for the JOIN.
     * @param string $field The field to join on.
     * @param string $join The type of JOIN (e.g., INNER, LEFT, RIGHT).
     * @return $this
     */
    public function join(string $table1, string $table2, string $field, string $join) {
        $this->query .= " ".$join." JOIN {$table2} ON ".$table1.".".$field." = ". $table2.".".$field;
        return $this;
    }

    /**
     * Execute the query and retrieve the results.
     *
     * @return array The results of the query.
     * @throws \Exception If an error occurs during execution.
     */
    public function get() {
        try {
            $statement = $this->pdo->prepare($this->query);
            $statement->execute($this->params);
            $this->params = [];
            return $statement->fetchAll(\PDO::FETCH_CLASS);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Generate binding parameters for the query.
     *
     * @param array $fieldNames The names of fields to bind.
     * @param array $values The values to bind.
     */
    private function generateBindParams(array $fieldNames, array $values) {
        foreach ($values as $value => $v) {
            $this->params = array_merge($this->params, [':'.$fieldNames[$value] =>  $v]);
        }
    }
}