<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <script type="text/javascript">if(parent.frames.length!==0){top.location='<?=site_url('pos')?>';}</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png" />
    <link href="<?= $assets ?>css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>helpers/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>helpers/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
    <link href="<?= $assets ?>css/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-theme02">
    <div id="login-page">
        <div class="container">
            <?php echo form_open('auth/reset_password/' . $code, 'class="form-login"');?>
            <h2 class="form-login-heading"><strong><?= $Settings->site_name; ?></strong></h2>
            <div class="login-wrap">
            <p><?php echo sprintf(lang('reset_password_email'), $identity_label); ?></p>
                <!-- <input type="text" class="form-control" name="identity" placeholder="<?=lang('email'); ?>" autofocus> -->
                <?php echo form_input($new_password);?>
                <br>
                <!-- <input type="password" class="form-control" name="password" placeholder="<?=lang('password'); ?>"> -->
                <?php echo form_input($new_password_confirm);?>
                <?php echo form_input($user_id);?>
                <?php echo form_hidden($csrf); ?>
                <label></label>
                <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> <?=lang('reset_password'); ?></button>
                <hr>
                <div class="registration">
                    Don't need to reset password?<br/>
                    <a class="" href="<?=site_url('login');?>">
                        <?= lang('back_to_login'); ?>
                    </a>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

    <script src="<?= $assets ?>js/jquery.js"></script>
    <script src="<?= $assets ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>helpers/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            <?php if(isset($message)) { ?>
                var success_noti = $.gritter.add({
                    title: 'Success!',
                    text: '<?=str_replace(array("\r", "\n"), "", $message);?>',
                    image: '',
                    sticky: true,
                    time: '',
                    class_name: 'bg-theme03 border-round'
                });
                <?php } ?>
                <?php if(isset($error)) { ?>
                    var error_noti = $.gritter.add({
                        title: 'Error!',
                        text: '<?=str_replace(array("\r", "\n"), "", $error);?>',
                        image: '',
                        sticky: true,
                        time: '',
                        class_name: 'bg-theme04 border-round'
                    });
                    <?php } ?>
                    return false;
                });
    </script>
</body>
</html>
