<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-vnptepay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vnptepay" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vnptepay_ws_url"><?php echo $text_ws_url; ?></label>
            <div class="col-sm-10">
              <input name="vnptepay_ws_url" class="form-control" placeholder="<?php echo $text_ws_url; ?>" value="<?php echo $vnptepay_ws_url; ?>" />
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vnptepay_partner_name"><?php echo $text_partner_name; ?></label>
            <div class="col-sm-10">
              <input name="vnptepay_partner_name" class="form-control" placeholder="<?php echo $text_partner_name; ?>" value="<?php echo $vnptepay_partner_name; ?>" />
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vnptepay_partner_password"><?php echo $text_partner_password; ?></label>
            <div class="col-sm-10">
              <input name="vnptepay_partner_password" class="form-control" placeholder="<?php echo $text_partner_password; ?>" value="<?php echo $vnptepay_partner_password; ?>" />
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vnptepay_key_sofpin"><?php echo $text_key_sofpin; ?></label>
            <div class="col-sm-10">
              <input name="vnptepay_key_sofpin" class="form-control" placeholder="<?php echo $text_key_sofpin; ?>" value="<?php echo $vnptepay_key_sofpin; ?>" />
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vnptepay_time_out"><?php echo $text_time_out; ?></label>
            <div class="col-sm-10">
              <input name="vnptepay_time_out" class="form-control" placeholder="<?php echo $text_time_out; ?>" value="<?php echo $vnptepay_time_out; ?>" />
              <?php if ($error_code) { ?>
              <div class="text-danger"><?php echo $error_code; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="vnptepay_status" id="input-status" class="form-control">
                <?php if ($vnptepay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>          
        </form>
      </div>
	</div>
  </div>
</div>

<?php echo $footer; ?>