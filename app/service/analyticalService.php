<?php
namespace App\Service;

use App\Model\transactions;
use App\Model\Budget;

class AnalyticalService
{
    private transactions $transaction;
    private Budget $budget;

    public function __construct(){
        $this->transaction = new transactions();
        $this->budget = new Budget();
    }

    public function getDashboardData($userId){
        $current = $this->transaction->getTotals($userId);
        $last = $this->transaction->getLastMonthTotals($userId);
        $activity = $this->transaction->getRecentActivity($userId, 5);
        $category = $this->transaction->getCategoryBreakdown($userId);
        $budgets = $this->budget->getCurrentMonthBudgets($userId);
        $hadBudgetBefore = $this->budget->hasEverHadBudget($userId);

        return [
            'summary' => $this->buildSummary($current, $last, $budgets),
            'activity' => $activity,
            'category' => $category,
            'budgets' => $budgets,
            'hadBudgetBefore' => $hadBudgetBefore

        ];
    }

    //private helpers 
    private function percent($current, $last){
        if ($last == 0) return $current > 0 ? 100.0 : 0.0;
        return round((($current - $last) / abs($last)) * 100, 1);
    }

    private function direction($pct){
        if ($pct > 0) return 'up';
        if ($pct < 0) return 'down';
        return 'flat';
    }

    private function badge($pct, $dir, $invert = false){
        if ($dir === 'flat' || $pct == 0) {
            return [
                'visual' => 'flat',
                'pct' => 0.0,
                'rotate' => false,
                'label' => 'No change vs last month',
            ];
        }

        return [
            'visual' => $invert ? ($dir === 'up' ? 'down' : 'up') : $dir,
            'pct' => abs($pct),
            'rotate' => ($dir === 'down'),
            'label' => abs($pct) . '% vs last month',
        ];
    }

    private function buildBudgetsLabel($budgets){
        if (empty($budgets)) return 'No budgets this month';
        $onTrack = 0;
        $atLimit = 0;
        foreach ($budgets as $b) {
            $pct = $b['limit'] > 0 ? ($b['spent'] / $b['limit'] * 100) : 0;
            if ($pct >= 90) $atLimit++;
            elseif ($pct < 65) $onTrack++;
        }
        return "{$onTrack} on track, {$atLimit} at limit";
    }

    private function buildSummary($current, $last, $budgets){
        $income = ($current['income'] ?? 0);
        $expense = ($current['expense'] ?? 0);

        $lastIncome = ($last['income'] ?? 0);
        $lastExpense = ($last['expense'] ?? 0);

        $incomePct = $this->percent($income, $lastIncome);
        $expensePct = $this->percent($expense, $lastExpense);

        return [
            // raw values for display
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,


            // pre-computed badge data — view renders only, zero logic
            'income_badge' => $this->badge($incomePct, $this->direction($incomePct)),
            'expense_badge' => $this->badge($expensePct, $this->direction($expensePct), true),
            'budgets_label' => $this->buildBudgetsLabel($budgets),

        ];
    }
}