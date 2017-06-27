<?php

require dirname(__FILE__) . '/system/functions.php';
require dirname(__FILE__) . '/system/CalcTrainFare.php';
require dirname(__FILE__) . '/system/CalcRentToPay.php';
require dirname(__FILE__) . '/system/CalcFoodExpenses.php';
require dirname(__FILE__) . '/system/CalcLivingExpenses.php';
require dirname(__FILE__) . '/system/CalcPaymentForStudentLoan.php';

// Ajax以外からのアクセスを遮断
$request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
    ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
if($request !== 'xmlhttprequest') exit;

$data_list = [
    'raw_salary'                         => ['required', 'integer'],
    'work_days'                          => ['required', 'integer'],
    'stay_home_work_days'                => ['required', 'integer'],
    'partner_living_expences_level'      => ['required', 'str'],
    'salary_lines_type'                  => ['required', 'str'],
    'payment_for_student_loan_flg'       => ['required', 'integer'],
];

// 簡易バリデーション
foreach ($data_list as $key => $data) { 
    if (! isset($_POST[$key])) {
        $$key = 0;
    } elseif (isset($data[$key]['integer']) && ! ctype_digit($_POST[$key])) {
        $$key = 0;
    } elseif (isset($data[$key]['string']) && ! is_string($_POST[$key])) {
        $$key = 0;
    } else {
        $$key = $_POST[$key];
    }
}

// 電車賃を計算する
$CalcTrainFareIns = new CalcTrainFare;
$total_train_fare = $CalcTrainFareIns->calc($work_days);

// 給与から給与から電車賃を引く
$salary = $raw_salary - $total_train_fare;

// 家賃のルールにのっとり、給与から家賃を算出する
$CalcRentToPayIns = new CalcRentToPay;
$rent             = $CalcRentToPayIns->calc($salary);

// 食費を算出する
$CalcFoodExpensesIns = new CalcFoodExpenses;
$food_expenses       = $CalcFoodExpensesIns->calcForOnePerson();

// 生活費のルールにのっとり、給与から光熱費系を算出する
$CalcLivingExpensesIns =  new CalcLivingExpenses;
$living_expences       = $CalcLivingExpensesIns
    ->calc($salary, $salary_lines_type, $partner_living_expences_level);

// 支払うべき額（家賃、食費、生活費の合計）
$total_payment = $rent + $food_expenses + $living_expences;

// 自由に使えるお金
$pre_free_use_money = $salary - $total_payment;

/**
 * 以下から個別の支払い
 */

// N母へ奨学金の支払がある人は、その分を計算する
if ($payment_for_student_loan_flg === '1') {
    $CalcPaymentForStudentLoanIns = new CalcPaymentForStudentLoan;
    $payment_for_student_loan     = $CalcPaymentForStudentLoanIns->calc();
    $free_use_money               = $pre_free_use_money - $payment_for_student_loan;
} else {
    $payment_for_student_loan  = 0;
}
 
$params = compact(
    'total_train_fare', 'salary', 'rent', 'food_expenses',
    'living_expences', 'payment_for_student_loan',
    'total_payment', 'pre_free_use_money', 'free_use_money'
);

foreach ($params as & $param) {
    $param = number_format($param);
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($params);
