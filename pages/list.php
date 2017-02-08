<?php

  require_once("../ftpMaganer.php");

  $dizin = isset($_GET["dizin"]) ? $_GET["dizin"] : ".";
  $islem = isset($_GET["islem"]) ? $_GET["islem"] : false;
  $dizinArray = explode("/",trim($dizin,"/"));

  // echo $dizin;
  if($islem == "ustdizin"){

    $dizin = $ftp->goUpDir($dizin);

  }else if($islem=="klasorSil"){
    // echo $dizin;
    if(!$ftp->removeDir($dizin))
      echo "ok";
    $dizin = $ftp->goUpDir($dizin);

  }else if($islem=="dosyaSil"){

    if(!$ftp->deleteFile($dizin))
      echo "ok";
    $dizin = $ftp->goUpDir($dizin);

  }

?>
<div class="dizin" data-dizin="<?=$dizin ?>">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="#" data-link="<?="pages/list.php?dizin=/"; ?>" class="ajaxHref"><i class="fa fa-home"></i> Ana Dizin</a></li>
          <li><a href="#" data-link="<?="pages/list.php?dizin=".$dizin."&islem=ustdizin"; ?>" class="ajaxHref"><i class="fa fa-reply"></i> Üst Klasör</a></li>
          <li><a href="#" data-toggle="modal" data-target="#klasorolustur" class="klasorolustur"><i class="fa fa-folder-open"></i> Klasör Oluştur</a></li>
          <li><a href="#" data-toggle="modal" data-target="#dosyaolustur" class="dosyaolustur"><i class="fa fa-file"></i> Dosya Oluştur</a></li>
          <li><a href="#" data-toggle="modal" data-target="#fileUpload" class="fileUpload"><i class="fa fa-upload"></i> Dosya Yükle</a></li>
          <!--<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#">Separated link</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li> -->
        </ul>
        <!--
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">Link</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
          </li>
        </ul> -->
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <div class="wall">
    <ol class="breadcrumb">
      <li><a href="#" class="ajaxHref" data-link="pages/list.php?dizin=/&islem=dirList"><i class="fa fa-home"></i></a></li>
      <?php $string = "";  ?>
      <?php  foreach($dizinArray as $link){ ?>
        <?php $string .= "/".$link; ?>
        <li><a href="#" class="ajaxHref" data-link="pages/list.php?dizin=<?=$string ?>&islem=dirList"><?=$link ?></a></li>
      <?php } ?>
    </ol>

    <?php
      if($islem=="dosyaDegistir"){
        require_once("duzenle.php");
      }else{
        require_once("list_table.php");
      }
    ?>
  </div>
</div>

<?php $ftp->close(); ?>
