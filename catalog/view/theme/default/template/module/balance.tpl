<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div id="content" class="col-sm-12">
        <div class="content-trangmuathe">
            <h3 style="width: 100%; text-transform: uppercase; ">SỐ DƯ TÀI KHOẢN</h3>
            <?php if($error_mess != ''): ?>
                <p class="mess_err"><?php echo $error_mess; ?></p>
            <?php endif; ?>

            <?php if($balance_detail != ''): ?>
            <div class="form_box">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <tr>
                    <td class="text-left">Tên tài khoản</td>
                    <td class="text-right"><?php echo $balance_detail->partnerName; ?></td>
                  </tr>
                  <tr>
                    <td class="text-left">Số dư hiện tại</td>
                    <td class="text-right"><?php echo $balance_detail->balance_money; ?></td>
                  </tr>
                  <tr>
                    <td class="text-left">Số nợ</td>
                    <td class="text-right"><?php echo $balance_detail->balance_debit; ?></td>
                  </tr>
                  <tr>
                    <td class="text-left">Số tiền thưởng</td>
                    <td class="text-right"><?php echo $balance_detail->balance_bonus; ?></td>
                  </tr>
                  <tr>
                    <td class="text-left">Số dư khả dụng</td>
                    <td class="text-right"><?php echo $balance_detail->balance_avaiable; ?></td>
                  </tr>
                </table>
              </div> 
            </div><!--end .form_box-->
            <?php endif; ?>
        </div>
        
    </div><!--end .container-->
   </div>
</div>
<?php echo $footer; ?>