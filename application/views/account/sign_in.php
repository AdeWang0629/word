<!DOCTYPE html>
<html lang="jp">
<head>
    <?php $this->load->view('components/head'); ?>
<!--    <script>-->
<!--        jQuery(document).ready(function ($) {-->
<!--            -->
<!---->
<!--        });-->
<!---->
<!--    </script>-->
    <style type="text/css">
        .ajax_loading_aria {
            width: 100%;
            top: 70px;
            left: 0;
            height: 100%;
            position: absolute;
            text-align: center;
            z-index: 120;
        }

        .ime_mode_inactive {
            ime-mode: inactive;
        }

        .ime_mode_active {
            ime-mode: active;
        }

        .btn {
            font-size: 13px;
        }
        .menu_button_selected {
            border:2px solid red;
        }
    </style>


</head>
<body>
<?php
if($_SERVER['SERVER_NAME'] === 'localhost'){
    $mother_url = 'http://localhost/keipro_all/';
}else{
    $mother_url = 'https://keipro.development.dhaka10.dev.jacos.jp/';
}
?>
<div class="" style="width:100%; margin: 0 auto">

    <div class="col-lg-3"></div>

    <div class="col-md-5 col-sm-12 col-xs-12 col-lg-6" style="margin-top: 10%;">

        <div id="login_options_div"
             style="display: none;text-align: center; border: 2px solid #385D8A; border-radius: 5px; padding: 10px;"
             class="panel panel-default">
            <div id="word_app" onclick="ajax_login(this.id,this);" class="btn btn-success"
                 style="font-size: 18px; margin-right: 10px;">
                ワープロ
            </div>
            <div id="settlement_page" onclick="ajax_login(this.id,this);" class="btn btn-success" style="font-size: 18px; margin-right: 10px;">
                ペーパーレス
            </div>
            <div id="email_screen" onclick="ajax_login(this.id,this);" class="btn btn-success" style="font-size: 18px; ">
                メール
            </div>
            <div id="select_login_menu_message" style="display:; margin-top: 10px; color: red; font-weight: bold;">業務を選択して、ログインしてください</div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: center;">
                <div class="panel-title" style="display: inline-block; float: left;"><strong>ログイン</strong></div>
                <div class="panel-title" style="display: inline-block; font-size: 20px;"><strong>ワープロ</strong></div>
                <div class="panel-title" style="display: inline-block; float: right;">
                    <button type="button" class="btn btn-warning" id="return_to_home_page" onclick="window.location.href = $('#mother_url').val();" >戻る
                    </button>
                </div>
            </div>

            <div style="padding-top:30px;" class="panel-body">
                <?php echo form_open(base_url('index.php/account/sign_in/userAuthorization') . ($this->input->get('continue') ? '/?continue=' . urlencode($this->input->get('continue')) : ''), 'class="form-horizontal" id ="login-form"'); ?>
                <?php echo form_fieldset(); ?>
                <input type="hidden" id="base_url" name="base_url" value="<?= base_url(); ?>">
                <input type="hidden" id="mother_url" name="mother_url" value="<?= $mother_url; ?>">
                <input type="hidden" id="login_menu_type" name="login_menu_type" value="1">

                <?php if (isset($sign_in_error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <a class="close" data-dismiss="alert" href="#">x</a><?php echo $sign_in_error; ?>
                    </div>
                <?php endif; ?>

                <?php if (form_error('sign_in_username_email') || isset($sign_in_username_email_error)) : ?>
                    <p class="text-danger">
                        <?php echo form_error('sign_in_username_email'); ?>
                        <?php if (isset($sign_in_username_email_error)) : ?>
                            <span class="field_error"><?php echo $sign_in_username_email_error; ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                <?php if (form_error('sign_in_password')) : ?>
                    <p class="text-danger">
                        <?php echo form_error('sign_in_password'); ?>

                    </p>
                <?php endif; ?>

                <!-- Text Username/ Mobile-->
                <div class="form-group <?= (form_error('sign_in_username_email') || isset($sign_in_username_email_error)) ? 'has-error' : ''; ?>">
                    <label class="col-md-3 control-label" for="sign_in_username_email">携帯番号</label>
                    <div class="col-md-9">
                        <input id="sign_in_username_email" type="text" class="form-control" autofocus="true"
                               name="sign_in_username_email" style="ime-mode:active" required="required"
                               value="<?= set_value('sign_in_username_email') != "" ? set_value('sign_in_username_email') : '' ?>"
                               placeholder="携帯番号">

                    </div>
                </div>

                <!-- Password input-->
                <div class="form-group <?= (form_error('sign_in_password')) ? 'has-error' : ''; ?>">
                    <label class="col-md-3 control-label" for="sign_in_password">パスワード</label>
                    <div class="col-md-9">
                        <input id="sign_in_password" onpaste="return false;" type="password" required="required"
                               class="form-control" name="sign_in_password" placeholder="パスワード"
                               value="<?= set_value('sign_in_password') != "" ? set_value('sign_in_password') : '' ?>">

                    </div>
                </div>
                <div class="form-group" id="error_display" style="display: none;">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-9">
                        <span id="error_display_message" class="help-block" style="color: red; font-weight: bold;"> </span>
                    </div>
                </div>
                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-3 control-label" for="singlebutton"></label>
                    <div class="col-md-12">
                        <button style="margin-left: 32px;" type="submit" id="singlebutton" title="ログイン"
                                name="singlebutton"
                                class="btn btn-success"><i class="fa fa-sign-in" aria-hidden="true"></i> ログイン
                        </button>
                        <button type="button" id="sign_up" title="新規登録" name="singlebutton"
                                class="btn btn-default btn-sub"><i class="fa fa-user-plus" aria-hidden="true"></i> 新規登録
                        </button>
                        <button type="button" id="change_pass" title="パスワード変更" name="singlebutton"
                                class="btn btn-default btn-sub"
                                style="background: red;border-color: blue; color: white; border-width:2px; "><i
                                    class="fa fa-lock" aria-hidden="true"></i> パスワード変更
                        </button>
                        <button type="button" id="forgot_password" title="パスワードを忘れた方
" name="singlebutton"
                                class="btn btn-default btn-sub"
                                style="background: blue; color: white; border-width:2px; border: 1px solid blue; border-radius: 5px; ">
                            <i class="fa fa-lock" aria-hidden="true"></i> パスワードを忘れた方
                        </button>
                    </div>
                </div>
                <dir class="ajax_loading_aria" style="display: none;" id="ajax_loading_aria">
                    <center><img src="resource/img/ajax/ajax_load_6.gif"></center>
                </dir>
            </div><!-- panel-body -->


            <?php echo form_fieldset_close(); ?>
            <?php echo form_close(); ?>
        </div>

    </div> <!-- col-md-5 -->
    <div style="clear: both;"></div>
    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="sign_up_aria"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px;">
        <div class="panel panel-default" style="margin-bottom: 0;">
            <div class="panel-body">
                <form class="form-horizontal">
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="singlebutton"></label>
                        <div class="col-md-9">
                            <button type="button" id="save_sign_up" title="登録" name="singlebutton"
                                    class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> 登録
                            </button>
                            <button type="button" id="close_sign_up" title="戻る" name="singlebutton"
                                    class="btn btn-default "><i class="fa fa-user-close" aria-hidden="true"></i> 戻る
                            </button>
                        </div>
                    </div>
                    <!-- Text Username/ Mobile-->
                    <div class="form-group <?= (form_error('sign_up_username') || isset($sign_up_username)) ? 'has-error' : ''; ?>">
                        <label class="col-md-3 control-label" for="sign_up_username">携帯番号</label>
                        <div class="col-md-9">
                            <input id="sign_up_username" style="ime-mode:active" type="text" class="form-control"
                                   name="sign_up_username" required="required"
                                   value="<?= set_value('sign_up_username') ?>" placeholder="携帯番号">

                        </div>
                    </div>
                    <!-- Name input-->
                    <div class="form-group <?= (form_error('sign_up_name') || isset($sign_up_name)) ? 'has-error' : ''; ?>">
                        <label class="col-md-3 control-label" for="sign_up_name">個人名</label>
                        <div class="col-md-9">
                            <input id="sign_up_name" style="ime-mode:active" type="text" class="form-control"
                                   name="sign_up_name" required="required"
                                   value="<?= set_value('sign_up_name') ?>" placeholder="個人名">
                            <input type="hidden" id="user_type" name="user_type"
                                   value="1">
                        </div>
                    </div>
                    <!-- Company Name input-->
                    <div class="form-group <?= (form_error('company_name') || isset($company_name)) ? 'has-error' : ''; ?>">
                        <label class="col-md-3 control-label" for="company_name">会社名</label>
                        <div class="col-md-9">
                            <input id="company_name" style="ime-mode:active" type="text" class="form-control"
                                   name="company_name" required="required"
                                   value="<?= set_value('company_name') ?>" placeholder="会社名">
                        </div>
                    </div>

                    <!-- Password input-->
                    <div class="form-group <?= (form_error('sign_up_password')) ? 'has-error' : ''; ?>">
                        <label class="col-md-3 control-label" for="sign_up_password">パスワード</label>
                        <div class="col-md-9">
                            <input id="sign_up_password" type="password" required="required" class="form-control"
                                   name="sign_up_password" onpaste="return false;" placeholder="パスワード（4桁以上）"
                                   value="<?= set_value('sign_up_password') ?>">

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="sign_up_success"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px;">

        <div class="panel panel-success" style="margin-bottom: 0">
            <div class="panel-body">
                <p>携帯番号 : <span id="sign_up_phone_show"></span></p>
                <p>個人名 : <span id="sign_up_name_show"></span></p>
                <p>会社名 : <span id="company_name_show"></span></p>
                <p>パスワード : <span id="sign_up_password_show"></span></p>
                <p class="text-success">登録が完了しました。</p>
                <button type="button" class="btn btn-default" id="success_close">戻る</button>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="change_pass_aria"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px; border: 2px solid #446590; border-radius: 0.5em; background-color: #EBF1DE;">
        <div style="text-align: center">
            <p style="color: black; font-family: ms mincho, ｍｓ 明朝; font-size: 16.666667px; font-weight: bold;">
                パスワードを変更しますか？　
            </p>
            <p>
                <button type="button" id="show_change_pass_form" class="btn btn-light_green"
                        style="border: 2px solid #46658C; margin-right: 10px;"> はい
                </button>
                <button type="button" id="close_change_pass_aria" class="btn btn-light_blue"
                        style="border: 2px solid #46658C; margin-right: 10px;"> いいえ
                </button>
            </p>
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="change_pass_form"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px; border: 2px solid #446590; border-radius: 0.5em; background-color: #EBF1DE;">
        <div style="width:auto; text-align: right">

            <button type="button" class="btn btn-danger" id="change_pass_form_close"> 戻る
            </button>

        </div>
        <div style="width:auto; text-align: left; font-family: ms mincho, ｍｓ 明朝; font-size: 18.666667px;">
            変更　
        </div>
        <div class="clearfix"></div>
        <div style="text-align: left; font-family: ms mincho, ｍｓ 明朝; font-size: 18.666667px;">
            現在のパスワード <input onblur="check_if_num_or_char();" class="ime_mode_active" type="tel"
                            id="user_current_password" name="user_current_password"
                            style="background-color: #B7DEE8; border: 2px solid #446590; border-radius: 0.5em; padding: 7px; margin-bottom: 10px; text-align: center;"
            ><br>
            新パスワード　
            <input type="text" id="user_new_password" name="user_new_password"
                   style="background-color: #FFCCFF; border: 2px solid #446590; border-radius: 0.5em; padding: 7px; margin-bottom: 10px; margin-left: 18px; text-align: center; ime-mode: inactive"
                   maxlength="4" placeholder="４ケタ">
            <p style="text-align: center">
                <button type="button" id="done_change_pass" class="btn btn-yellow" style="border: 2px solid #46658C;">
                    変更
                </button>
            </p>
        </div>
    </div>

    <!--    start forgot_pass_form-->
    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="forgot_pass_form"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px; border: 2px solid #446590; border-radius: 0.5em; background-color: #EBF1DE;">
        <div style="width:auto; text-align: right">

            <button type="button" class="btn btn-success" id="forgot_pass_form_close"> 戻る
            </button>

        </div>
        <div style="width:auto; text-align: left; font-family: ms mincho, ｍｓ 明朝; font-size: 18.666667px;">
            パスワードを忘れた方　
        </div>
        <div class="clearfix"></div>
        <div style="text-align: left; font-family: ms mincho, ｍｓ 明朝; font-size: 18.666667px;">
            携帯番号　<input onblur="check_if_num_or_char();" class="ime_mode_active" placeholder="携帯番号" type="tel"
                        id="user_phone_number" name="user_phone_number"
                        style="background-color:#CCFF99; border: 2px solid #446590; border-radius: 0.5em; padding: 7px; margin-bottom: 10px; margin-left: 7px; text-align: center;"
            >
            <!--            <br>-->
            <!--            再入力　-->
            <!--            <input type="text" id="user_forgot_new_password" name="user_forgot_new_password"-->
            <!--                   style="background-color: #FFCCFF; border: 2px solid #446590; border-radius: 0.5em; padding: 7px; margin-bottom: 10px; margin-left: 18px; text-align: center; ime-mode: inactive"-->
            <!--                   placeholder="パスワード">-->
            <p style="text-align: center">
                <span style="font-size: 16px;">携帯番号を入力して、送信ボタンを押してください。<br>
　携帯へ新パスワードを送ります。</span><br>
                <button type="button" id="done_forgot_pass9999" class="btn btn-yellow"
                        style="border: 2px solid #46658C;display: none;">
                    送信
                </button>
                <a id="done_forgot_pass" style="border: 2px solid #46658C; color: black;" class="btn btn-yellow"
                   href="#">送信</a>

            </p>
        </div>
    </div>
    <!--    end forgot_pass_form-->

    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="user_change_pass_error_message"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px; border: 2px solid #446590; border-radius: 0.5em; background-color: #EBF1DE; text-align: center;">
        <p style="text-align: center; padding: 10px;"><span id="error_message_text"></span></p>
        <button type="button" class="btn btn-default" id="message_close">確認
        </button>
    </div>

    <div class="col-md-4 col-sm-4 col-xs-12 pull-right hide" id="success_message"
         style="position: fixed; right: 0px; bottom: 0px; padding: 4px; border: 2px solid #446590; border-radius: 0.5em; background-color: #EBF1DE; text-align: center;">
        <p style="text-align: center; padding: 10px;"><span id="success_message_text"
                                                            style="font-size: 18px; font-weight: bold;"></span></p>
        <button type="button" class="btn btn-success" id="success_message_close" style="border: 2px solid blue;">確認
        </button>
    </div>

</div> <!-- container_lg -->

<?php $this->load->view('components/footer'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).mouseup(function (e) {
            var user_change_pass_error_message = $("#user_change_pass_error_message");
            var success_message = $("#success_message");
            if (!user_change_pass_error_message.is(e.target) && user_change_pass_error_message.has(e.target).length === 0) {
                user_change_pass_error_message.removeClass('show').addClass('hide');
            }
            if (!success_message.is(e.target) && success_message.has(e.target).length === 0) {
                success_message.removeClass('show').addClass('hide');
            }


        });

        $("#sign_in_username_email").val(localStorage['username']);
        $("#sign_in_password").val(localStorage['password']);
        var form = $("#login-form");
        form.submit(function (event) {
            event.preventDefault();
            var username = $("#sign_in_username_email").val();
            var password = $("#sign_in_password").val();
            localStorage.setItem("user_login_password", password);

            var base_url = $("#base_url").val();
            var url = base_url + "index.php/account/sign_in/userAuthorization";
            $.ajax({
                url: url,
                type: 'POST',
                beforeSend: function () {
                    $("#ajax_loading_aria").show();
                },
                data: {sign_in_username_email: username, sign_in_password: password},
            })
                .done(function (data) {
//                        alert(data);console.log(data);die();
                    var resposnse = JSON.parse(data);
                    if (resposnse.message == 'success') {

                        localStorage['username'] = username;
                        localStorage['password'] = password;
                        window.location.href = base_url;
                    } else {
                        if(resposnse.message == '電話番号登録がありません。'){
                                document.getElementById('error_display_message').innerText = resposnse.message;
                            }
                            if(resposnse.message == 'パスワードが正しくありません。'){
                                document.getElementById('error_display_message').innerText = resposnse.message;
                            }
                        $("#ajax_loading_aria").hide();
                        $("#error_display").show();
                    }
                })
                .fail(function () {
                    console.log("error");
                })
                .always(function () {
                    $("#ajax_loading_aria").hide();
                    console.log("complete");
                });
        });

//        $("#sign_in_username_email").val(localStorage['username']);
//        $("#sign_in_password").val(localStorage['password']);
//        var form = $("#login-form");
//        form.submit(function (event) {
//            event.preventDefault();
//                var username = $("#sign_in_username_email").val();
//                var password = $("#sign_in_password").val();
////                var login_menu_type = $("#login_menu_type").val();
////                if(login_menu_type==0){
////                    $("#select_login_menu_message").show();
////                }else{
////                    $("#select_login_menu_message").show();
//                localStorage.setItem("user_login_password", password);
//
//                var base_url = $("#base_url").val();
//                var url = base_url + "index.php/account/sign_in/userAuthorization";
//                $.ajax({
//                    url: url,
//                    type: 'POST',
//                    beforeSend: function () {
//                        $("#ajax_loading_aria").show();
//                    },
//                    data: {sign_in_username_email: username, sign_in_password: password},
//                })
//                    .done(function (data) {
////                        alert(data);console.log(data);die();
//                        var resposnse = JSON.parse(data);
//                        if (resposnse.message == 'success') {
//
//                            localStorage['username'] = username;
//                            localStorage['password'] = password;
//                            window.location.href = base_url;//'https://google.com'
////                            if (login_menu_type == 1)//'word_app'
////                                window.location.href = base_url;//'https://google.com'
////                            else if (login_menu_type == 2)//'settlement_page'
////                                window.location.href = base_url + 'index.php/wordapp/view_settlement_form/';
////                            else if (login_menu_type == 3)//'email_screen'
////                                window.location.href = base_url + 'index.php/emailing/view_email_screen/';
//                        } else {
//                            if(resposnse.message == '電話番号登録がありません。'){
//                                document.getElementById('error_display_message').innerText = resposnse.message;
//                            }
//                            if(resposnse.message == 'パスワードが正しくありません。'){
//                                document.getElementById('error_display_message').innerText = resposnse.message;
//                            }
//                            $("#ajax_loading_aria").hide();
//                            $("#error_display").show();
//
//                        }
//                    })
//                    .fail(function () {
//                        console.log("error");
//                    })
//                    .always(function () {
//                        $("#ajax_loading_aria").hide();
//                        console.log("complete");
//                    });
//                }
//        });

//        $("#singlebutton").click(function (event) {
////            alert('hi');
//            $("#login_options_div").show();
//        });
        $("#sign_up").click(function (event) {
//            var login_menu_type = $("#login_menu_type").val();
//            if(login_menu_type==0){
//                $("#select_login_menu_message").show();
//            }else{
                $("#sign_up_aria").removeClass("hide").addClass("show");
                $("#change_pass_aria").removeClass("show").addClass("hide");
                $("#change_pass_form").removeClass("show").addClass("hide");
                $("#sign_up_username").val("");
                $("#sign_up_name").val("");
                $("#company_name").val("");
                $("#sign_up_password").val("");
//            }


            /* Act on the event */
        });
        $("#close_sign_up").click(function (event) {
            $("#sign_up_aria").removeClass("show").addClass("hide");
        });
        $("#success_close").click(function (event) {
            $("#sign_up_success").removeClass("show").addClass("hide");
        });

        $("#change_pass").click(function (event) {
            $("#change_pass_aria").removeClass("hide").addClass("show");
            $("#sign_up_aria").removeClass("show").addClass("hide");
            /* Act on the event */
        });

        $("#close_change_pass_aria").click(function (event) {
            $("#change_pass_aria").removeClass("show").addClass("hide");
        });
        $("#show_change_pass_form").click(function (event) {
            $("#change_pass_form").removeClass("hide").addClass("show");
            $("#sign_up_aria").removeClass("show").addClass("hide");
            $("#user_new_password").val('');
            $("#user_current_password").val('');
            /* Act on the event */
        });
        $("#change_pass_form_close").click(function (event) {
            $("#change_pass_form").removeClass("show").addClass("hide");
        });

        $("#forgot_password").click(function (event) {
            $("#forgot_pass_form").removeClass("hide").addClass("show");
            $("#user_phone_number").val('');
            $("#user_forgot_new_password").val('');
        });
        $("#forgot_pass_form_close").click(function (event) {
            $("#forgot_pass_form").removeClass("show").addClass("hide");
        });
        $("#message_close").click(function (event) {
            $("#user_change_pass_error_message").removeClass("show").addClass("hide");
        });
        $("#success_message_close").click(function (event) {
            $("#success_message").removeClass("show").addClass("hide");
        });

        $("#save_sign_up").click(function (event) {
            var username = $("#sign_up_username").val();
            var password = $("#sign_up_password").val();
            var sign_up_name = $("#sign_up_name").val();
            var company_name = $("#company_name").val();
            var user_type = $("#user_type").val();
//            var login_menu_type = $("#login_menu_type").val();

            if (username == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '個人名、会社名、携帯番号、パスワード、関連番号を入力して下さい。';
//                alert("氏名、携帯番号、パスワード、関連番号を入力して下さい。")
                return false;
            } else if (password == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '個人名、会社名、携帯番号、パスワード、関連番号を入力して下さい。';
//                alert("氏名、携帯番号、パスワード、関連番号を入力して下さい。")
                return false;
            } else if (sign_up_name == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '氏名、会社名、携帯番号、パスワード、関連番号を入力して下さい。';
//                alert("氏名、携帯番号、パスワード、関連番号を入力して下さい。")
                return false;
            } else if (company_name == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '氏名、会社名、携帯番号、パスワード、関連番号を入力して下さい。';
//                alert("氏名、携帯番号、パスワード、関連番号を入力して下さい。")
                return false;
            }
            $.post('index.php/account/sign_up', {
                sign_up_username: username,
                sign_up_password: password,
                sign_up_name: sign_up_name,
                company_name: company_name,
                user_type: user_type,
                company_id: 0,
                user_id: 0
            }, function (resposnse) {
                console.log(resposnse);
                var res_data = JSON.parse(resposnse);
                if (res_data.message == "success") {
                    $("#user_change_pass_error_message").removeClass("show").addClass("hide");
                    $("#sign_in_username_email").val(username);
                    $("#sign_in_password").val(password);
                    document.getElementById('sign_up_phone_show').innerText = username;
                    document.getElementById('sign_up_name_show').innerText = sign_up_name;
                    document.getElementById('company_name_show').innerText = company_name;
                    document.getElementById('sign_up_password_show').innerText = password;
                    $("#sign_up_aria").removeClass("show").addClass("hide");
                    $("#sign_up_success").removeClass("hide").addClass("show");
                }

                if (res_data.message == "exist") {
                    $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                    document.getElementById('error_message_text').innerText = 'この携帯番号は既に登録しました。';
//                    alert('この携帯番号は既に登録しました。');
                    // $( "#sign_up_exist_aria" ).removeClass( "hide" ).addClass( "show" );
                }
            });
        });

        $("#done_change_pass").click(function (event) {
            var user_new_password = $("#user_new_password").val();
            var user_current_password = $("#user_current_password").val();
            var username = $("#sign_in_username_email").val();
//            alert(username);
//            die();
            if (user_new_password == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '新パスワード を入力して下さい。';
//                alert(" 新パスワード を入力して下さい。")
                return false;
            } else if (user_current_password == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '現在のパスワード を入力して下さい。';
//                alert("現在のパスワード を入力して下さい。")
                return false;
            }
            $.post('index.php/account/account_password/user_change_password', {
                user_new_password: user_new_password,
                user_current_password: user_current_password,
                username: username
            }, function (response) {
                var result = response.trim();
//                alert(result);die();
                if (result == 1) {
                    $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                    document.getElementById('error_message_text').innerText = '登録された携帯番号を入力してください。';
                }
                if (result == 2) {
                    $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                    document.getElementById('error_message_text').innerText = 'パスワードが間違いです。';
                }
                if (result == 3) {
                    $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                    $("#change_pass_form").removeClass("show").addClass("hide");
                    $("#change_pass_aria").removeClass("show").addClass("hide");
                    document.getElementById('error_message_text').innerText = 'パスワードを変更しました。';
                    $("#sign_in_password").val(user_new_password);
                }

            });
        });

        $("#done_forgot_pass").click(function (event) {
//            var user_forgot_new_password = $("#user_forgot_new_password").val();
            var user_phone_number = $("#user_phone_number").val();
//            alert(username);
//            die();
            if (user_phone_number == "") {
                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                document.getElementById('error_message_text').innerText = '新パスワード を入力して下さい。';
//                alert(" 新パスワード を入力して下さい。")
                return false;
            }
//            else if (user_forgot_new_password == "") {
//                $("#user_change_pass_error_message").removeClass("hide").addClass("show");
//                document.getElementById('error_message_text').innerText = '新パスワード を入力して下さい。';
//                return false;
//            }
            var new_pass = Math.floor(1000 + Math.random() * 9000);
            $("#done_forgot_pass").attr('href', 'sms:' + user_phone_number + '?body=これはＰＬ会計のパスワードです ' + new_pass);

            $.post('index.php/account/account_password/user_forgot_password', {
                user_forgot_new_password: new_pass,
                user_phone_number: user_phone_number
            }, function (response) {
                var result = response.trim();
//                alert(result);
//                die();
                if (result == 1) {
                    $("#user_change_pass_error_message").removeClass("hide").addClass("show");
                    document.getElementById('error_message_text').innerText = '携帯番号が間違いです。再確認お願い致します。';
                }
                if (result == 2) {
                    $("#success_message").removeClass("hide").addClass("show");
                    $("#forgot_pass_form").removeClass("show").addClass("hide");
//                    $("#change_pass_aria").removeClass("show").addClass("hide");
                    document.getElementById('success_message_text').innerText = 'あなたのパスワードは ' + new_pass + ' です。';
                    $("#sign_in_password").val(new_pass);
                    $("#sign_in_username_email").val(user_phone_number);
                }

            });
        });

    });

    function ajax_login(id,btn) {
//        document.getElementById('login-form').submit();
//die();
        $("#error_display").hide();
        $("#select_login_menu_message").show();
        $('div.btn-success').removeClass("menu_button_selected");
        $(btn).addClass("menu_button_selected");

//        if (id == 'word_app')
//            $("#login_menu_type").val(1);
//        else if (id == 'settlement_page')
//            $("#login_menu_type").val(2);
//        else if (id == 'email_screen')
//            $("#login_menu_type").val(3);


//        var username = $("#sign_in_username_email").val();
//        var password = $("#sign_in_password").val();
//        localStorage.setItem("user_login_password", password);
//
//        var base_url = $("#base_url").val();
//        if(username=='farjana')
//        var url = base_url + "index.php/account/sign_in/testAuthorization";
//        else
//        var url = base_url + "index.php/account/sign_in/userAuthorization";
//        $.ajax({
//            url: url,
//            type: 'POST',
//            beforeSend: function () {
//                $("#ajax_loading_aria").show();
//            },
//            data: {sign_in_username_email: username, sign_in_password: password},
//        })
//            .done(function (data) {
////                        alert(data);
//                        console.log(data);
////                        die();
//                var resposnse = JSON.parse(data);
//                if (resposnse.message == 'success') {
////                    console.log(resposnse);
//                    localStorage['username'] = username;
//                    localStorage['password'] = password;
//                    if (id == 'word_app')
//                        window.location.href = base_url;//'https://google.com'
//                    else if (id == 'settlement_page')
//                        window.location.href = base_url + 'index.php/wordapp/view_settlement_form/';
//                    else if (id == 'email_screen')
//                        window.location.href = base_url + 'index.php/emailing/view_email_screen/';
//                } else {
//                    $("#ajax_loading_aria").hide();
//                    $("#error_display").show();
//                }
//            })
//            .fail(function () {
//                console.log("error");
//            })
//            .always(function () {
//                $("#ajax_loading_aria").hide();
//                console.log("complete");
//            });
    }

    function check_if_num_or_char() {
        var user_phone_number = $("#user_phone_number").val();
//            var containsJapanese = user_phone_number.match(/[\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf]/);
//            alert(containsJapanese);
//            if (containsJapanese){
//                $("#user_phone_number").removeClass('ime_mode_inactive').addClass('ime_mode_active');
//            }else{
//                $("#user_phone_number").removeClass('ime_mode_active').addClass('ime_mode_inactive');
//            }

        if (isNaN(user_phone_number)) {
            $("#user_phone_number").removeClass('ime_mode_inactive').addClass('ime_mode_active');
        } else {
            $("#user_phone_number").removeClass('ime_mode_active').addClass('ime_mode_inactive');
        }
    }

</script>
</body>
</html>