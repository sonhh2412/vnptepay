<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div id="content" class="col-sm-12">
        <div class="content-trangmuathe">
            <h3 style="width: 100%; text-transform: uppercase; ">Kiểm tra kết quả giao dịch Airtime</h3>
            <div class="form_box">
                <form name="frm_megabank" id="frm_megabank" action="<?php echo $action; ?>" method="post"  class="form-horizontal">
                    <div class="form-group ">
                        <div class="col-sm-4 required">
                          <input type="text" name="request_id" placeholder="Nhập mã giao dịch cần tải lại thẻ" value="" class="form-control" />
                          <?php if ($error_request_id) { ?>
                          <div class="text-danger"><?php echo $error_request_id; ?></div>
                          <?php } ?>
                        </div>
                    </div>

                    <div class="form-group required">
                        <div class="col-sm-4">
                          <input type="password" name="password" placeholder="Nhập mã quản lý" value="" class="form-control" />
                          <?php if ($error_password) { ?>
                          <div class="text-danger"><?php echo $error_password; ?></div>
                          <?php } ?>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <input type="submit" value="Kiểm tra giao dịch" class="btn btn-primary" />
                        </div>
                    </div>
                </form>
                <?php if($error_mess != ''): ?>
                    <p class="mess_err"><?php echo $error_mess; ?></p>
                <?php endif; ?>
                <?php if($success_mess != ''): ?>
                    <p class="mess_success"><?php echo $success_mess; ?></p>
                <?php endif; ?>
            </div><!--end .form_box-->
        </div>
        
    </div><!--end .container-->
   </div>
</div>
<?php echo $footer; ?>