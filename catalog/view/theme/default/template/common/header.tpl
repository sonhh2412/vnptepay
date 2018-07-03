<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">

<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-9">
        <nav id="menu" class="navbar">
          <div class="navbar-header">
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
          </div>
          <div class="collapse navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
              <li <?php if($currentpage=="module/vnptepay") echo 'class="active"'?> ><a href="index.php?route=module/vnptepay">Mua mã thẻ</a></li>
              <li <?php if($currentpage=="module/vnptepay/balance") echo 'class="active"'?> ><a href="index.php?route=module/vnptepay/balance">Kiểm tra số dư tài khoản</a></li>
              <li <?php if($currentpage=="module/vnptepay/redownloadpin") echo 'class="active"'?> ><a href="index.php?route=module/vnptepay/redownloadpin">Tải lại mã thẻ</a></li>
             <!--  <li <?php if($currentpage=="module/vnptepay/checktransaction") echo 'class="active"'?>><a href="index.php?route=module/vnptepay/checktransaction">Kiểm tra giao dịch</a></li> -->
              <li <?php if($currentpage=="module/vnptepay/historytransaction") echo 'class="active"'?>><a href="index.php?route=module/vnptepay/historytransaction">Lịch Sử Giao Dịch</a></li>
              <?php if ($logged) { ?>
              <li <?php if($currentpage=="account/logout") echo 'class="active"'?>><a href="<?php echo $logout; ?>">Thoát</a></li>
              <?php } else { ?>
              <li <?php if($currentpage=="account/login") echo 'class="active"'?>><a href="<?php echo $login; ?>">Đăng nhập</a></li>
              <?php } ?>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>

