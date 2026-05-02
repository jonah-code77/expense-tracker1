<?php
namespace App\Core;

class CategoryIcons{
    private const ICONS = [
        'food'             => ['ico'=>'bi-basket-fill',          'ibg'=>'rgba(249,115,22,.13)', 'ic'=>'#fb923c'],
        'food & groceries' => ['ico'=>'bi-basket-fill',          'ibg'=>'rgba(249,115,22,.13)', 'ic'=>'#fb923c'],
        'groceries'        => ['ico'=>'bi-bag-fill',             'ibg'=>'rgba(249,115,22,.13)', 'ic'=>'#fb923c'],
        'rent'             => ['ico'=>'bi-house-fill',            'ibg'=>'rgba(59,130,246,.13)', 'ic'=>'var(--accent2)'],
        'transport'        => ['ico'=>'bi-car-front-fill',        'ibg'=>'rgba(245,158,11,.13)', 'ic'=>'var(--yellow2)'],
        'utilities'        => ['ico'=>'bi-lightning-charge-fill', 'ibg'=>'rgba(139,92,246,.13)','ic'=>'var(--purple)'],
        'entertainment'    => ['ico'=>'bi-controller',            'ibg'=>'rgba(236,72,153,.13)', 'ic'=>'#f472b6'],
        'health'           => ['ico'=>'bi-heart-pulse-fill',      'ibg'=>'rgba(239,68,68,.13)',  'ic'=>'#f87171'],
        'savings'          => ['ico'=>'bi-piggy-bank-fill',       'ibg'=>'rgba(16,185,129,.13)', 'ic'=>'var(--green2)'],
        'income'           => ['ico'=>'bi-briefcase-fill',        'ibg'=>'rgba(16,185,129,.13)', 'ic'=>'var(--green2)'],
        'salary'           => ['ico'=>'bi-briefcase-fill',        'ibg'=>'rgba(16,185,129,.13)', 'ic'=>'var(--green2)'],
        'shopping'         => ['ico'=>'bi-bag-heart-fill',        'ibg'=>'rgba(244,114,182,.13)','ic'=>'#f472b6'],
        'education'        => ['ico'=>'bi-book-fill',             'ibg'=>'rgba(99,102,241,.13)', 'ic'=>'#818cf8'],
        'travel'           => ['ico'=>'bi-airplane-fill',         'ibg'=>'rgba(14,165,233,.13)', 'ic'=>'#38bdf8'],
    ];

    private const FALLBACK_INCOME  = ['ico'=>'bi-arrow-down-circle-fill','ibg'=>'rgba(16,185,129,.13)','ic'=>'var(--green2)'];
    private const FALLBACK_EXPENSE = ['ico'=>'bi-arrow-up-circle-fill',  'ibg'=>'rgba(239,68,68,.1)',  'ic'=>'#f87171'];

    public static function resolve($name, $isIncome = false){
        $key = strtolower(trim($name));
        return self::ICONS[$key] ?? ($isIncome ? self::FALLBACK_INCOME : self::FALLBACK_EXPENSE);
    }
}