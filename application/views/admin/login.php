<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
      <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/fontawesome-free/css/all.min.css"); ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/icheck-bootstrap/icheck-bootstrap.min.css"); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/dist/css/adminlte.min.css"); ?>">

  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><img style="margin-top: -30px;" src="<?php echo base_url("images/logo.png"); ?>"></a>
      </div><!-- /.login-logo -->
      <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"> <?php echo $this->lang->line("Sign in to your account"); ?></p>
        <?php if(isset($error) && $error!=""){
                            echo $error;
                        } ?>
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="<?php echo $this->lang->line("Email"); ?>" required="" />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="<?php echo $this->lang->line("Password"); ?>" required="" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember"  /> <?php echo $this->lang->line("Remember Me"); ?>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">  <?php echo $this->lang->line("Sign In"); ?></button>
            </div><!-- /.col -->
          </div>
        </form>
      </div>
      
        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->


  <!-- jQuery -->
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/jquery/jquery.min.js"); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src=".<?php echo base_url($this->config->item("theme_admin") . "/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
  <!-- AdminLTE App -->
  <script src=".<?php echo base_url($this->config->item("theme_admin") . "/dist/js/adminlte.min.js"); ?>"></script>

  </body>
</html>
