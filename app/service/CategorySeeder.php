<?php
namespace App\Service;

use App\Model\Category;


class CategorySeeder{
    private Category $category;

    // Default categories every new user gets.
    private const DEFAULTS = [
        // expenses
        ['name' => 'Food & Groceries', 'type' => 'expenses'],
        ['name' => 'Rent',             'type' => 'expenses'],
        ['name' => 'Transport',        'type' => 'expenses'],
        ['name' => 'Utilities',        'type' => 'expenses'],
        ['name' => 'Health',           'type' => 'expenses'],
        ['name' => 'Entertainment',    'type' => 'expenses'],
        ['name' => 'Shopping',         'type' => 'expenses'],
        ['name' => 'Education',        'type' => 'expenses'],
        ['name' => 'Savings',          'type' => 'expenses'],
        // income
        ['name' => 'Salary',           'type' => 'income'],
        ['name' => 'Freelance',        'type' => 'income'],
        ['name' => 'Other Income',     'type' => 'income'],
    ];

    public function __construct(){
        $this->category = new Category();
    }

    public function seedDefaults($userId){
        foreach (self::DEFAULTS as $cat) {
            $this->category->createDefault($userId, $cat['name'], $cat['type']);
        }
    }
}