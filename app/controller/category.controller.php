<?php
namespace App\Controller;
use App\Model\Category;
use App\Core\Session;
use App\Core\View;
class categories {
    private $category;

    public function __construct()
    {
        $this->category = new category();
    }

    //show all categories
    public function categoryDashboard(){
        $userId = Session::getSession('user_id');
        $categories = $this->category->getCategories($userId);
        $data = [];
        foreach($categories as $cat){
            $hasTransactionHistory = $this->category->hasTransaction($cat['id'], $userId);
            $cat['hasTransactionHistory'] = $hasTransactionHistory;
            $data[] = $cat;

        }
        View::views('category/dashboard',['categories'=>$data], 'public');
    }

    //add a new category controller
    public function createView(){
        View::views('category/create', [], 'public');
    }

    public function createCategory(){
        $errMsg = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = trim($_POST['name']);
            $type = trim($_POST['type']);
            $userId = Session::getSession('user_id');

            //validation
            if(!empty($name) && !empty($type)){
                if(!in_array($type, ['income','expenses'])){
                    $errMsg[] = "please Select a valid category";
                }

                $newCategoryId = $this->category->addToCategory($userId, $name, $type);
                if ($newCategoryId) {
                    if (isset($_POST['btn'])) {
                        header("location: ". BASE_URL . "/transaction/create/$newCategoryId");
                        exit;
                    }else{
                        header("location:". BASE_URL . "/category/dashboard");
                        exit;
                    }                  
                   
                }else{
                    $errMsg[] = "failed to get category";
                }
                
                
            }else{
                $errMsg[] = "fill all inputs";
            }
        }
        View::views('category/create',['errMsg' => $errMsg], 'public');
    }

    //edit category controller

    public function editCategoryView($id){
        $userId = Session::getSession('user_id');
        $category = $this->category->getCategoryById($userId,$id);
        if (!$category) {
            die("category not found");
        }
        View::views('category/edit', ['category' => $category], 'public');
    }

    public function editCategory($id){
        //$id = $_POST['id'] ?? '';
        $userId = Session::getSession('user_id');
        $errMsg = [];
        $category = $this->category->getCategoryById($userId,$id);
        //check for transcation
        $hasTransctionHistory = $this->category->hasTransaction($id,$userId);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = trim($_POST['name']) ?? '';
            $type = trim($_POST['type']) ?? '';


            if(!empty($name) && !empty($type)){
                if ($hasTransctionHistory && $type !== $category['type']) {
                    $errMsg[] = "you cannot change the type as it already has a transaction history"; 
                }

                $result = $this->category->updateCategory($name,$type,$id,$userId,$hasTransctionHistory);
                if ($result) {
                    header("location: ". BASE_URL . "/category/dashboard");
                }else{
                    $errMsg = "failed to updated DataBase";
                }
                
            }else{
                $errMsg[] = "fill all inputs";
            }
        }
        View::views('category/edit', ['category' => $category, 'errMsg' => $errMsg], 'public');
    }

    //delete category
    public function deleteCategory($id){
        $userId = Session::getSession('user_id');
        $hasTransctionHistory = $this->category->hasTransaction($id,$userId);
        if ($hasTransctionHistory) {
            echo "you cannot delete this category because it has a transaction history";
        }else{
            $this->category->deleteCategory($id,$userId);
            header("location:". BASE_URL . "/category/dashboard");
        }

    }
}