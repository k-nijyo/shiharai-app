<?php
/**
 * N母への奨学金の支払いを計算する
 */
class CalcPaymentForStudentLoan
{
    // 毎月の支払額
    private $monthly_payment = 10000;

    public function calc() {
        return $this->monthly_payment;
    }
}
