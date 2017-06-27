jQuery(function($) {
    $('form').submit(function(event) {

        $('table').fadeOut();

        // HTMLでの送信をキャンセル
        event.preventDefault();
        
        // 操作対象のフォーム要素を取得
        var $form = $(this);
        
        // 送信ボタンを取得（後で使う: 二重送信を防止する。）
        var $button = $form.find('button');
        
        // 送信
        $.ajax({
            url:  $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            timeout: 10000,  // 単位はミリ秒
            
            // 送信前
            beforeSend: function(xhr, settings) {
                // ボタンを無効化し、二重送信を防止
                $button.attr('disabled', true);
            },
            // 応答後
            complete: function(xhr, textStatus) {
                // ボタンを有効化し、再送信を許可
                $button.attr('disabled', false);
            },
            
            // 通信成功時の処理
            success: function(result, textStatus, xhr) {
                $('table').fadeIn();
                $('#result_total_train_fare').val(result.total_train_fare);
                $('#result_salary').val(result.salary);
                $('#result_rent').val(result.rent);
                $('#result_food_expenses').val(result.food_expenses);
                $('#result_living_expences').val(result.living_expences);
                $('#result_total_payment').val(result.total_payment);
                $('#last_result_total_payment').val(result.total_payment);
                $('#result_pre_free_use_money').val(result.pre_free_use_money);

                if ($('#result_payment_for_student_loan').length) {
                    $('#result_payment_for_student_loan')
                        .val(result.payment_for_student_loan);
                }

                $('#result_free_use_money').val(result.free_use_money);
            },
            
            // 通信失敗時の処理
            error: function(xhr, textStatus, error) {}
        });
    });
});

