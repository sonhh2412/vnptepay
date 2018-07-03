<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div id="content" class="col-sm-12">
        <div class="content-trangmuathe">
            <h3 style="width: 100%; text-transform: uppercase; ">TẢI LẠI MÃ THẺ ĐIỆN THOẠI</h3>
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

                  

                    <div class="form-group">
                        <div class="col-sm-4">
                            <input type="submit" value="Tải lại mã thẻ" class="btn btn-primary" />
                        </div>
                    </div>
                </form>
                <?php if($error_mess != ''): ?>
                    <p class="mess_err"><?php echo $error_mess; ?></p>
                <?php endif; ?>
            </div><!--end .form_box-->
            <?php if($listdata != ''): ?>
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
                        <td class="text-left">Kết quả</td>
                        <td class="text-left">Ghi chú</td>
                        <td class="text-left">Thời gian xuất mã thẻ</td>
                        <td class="text-right">Hạn sử dụng</td>
                      </tr>
                    </thead>
                    <tbody>

                      <?php foreach ($listdata as $key => $itemcart) { ?>
                      <tr>
                        <td class="text-left"><?php echo $key+1;  ?></td>
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
                        <td class="text-left"><?php echo number_format($itemcart['1']); ?></td>
                        <td class="text-left"><?php echo $itemcart[2]; ?></td>
                        <td class="text-left"><?php
                          switch ($itemcart[5]) {
                            case 0: echo "Thành công"; break;
                            case 23: echo "Đang nạp tiền"; break;
                            case 99: echo "Đang kiểm tra"; break;
                            case 10: echo "Tài khoản đang bị khoá"; break;
                            case 11: echo "Tên partner không đúng"; break;
                            case 12: echo "Địa chỉ IP không cho phép"; break;
                            case 13: echo "Mã đơn hàng bị lỗi"; break;
                            case 14: echo "Mã đơn hàng đã tồn tại"; break;
                            case 15: echo "Mã đơn hàng không tồn tại"; break;
                            case 17: echo "Sai tổng tiền"; break;
                            case 21: echo "Sai chữ ký"; break;
                            case 22: echo "Dữ liệu gửi lên rỗng hoặc có ký tự đặc biệt"; break;
                            case 30: echo "Số dư khả dụng không đủ"; break;
                            case 31: echo "Chiết khấu chưa được cập nhật cho partner"; break;
                            case 32: echo "Partner chưa cập nhật Public key"; break;
                            case 33: echo "Partner chưa được set IP"; break;
                            case 35: echo "Hệ thống đang bận"; break;
                            case 53: echo "Loại hình thanh toán không hổ trợ"; break;
                            case 101: echo "Mã giao dịch truyền lên sai định dạng"; break;
                            case 102: echo "Mã giao dịch đã tồn tại"; break;
                            case 103: echo "Tài khoản nạp tiền bị sai"; break;
                            case 104: echo "Sai mã nhà cung cấp hoặc nhà cung cấp hệ thống không hỗ trợ"; break;
                            case 105: echo "Mệnh giá nạp tiền không hỗ trợ"; break;
                            case 106: echo "Mệnh giá thẻ không tồn tại"; break;
                            case 107: echo "Thẻ trong kho không đủ cho giao dịch"; break;
                            case 108: echo "Số lượng thẻ mua vượt giới hạn cho phép"; break;
                            case 109: echo "Kênh nạp tiền đang bảo trì"; break;
                            case 110: echo "Giao dịch thất bại"; break;
                            case 111: echo "Mã giao dịch không tồn tại"; break;
                            case 112: echo "Tài khoản chưa có key mã hoá softpin"; break;
                            case 113: echo "Tài khoản nhận tiền không đúng"; break;
                            
                          }
                         ; 
                         ?></td><!-- ketqua -->
                        <td class="text-left"><?php echo $itemcart[6]; ?></td><!-- ghi chú -->
                        <td class="text-left"><?php echo $itemcart[7]; ?></td><!-- Thời gian xuất mã thẻ -->
                        <td class="text-right"><?php echo $itemcart[4]; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>               
            <?php endif; ?>
        </div>
        
    </div><!--end .container-->
   </div>
</div>
<?php echo $footer; ?>