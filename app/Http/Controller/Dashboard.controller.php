<?php
namespace App\Http\Controller;
use App\Core\Session;
use App\Core\View;
use App\Service\AnalyticalService;

class Dashboard{
    private $analytics;
    private $categoryModel;

    public function __construct()
    {
        $this->analytics = new AnalyticalService();
        //$this->categoryModel = new Category();
    }


    public function index(){
        $userId = Session::getSession('user_id');
       
        $data = $this->analytics->getDashboardData($userId);

        View::views('home', 
        [
            'summary' => $data['summary'],
            'recent' => $data['recent'],
            'category' => $data['category']
        ],
        'public');
    }


    
}