<?php

/**
 * 電車賃を計算する
 */
class CalcTrainFare
{
    // 片道運賃
    private $one_way_train_fare = 165;
    // 通勤費の支給上限
    private $upper_limit        = 10000;

    /**
     * 片道運賃を往復運賃として月合計の電車賃を返す
     */
    public function calc($work_days = 0) {
         $total_train_fare = ($this->one_way_train_fare * 2) * $work_days;
         return ($total_train_fare <= $this->upper_limit) 
             ? $total_train_fare : $this->upper_limit;
    }
}
