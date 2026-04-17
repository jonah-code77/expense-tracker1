<?php
namespace App\Http\Controller;
use App\Model\transactions;
use App\Model\Category;
use App\Core\Session;
use App\Core\View;

class transaction {
    private $transaction;
    private $category;

    public function __construct()
    {
        $this->transaction = new transactions();
        $this->category = new category; 
    }

    public function transactionDashboard(){
        $userId = Session::getSession('user_id');
        $transactions = $this->transaction->getMonthly($userId);
        $transactions = $this->getFilteredTransaction($userId);
        View::views('transaction/dashboard', ['transactions' => $transactions], 'public');
    }

    //search
    // public function search(){
    //     $userId = Session::getSession('user_id');
    //     $search = trim($_GET['search'] ?? '');
    //     if($search !== ""){
    //         $transactions = $this->transaction->search($userId,$search);
    //     }else{
    //         $transactions = $this->transaction->getTransactions($userId);
    //     }
    //     View::views('transaction/dashboard', ['transactions'=> $transactions, 'search'=>$search]);
    // }


    private function getFilteredTransaction($userId){
        $search = trim($_GET['search'] ?? '');
        $type = trim($_GET['type'] ?? '');
        $getDate = trim($_GET['getDate'] ?? '');
        $errMsg = [];

        if (empty($type) && !\in_array($type,['income','expenses', 'All types'] )) {
            $errMsg[] = "Please pick a type";
            $type = "";
        }

        if (!empty($getDate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $getDate)) {
            $errMsg[] = "pick a valid date format";
            $getDate = "";
        }
 
        // if ($end_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
        //     $errMsg[] = "pick a valid date format";
        //     $end_date = "";
        // }

        // if($start_date && $end_date && $start_date > $end_date){
        //     $errMsg = "pick a valid start and end date";
        //     $start_date = $end_date = "";
        // }

        $filter = [
            'search' => $search,
            'type' => $type,
            'getDate' => $getDate,
           
        ];

        return $this->transaction->getFiltered($userId,$filter);
    }

    public function getSingleTransaction($id){
        $userId = Session::getSession('user_id');
        $transaction = $this->transaction->getTransactionsById($userId,$id);
        $previousUrl = $_SERVER['HTTP_REFERER'] ?? BASE_URL .'/transaction/dashboard'; 
        View::views('transaction/single', 
        [
            'transaction'=>$transaction,
            'previousUrl'=>$previousUrl
        ],
        'public'
        );
    }

    public function editTransactionView($id){
        $userId = Session::getSession('user_id');
        $transaction = $this->transaction->getTransactionsById($userId,$id);
        if (!$transaction) {
            die("transaction not found");
        }
        View::views('transaction/edit', ['transaction' => $transaction], 'public');
    }

    public function editTransaction($id){
        //$id = $_POST['id'] ?? '';
        $userId = Session::getSession('user_id');
        $errMsg = [];
        $transaction = $this->transaction->getTransactionsById($userId,$id);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $amount = trim($_POST['amount']);
            $desc = trim($_POST['descr']);
            if(!empty($amount) && !empty($desc)){
                $result = $this->transaction->updateTransaction($userId,$id,$amount,$desc);
                if ($result) {
                    header("location: ". BASE_URL . "/transaction/dashboard");
                }else{
                    $errMsg = "failed to updated DataBase";
                }
                
            }else{
                $errMsg[] = "fill all inputs";
            }
        }
        View::views('transaction/edit', ['transaction' => $transaction, 'errMsg' => $errMsg], 'public');
    }


    //Add to Transaction
    public function createView($id){
        $userId = Session::getSession('user_id');
        $category = $this->category->getCategoryById($userId,$id);
        View::views('transaction/create', ['category' => $category], 'public');
    }

    public function createTransaction(){
        $userId = Session::getSession('user_id');
        $errMsg = [];
        $categoryId = $_POST['id'] ?? null;
        //fetch Category from the db
        $category = $this->category->getCategoryById($userId,$categoryId);
        if (!$category) {
            die("error ". var_dump($category). " found");
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$categoryId = $_POST['id'];
            $amount = filter_var(trim($_POST['amount'] ?? ''), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $desc = trim($_POST['descr']);

            if(is_numeric($amount) && !empty($desc)){
                $result = $this->transaction->create($userId,$categoryId, $amount, $desc);
                if ($result) {
                    header("location:". BASE_URL . "/transaction/dashboard");
                }else{
                    $errMsg[] = "failed to updated database";
                }
            }else{
                $errMsg[] = "Please Fill in all inputs";
            }
        }
        View::views('transaction/create', ['category' => $category,'errMsg' => $errMsg], 'public');
    }

    //delete transaction
    public function deleteTransaction($id){
        $userId = Session::getSession('user_id');
        $this->transaction->deleteTransaction($id,$userId);
        header("location:". BASE_URL . "/transaction/dashboard");
    }

    public function getTransactionsByCategory($categoryId){
        $userId = Session::getSession('user_id');
        $category = $this->category->getCategoryById($userId,$categoryId);
        $transaction = $this->transaction->getTransactionsByCategory($categoryId,$userId);
        View::views('transaction/byCategory', 
        [
            'category' => $category,
            'transaction' => $transaction
        ],
        'public'
        );
    }
    
    public function getReport(){
        $userId = Session::getSession('user_id');
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $errMsg = [];
        $transactions = [];
        $totals = [
            'income' => 0,
            'expenses' => 0,
            'balance' => 0
        ];

        if($startDate && $endDate){
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || ($endDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate))) {
                $transactions = $this->transaction->getTransactionReport($userId,$startDate,$endDate);
                foreach($transactions as $transaction){
                    if($transaction['type'] === 'income'){
                        $totals['income'] += $transaction['amount'];
                    }else{
                        $totals['expenses'] += $transaction['amount'];
                    }
                }
                $totals['balance'] = $totals['income'] - $totals['expenses'];
            }else{
                $errMsg[] = "Invalid date format";
            }
        }else{
            //$errMsg[] = "incomplete dates";
            
        }
        
        View::views('transaction/report', 
        [
            'transactions' => $transactions,
            'errMsg' => $errMsg,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totals' => $totals
        ], 
        'public'
        );
    }

    public function getTotals(){
        $userId = Session::getSession('user_id');
        $total = $this->transaction->getTotals($userId);
        View::views('home', ['total'=>$total], 'public');
    }

}