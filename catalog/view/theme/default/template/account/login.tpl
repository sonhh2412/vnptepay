<?php echo $header; ?>
<div class="container">
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row">
    <div id="content" class="col-sm-12 ">
      <div class="login-page">
        <div class="row">
          <div class="col-sm-7 col-left">          
          </div>
          <div class="col-sm-5 col-right">
            <div class="well">
              <h2>ĐĂNG NHẬP</h2>            
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-login">
                <div class="form-group">
                  <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                </div>
                <div class="form-group">
                  <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                  <!-- <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a> -->
                </div>
                <?php echo $captcha; ?>
                <input type="submit" value="Đăng nhập" class="btn btn-primary" />
                <?php if ($redirect) { ?>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                <?php } ?>
              </form>
            </div>
          </div>
        </div>
      </div>
     </div>
    </div>
</div>
<?php echo $footer; ?>