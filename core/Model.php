<?php

class Model {
    protected static $connection;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        if (!self::$connection) {
            $this->connect();
        }
    }
    
    private function connect() {
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            self::$connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function query($sql, $params = []) {
        $instance = new static();
        
        try {
            $stmt = self::$connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public static function find($id) {
        $instance = new static();
        $table = $instance->table;
        $primaryKey = $instance->primaryKey;
        
        $stmt = self::query("SELECT * FROM $table WHERE $primaryKey = ?", [$id]);
        return $stmt->fetch();
    }
    
    public static function all() {
        $instance = new static();
        $table = $instance->table;
        
        $stmt = self::query("SELECT * FROM $table");
        return $stmt->fetchAll();
    }
    
    public static function where($column, $operator, $value = null) {
        $instance = new static();
        $table = $instance->table;
        
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $stmt = self::query("SELECT * FROM $table WHERE $column $operator ?", [$value]);
        return $stmt->fetchAll();
    }
    
    public static function create($data) {
        $instance = new static();
        $table = $instance->table;
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        self::query($sql, array_values($data));
        
        return self::$connection->lastInsertId();
    }
    
    public static function update($id, $data) {
        $instance = new static();
        $table = $instance->table;
        $primaryKey = $instance->primaryKey;
        
        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "$column = ?";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE $table SET $setClause WHERE $primaryKey = ?";
        $params = array_merge(array_values($data), [$id]);
        
        self::query($sql, $params);
        return true;
    }
    
    public static function delete($id) {
        $instance = new static();
        $table = $instance->table;
        $primaryKey = $instance->primaryKey;
        
        self::query("DELETE FROM $table WHERE $primaryKey = ?", [$id]);
        return true;
    }
    
    public static function raw($sql, $params = []) {
        return self::query($sql, $params);
    }
}
