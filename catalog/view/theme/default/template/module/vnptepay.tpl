<?php echo $header; ?>
<div class="container">
  <div class="row">
    <?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <div class="content-trangmuathe">
            <h3 style="width: 100%; text-transform: uppercase; ">MUA MÃ THẺ ĐIỆN THOẠI</h3>
            <div class="form_box">
                <form name="frm_megabank" id="frm_megabank" action="<?php echo $action; ?>" method="post"  class="form-horizontal">
                    <div class="form-group ">
                        <div class="col-sm-4 required">
                            <select name="provider" class="input_text form-control" >
                                <option value="0">Chọn nhà cung cấp</option>
                                <option value="VTT">Vietel</option>
                                <option value="VNP">Vinaphone</option>
                                <option value="VMS">Mobifone</option>
                            </select>
                          <?php if ($error_provider) { ?>
                          <div class="text-danger"><?php echo $error_provider; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-4 required">
                            <select name="amount" class="input_text form-control" >
                                <option value="0" selected="">Chọn mệnh giá</option>
                                <option value="10000">10.000 đ</option>
                                <option value="20000">20.000 đ</option>
                                <option value="30000">30.000 đ</option>
                                <option value="50000">50.000 đ</option>
                                <option value="100000">100.000 đ</option>
                                <option value="200000">200.000 đ</option>
                                <option value="300000">300.000 đ</option>
                                <option value="500000">500.000 đ</option>
                            </select>
                          <?php if ($error_amount) { ?>
                          <div class="text-danger"><?php echo $error_amount; ?></div>
                          <?php } ?>
                        </div>
                        <div class="col-sm-4 required">
                          <input type="number" name="quantity" value="1" class="form-control" />
                          <?php if ($error_quantity) { ?>
                          <div class="text-danger"><?php echo $error_quantity; ?></div>
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
                        <div class="col-sm-4">
                          <textarea name="note" placeholder="Ghi chú" value="" class="form-control" /></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <input type="submit" value="Mua mã thẻ" class="btn btn-primary" />
                        </div>
                    </div>
                </form>
                <?php if($error_mess != ''): ?>
                    <p class="mess_err"><?php echo $error_mess; ?></p>
                <?php endif; ?>
                <?php if($success_mess != ''): ?>
                    <!-- <p class="mess_success"><?php echo $success_mess; ?></p> -->
                <?php endif; ?>
            </div><!--end .form_box-->
            <?php if($listcart != ''): ?>
                <h3 class="list-title">Danh sách thẻ cào</h3>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left">STT</td>
                        <td class="text-left">Nhà mạng</td>
                        <td class="text-left">Mã thẻ</td>
                        <td class="text-left">Mệnh giá</td>
                        <td class="text-left">Serial</td>
                        <td class="text-left">Ghi chú</td>
                        <td class="text-right">Hạn sử dụng</td>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      foreach ($listcart as $key => $itemcart) { ?>
                      <tr>
                        <td class="text-left"><?php echo $key; ?></td>
                        <td class="text-left">
                            <?php 
                                if($itemcart[0] == 'VTT'){
                                    echo 'Vietel'; 
                                }else if($itemcart[0] == 'VNP'){
                                    echo 'Vinaphone';
                                }else if($itemcart[0] == 'VMS'){
                                    echo 'Mobifone';
                                }                                
                            ?>                            
                        </td>
                        <td class="text-left"><?php echo $itemcart[3]; ?></td>
                        <td class="text-left"><?php echo number_format($itemcart[1]); ?></td>
                        <td class="text-left"><?php echo $itemcart[2]; ?></td>
                        <td class="text-left"><?php echo $itemcart[5]; ?></td>
                        <td class="text-right"><?php echo $itemcart[4]; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>               
            <?php endif; ?>
        </div>
        
    </div><!--end .container-->
    <?php echo $column_right; ?>
   </div>
</div>
<?php echo $footer; ?>