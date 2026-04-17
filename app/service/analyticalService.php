<?php

namespace App\Service;


use App\Model\transactions;

class AnalyticalService{
    private transactions $transaction;

    public function __construct(){
        $this->transaction = new transactions();
    }

    public function getDashboardData($userId){
        $current = $this->transaction->getTotals($userId);
        $last = $this->transaction->getLastMonthTotals($userId);
        $recent = $this->transaction->getRecentTransaction($userId);
        $category = $this->transaction->getCategoryBreakdown($userId);

        return [
            'summary' => $this->buildSummary($current, $last),
            'recent' => $recent,
            'category' => $category
        ];
    }

    // percentage
    private function percent($current, $last){
        if ($last == 0) return 0;
        return (($current - $last) / $last) * 100;
    }

    private function buildSummary($current, $last){
        $income = $current['income'] ?? 0;
        $expense = $current['expense'] ?? 0;

        $lastIncome  = $last['income'] ?? 0;
        $lastExpense = $last['expense'] ?? 0;

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,

            'income_change' => $this->percent($income, $lastIncome),
            'expense_change' => $this->percent($expense, $lastExpense)
        ];
    }
}