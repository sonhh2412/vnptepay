
<div class="form-group captcha-possition">
  <?php if (substr($route, 0, 9) == 'checkout/') { ?>
  <label class="control-label" for="input-payment-captcha"><?php echo $entry_captcha; ?></label>
  <input type="text" name="captcha" id="input-payment-captcha" class="form-control" autocomplete="off" />
  <img src="index.php?route=extension/captcha/basic_captcha/captcha" alt="" />
  <?php } else { ?>
  <label class=" control-label" for="input-captcha">Mã bảo mật</label>
  <div class="captcha-content-pos">
    <input type="text" name="captcha" id="input-captcha" placeholder="Nhập mã bảo mật" class="form-control" />
    <img src="index.php?route=extension/captcha/basic_captcha/captcha" alt="" />
    <?php if ($error_captcha) { ?>
    <div class="text-danger"><?php echo $error_captcha; ?></div>
    <?php } ?>
  </div>
  <?php } ?>
</div>

