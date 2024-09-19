<?php
namespace app\core\Validation\Menu;

class StoreMenuValidator
{
    public static function storeMenuData($data)
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors = 'menu name is required';
        }
        if (isset($data['parent_id']) && !is_int($data['parent_id'])) {
            $errors = 'parent_id should be type of int';
        }
        return $errors;

    }
}