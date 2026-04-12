<?php
namespace App\Controller;
use App\Model\transactions;
//use App\Model\Category;
use App\Core\Session;
use App\Core\View;

class Dashboard{
    private $transactionModel;
    private $categoryModel;

    public function __construct()
    {
        $this->transactionModel = new transactions();
        //$this->categoryModel = new Category();
    }

    //Main Dashboard
    public function MainDahboard(){
        $userId = Session::getSession('user_id');
        //$total = $this->transactionModel->getTotal($userId);
        View::views('home',['user_id'=>$userId], 'public');
    }

    public function dashboard(){
        $userId = Session::getSession('user_id');
        $year = date('Y');
        $month = date('m');

        $totals = $this->transactionModel->getTotal($userId);
        $monthlySummary = $this->transactionModel->getMonthlyExpenses($userId,$month,$year);

        $recentTransaction = $this->transactionModel->getRecentTransaction($userId,3);

        View::views('home', 
        [
            'totals' => $totals,
            'transactions' => $recentTransaction,
            'monthlySummary' => $monthlySummary
        ],
        'public');
    }
}