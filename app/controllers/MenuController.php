<?php

namespace app\controllers;

use app\models\Menu;
use app\core\JsonResponse;
use app\core\Validation\Menu\StoreMenuValidator;

class MenuController
{
    public function index()
    {
        $menu = new Menu();
        $menus = $menu->index();

        JsonResponse::send($menus);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = StoreMenuValidator::storeMenuData($data);

        if (!empty($errors)) {
            return JsonResponse::send(['errors' => $errors], 422);
        }

        $menu = new Menu();
        $result = $menu->create($data['name'], $data['parent_id'] ?? null);

        if ($result) {
            JsonResponse::send(['success' => true, 'message' => 'menu created successfully'], 201);
        } else {
            JsonResponse::send(['error' => 'internal server error occurred'], 500);
        }
    }
}
