<?php
/**
 * 光熱費系の生活費を計算する
 *
 * - 生活費のルール -
 *
 * 1. 収入の少ない方が月収12万円以上の場合
 * 光熱費系統はそれぞれ1万円を出す。
 *
 * 1. 収入の少ない方が月収10万円以上の場合
 * 光熱費系統は収入の多い方が1.3万円出し、少ない方が7千円を出す。
 *
 * 2. 収入の少ない方が月収10万円未満の場合
 * 光熱費系統は収入の多い方が1.5万円出し、少ない方が5千円を出す。
 */
class CalcLivingExpenses
{
    private $salary_lines_types = [
        'high_salary_lines' => [
            'high'   => ['line' => 120000, 'payment' => 15000],
            'user_mdle' => ['line' => 100000, 'payment' => 13000],
            'low'    => ['line' =>  99999, 'payment' => 10000],
        ],
        'low_salary_lines' => [
            'high'   => ['line' => 120000, 'payment' => 10000],
            'user_mdle' => ['line' => 100000, 'payment' => 7000],
            'low'    => ['line' =>  99999, 'payment' => 5000],
        ],
    ];
    
    private $max_living_expenses_line = 20000;

    public function calc($salary, $salary_lines_type = 'low_salary_lines', $partner_living_expences_level = 'low') {
        $salary_lines = $this->salary_lines_types[$salary_lines_type];
        $salary_line  = '';

        switch ($salary) {
            case ($salary >= $salary_lines['high']['line']) :
                $salary_line = 'high';
                break;
            case ($salary >= $salary_lines['user_mdle']['line']) :
                $salary_line = 'user_mdle';
                break;
            default : //case ($salary < 100000) :
                $salary_line = 'low';
                break;
        }
        
        // 相手方が支払う金額を出す
        $partner_payment = $this->calc_partner_living_expences_level(
            $salary_lines_type, $partner_living_expences_level
        );
            
        // 自分と相手方の支払う金額のトータルを出す（この処理は自分が high_salary の場合のみ）
        if ($salary_lines_type === 'high_salary_lines') {
            $total_payment = $partner_payment + $salary_lines[$salary_line]['payment'];
        
            // 支払う金額のトータルがMAXの基準値を超えていたら差額を引く
            if ($total_payment > $this->max_living_expenses_line) {
                $difference = $total_payment - $this->max_living_expenses_line;
                $salary_lines[$salary_line]['payment'] -= $difference;
            }
        }

        return $salary_lines[$salary_line]['payment'];
    }
    
    private function calc_partner_living_expences_level($salary_lines_type, $partner_living_expences_level) {
    
        // もし自分の給与レベルが高ければ相手の給与レベルは逆になる
        $partner_salary_lines_type = ($salary_lines_type === 'high_salary_lines') 
            ? 'low_salary_lines' : 'high_salary_lines';
            
        return $this->salary_lines_types
            [$partner_salary_lines_type][$partner_living_expences_level]['payment'];
    }
}
