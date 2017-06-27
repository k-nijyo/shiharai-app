<?php
/**
 * 支払うべき家賃を計算する
 *
 * -- 家賃のルール --
 *
 * 収入が少ない方が家賃分（96,000円）以上の場合は
 * 前月分に立て替えられた分の家賃として半額分を支払う
 *
 * もしくは
 *
 * 収入が少ない方の収入が100,000円以上の場合は
 * 前月分に立て替えらた分の家賃として半額分を支払う
 *
 * ※ 収入が少ない場合の減額措置
 * 上記に満たない場合は前月分に立て替えられた分の家賃として30,000円を支払う
 */
class CalcRentToPay
{
    // 家賃
    private $rent = 100000;

    // 減額措置を取るライン
    private $reduction_salary_line = 100000;

    // 減額措置の取られた家賃
    private $reduction_rent = 30000;

    /**
     * 支払うべき家賃を返す
     */
    public function calc($salary = 0) {
        return ($this->isAbleToHalfPrice($salary)) 
            ? ($this->rent / 2) : $this->reduction_rent;
    }

    private function isAbleToHalfPrice($salary) {
        // 家賃のルール上段適用の場合（もしくは〜以前）
        //return ($this->rent <= $salary) ? true : false;
        // 家賃のルール下段適用の場合（もしくは〜以後）
        return ($salary >= $this->reduction_salary_line) ? true : false;
    }
}
