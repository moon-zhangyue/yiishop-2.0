<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!-- ============================================================= HEADER : END ============================================================= -->		<!-- ========================================= MAIN ========================================= -->
<main id="authentication" class="inner-bottom-md">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <section class="section sign-in inner-right-xs">
                    <h2 class="bordered">完善信息</h2>
                    <p>请填写用户名  密码</p>

                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => '<div class="field-row">{label}{input}</div>{error}'
                        ],
                        'options' => [
                            'class' => 'login-form cf-style-1',
                            'role' => 'form',
                        ],
                        'action' => ['member/qqreg'],
                    ]); ?>
                    <?php echo $form->field($model, 'username')->textInput(['class' => 'le-input']); ?>
                    <?php echo $form->field($model, 'userpass')->passwordInput(['class' => 'le-input']); ?>
                    <?php echo $form->field($model, 'repass')->passwordInput(['class' => 'le-input']); ?>
                    <div class="field-row clearfix">
                        <?php echo $form->field($model, 'rememberMe')->checkbox([
                            'template' => '<span class="pull-left"><label class="content-color">{input} <span class="bold">记住我</span></label></span>',
                            'class' => "le-checkbox auto-width inline",
                        ]); ?>
                        <span class="pull-right">
                        		<a href="#" class="content-color bold">忘记密码 ?</a>
                        	</span>
                    </div>

                    <div class="buttons-holder">
                        <?php echo Html::submitButton('安全登录', ['class' => 'le-button huge']); ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </section>
            </div>


        </div><!-- /.row -->
    </div><!-- /.container -->
</main><!-- /.authentication -->
<!-- ========================================= MAIN : END ========================================= -->		<!-- ============================================================= FOOTER ============================================================= -->
<script>
    var qqbtn = document.getElementById("login_qq");
    qqbtn.onclick = function(){
        windows.location.href = "<?php echo \yii\helpers\Url::to(['member/qqlogin']) ?>";
    }
</script>