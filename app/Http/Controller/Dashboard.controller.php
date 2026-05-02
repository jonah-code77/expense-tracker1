<?php
namespace App\Http\Controller;

use App\Core\Session;
use App\Core\View;
use App\Service\AnalyticalService;

class Dashboard
{
    private AnalyticalService $analytics;

    public function __construct(){
        $this->analytics = new AnalyticalService();
    }

    public function index(){
        $userId = Session::getSession('user_id');
        $data   = $this->analytics->getDashboardData($userId);
        $isFirstLogin = Session::getSession('is_first_login') ?? false;
        $name = Session::getSession('name');

        if($isFirstLogin){
            Session::removeSession('is_first_login'); 
        }

        View::views('home', [
            'summary' => $data['summary'],
            'activity' => $data['activity'],
            'categories' => $data['category'],
            'budgets' => $data['budgets'],
            'hadBudgetBefore' => $data['hadBudgetBefore'],
            'name' => $name,
            'isFirstLogin' => $isFirstLogin
        ], 'public');
    }
}