<?php
/**
 * 一人当たりの食費を返す
 */
class CalcFoodExpenses
{
    // 食費
    private $food_expenses    = 15000;
    // 家族の人数
    private $number_of_people = 2;

    public function calcForOnePerson() {
        return ($this->food_expenses / $this->number_of_people);
    }
}
