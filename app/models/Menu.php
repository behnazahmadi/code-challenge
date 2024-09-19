<?php

namespace app\models;

use app\core\Database;
use PDO;
use PDOException;

class Menu
{
    private $conn;
    private $table = 'menus';

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($name, $parent_id = null)
    {
        $query = "INSERT INTO " . $this->table . " (name, parent_id) VALUES (:name, :parent_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

//    public function index()
//    {
//        $query = "SELECT * FROM " . $this->table;
//        $stmt = $this->conn->prepare($query);
//        $stmt->execute();
//
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
    public function index()
    {
        $query = "SELECT * FROM {$this->table}";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $menuTree = [];
            foreach ($menus as $menu) {
                if ($menu['parent_id'] === null) {
                    $menuTree[$menu['id']] = $menu;
                    $menuTree[$menu['id']]['childs'] = [];
                } else {
                    $menuTree[$menu['parent_id']]['childs'][] = $menu;
                }
            }

            return $menuTree;
        } catch (PDOException $e) {
            error_log('error fetching menus: ' . $e->getMessage());
            return false;
        }
    }
}
