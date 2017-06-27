<?php
if (isset($_GET['user']) && $_GET['user'] === 'user_m') {
    $user                               = 'user_m';
    $user_name                          = 'M';
    $payment_for_student_loan_flg       = 0; // N母への奨学金の支払無し
    $default_raw_salary                 = 300000;
    $default_work_days                  = 0;
    $default_stay_home_work_days        = 0;
    $salary_lines_type                  = 'high_salary_lines';
} else {
    $user                               = 'user_n';
    $user_name                          = 'N';
    $payment_for_student_loan_flg       = 1; // N母への奨学金の支払有り
    $default_raw_salary                 = 200000;
    $default_work_days                  = 20;
    $default_stay_home_work_days        = 0;
    $salary_lines_type                  = 'low_salary_lines';
}
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $user_name; ?>支払い計算システム</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link href="./css/style.css" rel="stylesheet">
</head>
<body>

<?php include dirname(__FILE__) . '/header.php'; ?>

<div class="jumbotron">
  <div class="container">
    <h1><?php echo $user_name; ?>支払い計算システム</h1>
    <p>
    今月の家計に充てる額や、それぞれの返済額などを計算します。
    </p>
    <p>
    <?php if ($user === 'user_n') : ?>
    <button type="button" class="btn btn-success" onclick="location.href='./?user=user_m'">M支払い計算システムはこちら</button>
    <?php else : ?>
    <button type="button" class="btn btn-info" onclick="location.href='./?user=user_n'">N支払い計算システムはこちら</button>
    <?php endif; ?>
    </p>
  </div><!-- /.container -->
</div>

<div class="container">
  <div class="row">
    <div class="col-md-5">
      <h2>入力フォーム</h2>
      <form action="./ajax-calc.php" method="POST">
        <div class="form-group">
          <label for="raw_salary">会社から振り込まれた給与</label>
          <p class="bg-warning">カンマ（, ）は入れずに入力してください。</p>
          <input type="text" class="form-control" name="raw_salary" id="raw_salary" placeholder="会社から振り込まれた給与（ , は不要）" value="<?php echo $default_raw_salary; ?>" required="required" /> 円
        </div>
        <div class="form-group">
          <label for="work_days">出勤日数</label>
          <input type="text" class="form-control" name="work_days" id="work_days" placeholder="出勤日数" value="<?php echo $default_work_days; ?>" required="required" /> 日
        </div>
        <div class="form-group">
          <label for="stay_home_work_days">在宅勤務日数</label>
          <input type="text" class="form-control" name="stay_home_work_days" id="stay_home_work_days" placeholder="在宅勤務日数" value="<?php echo $default_stay_home_work_days; ?>" required="required" /> 日
        </div>
        <div class="form-group">
          <label for="partner_living_expences_level">相手方の生活費支出レベル</label>
          <select class="form-control" name="partner_living_expences_level">
            <option value="high"<?php echo ($user === 'user_n') ? ' selected="selected"' : '' ?>>高い</option>
            <option value="user_mdle"<?php echo ($user === 'user_m') ? ' selected="selected"' : '' ?>>普通</option>
            <option value="low">低い</option>
          </select>
        </div>
        <div class="form-group">
        <input type="hidden" class="form-control" name="salary_lines_type" id="salary_lines_type" value="<?php echo $salary_lines_type; ?>">
        <input type="hidden" class="form-control" name="payment_for_nan_flg" id="payment_for_nan_flg" value="<?php echo $payment_for_nan_flg; ?>">
        <input type="hidden" class="form-control" name="payment_for_student_loan_flg" id="payment_for_student_loan_flg" value="<?php echo $payment_for_student_loan_flg; ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">計算する</button>
      </form>
    </div><!-- /.col-md-5 -->

    <div class="col-md-7">

      <h2>計算結果</h2>

      <table class="table table-bordered table-hover">
        <tr>
          <td class="active">
          振り込まれた額から通勤費（
          <input type="text" id="result_total_train_fare" value="0" readonly="readonly" />
          円）を引くと、給与は
          <input type="text" id="result_salary"  value="0" readonly="readonly" />
          円です。
         </td>
        </tr>
        <tr>
          <td>
          家賃を計算した結果、今月は
          <input type="text" id="result_rent" value="0" readonly="readonly" />
          円です
          </td>
        </tr>
        <tr>
          <td>
          食費を計算した結果、今月は
          <input type="text" id="result_food_expenses" value="0" readonly="readonly" />
          円です。
          </td>
        </tr>
        <tr>
          <td>
          生活費を計算した結果、今月は
          <input type="text" id="result_living_expences" value="0" readonly="readonly" />
          円です。
          </td>
        </tr>
        <tr>
          <td>
          これらの支払いを合計すると
          <input type="text" id="result_total_payment" value="0" readonly="readonly" />
          円です。
          </td>
        </tr>
        <tr>
          <td class="success">
          よって、家計に入れるお金は
          <input type="text" id="last_result_total_payment" value="0" readonly="readonly" />
          です。
          <br />
          <br />
          <p class="text-danger">
          <?php if ($user === 'user_n') : ?>
          ※ こちらはみずほ銀行のMの口座に入金してください。<br />
          確認後、Mが家賃分を専用口座に移行したり、光熱費ネット代の支払いを行います。<br />
          その後、残った分から今月の食費が共用のお財布に入ります。
          <?php else : ?>
          ※ こちらをまず、みずほ銀行の自分の口座に入金してください。<br />
          次に同口座にNからの入金を確認したのち、
          家賃分を三井住友銀行に移行してください。<br />
          その後、みずほ銀行の残りの分から光熱費ネット代を支払った後は、<br />
          食費を1,000円単位で引き落とし、共用のお財布に入れておいてください。<br />
          ※ Nに家賃の減額措置が取られている場合は上記に加えて、その分もこちらが追加で負担してください。
          <?php endif; ?>
          </p>
          </td>
        </tr>
        <tr>
          <td class="warning">
          この時点で手元に残るお金は
          <input type="text" id="result_pre_free_use_money" value="0" readonly="readonly" />
          です。
          </td>
        </tr>
        <tr>

          <?php if ($payment_for_student_loan_flg) : ?>
            <td>
          N母への奨学金返済を計算した結果、今月は
          <input type="text" id="result_payment_for_student_loan" value="0" readonly="readonly" />
          円です。
          </td>
          </tr>
          <tr>
              <td class="info">
                  これらの支払いも合わせると残りの手元に残るお金は
                  <input type="text" id="result_free_use_money" value="0" readonly="readonly" />
                  です。
              </td>
          </tr>
          <?php else : ?>
              <tr>
                  <td class="info">
                      奨学金の支払いはありません。
                  </td>
          </tr>
          <?php endif; ?>
          </td>

        </tr>
      </table>

    </div><!-- /.col-md-7 -->
  </div><!-- /.row -->
</div><!-- /.container -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="./javascript/calc.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.jumbotron').css('background-color',
            "<?php echo ($user === 'user_n') ? '#dff0d8' : '#d9edf7'; ?>");
    });
</script>

</body>
</html>
