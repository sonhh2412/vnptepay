<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div id="content" class="col-sm-12">
        <div class="content-trangmuathe">
          <h3 style="width: 100%; text-transform: uppercase; ">LỊCH SỬ GIAO DỊCH</h3>
          
          <div class="form-group custom-field form-filter-history" data-sort="">
            <form name="date_history" id="date_history" action="<?php echo $action; ?>" method="post">
              <label class="col-sm-1 control-label">Từ ngày</label>
              <div class="col-sm-5">
                <div class="input-group date">
                  <input type="text" name="datefrom" value="<?php 
                  if(isset($_GET["datefrom"]))print_r($_GET["datefrom"]);else if(isset($datefrom))echo $datefrom;
                  
                  ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
              <label class="col-sm-1 control-label">Đến ngày</label>
              <div class="col-sm-5">
                <div class="input-group date">
                  <input type="text" name="dateto" value="<?php if(isset($_GET["dateto"]))print_r($_GET["dateto"]);elseif(isset($dateto))echo $dateto;else echo date("Y-m-d") ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-12">
                      <input type="submit" value="Xem lịch sử giao dịch" class="btn btn-primary" />
                  </div>
              </div>
            </form>
          </div>
          
          <div class="col-sm-12 history-transaction table-responsive">
            <?php if(isset($historys)){ ?>
            <table>
              <tr class="caption">
                <td>Id</td>
                <td>Request_id</td> 
                <td>Nhà Mạng</td>
                <td>Mệnh Giá</td>
                <td>Số Lượng</td>
                <td>Kết quả</td>
                <td>Ghi chú</td>
                <td>Ngày Giao Dịch</td>
              </tr>
              <?php $a=0;?>
              <?php  foreach ($historys as $history) { ?>
                <tr <?php if ($a==0) echo "class='pearline'"; ?>>
                  <td><?php echo $history['id'];?></td>
                  <td><?php echo $history['request_id'];?></td> 
                  <td><?php if($history['provider'] =='VTT')echo "Vietel";
                            if($history['provider'] =='VNP')echo "Vinaphone";
                            if($history['provider'] =='VMS')echo "Mobifone";

                  ?></td>
                  <td><?php echo $history['amount'];?></td>
                  <td><?php echo $history['quantity'];?></td>
                  <td class="text-left" >
                    <?php 
                       switch ($history['error_code']) {
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
                            
                          }; 
                    ?>
                      
                  </td><!-- ketqua -->
                  <td><?php echo $history['note'];?></td>
                  <td><?php echo $history['date_added'];?></td>
                  </tr>
              <?php if($a==0) $a=1; else $a=0;} ?>  
            </table>
            <div class="history-tran-control">
              <div class="col-sm-6 text-left"><?php if(isset($pagination))echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php if(isset($results))echo $results; ?></div>
            </div>
            <?php }?>
          </div>
        </div>
        
        

    </div><!--end .container-->
   </div>
</div>
<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

//--></script>
<?php echo $footer; ?>