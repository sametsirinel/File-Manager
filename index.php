<?php ob_start();session_start();
require_once("ftpMaganer.php"); ?>

<?php

  $dizin = trim(isset($_GET["dizin"]) ? $_GET["dizin"] : "","");
  $islem = isset($_GET["islem"]) ? $_GET["islem"] : false;

  if($islem=="klasorEkle"){
    echo $dizin;
    $klasor = $dizin."/".(empty($_POST["klasor"]) ? "" : $_POST["klasor"]);
    echo $klasor;
    if($ftp->createDir($klasor))
      "ok";
      exit;

  }else if($islem=="renameFile"){

    $old = empty($_POST["old"]) ? false : $_POST["old"];
    $new = empty($_POST["new"]) ? false : $_POST["new"];

    if($ftp->renameFile($dizin."/$old",$dizin."/$new"))
      echo "ok";
      exit;

  }else if($islem=="creteFile"){

    $file = rtrim($dizin."/".(empty($_POST["file"]) ? "" : $_POST["file"]),"/");
    // echo $file;exit;
    $write =empty($_POST["write"]) ? "" : $_POST["write"];
    // echo $write;exit;
    if($ftp->creteFile($file,$write))
      echo "ok";
      exit;

  }else if($islem=="fileUpload"){

    if($ftp->fileUpload($dizin))
      echo "ok";
    else
      echo "not ok";
      exit;

  }else if($islem=="allList"){

    $ftp->all_dir_list($dizin);
    exit;

  }else if($islem=="klasorYetkisi"){

    $sahipr = @$_POST["sr"] == 1 ? 1 : 0;
    $sahipw = @$_POST["sw"] == 1 ? 1 : 0;
    $sahipe = @$_POST["se"] == 1 ? 1 : 0;

    $sahip = $sahipr*4 + $sahipw*2 + $sahipe*1;

    $grupr = @$_POST["grr"] == 1 ? 1 : 0;
    $grupw = @$_POST["grw"] == 1 ? 1 : 0;
    $grupe = @$_POST["gre"] == 1 ? 1 : 0;

    $grup = $grupr*4 + $grupw*2 + $grupe*1;

    $genelr = @$_POST["gr"] == 1 ? 1 : 0;
    $genelw = @$_POST["gw"] == 1 ? 1 : 0;
    $genele = @$_POST["ge"] == 1 ? 1 : 0;

    $genel = $genelr*4 + $genelw*2 + $genele*1;

    $mod =  "0".$sahip.$grup.$genel;

    echo $mod.$dizin;

    if(!$ftp->chMod($mod,$dizin))
      echo "ok";
    exit;

  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>File Manager</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script type="text/javascript">
        $(function(){

          $("body").on("click", ".ajaxHref", function(event){

            $(".mainLoader").fadeIn();

            var href = $(this).attr("data-link");

            $.ajax({

              type:"post",
              url:href,
              success:function(cevap){

                $(".main").html(cevap);
                $(".mainLoader").fadeOut();
              }

            });

          });

          $(function(){
            $("body").on("click", ".ajaxAltLink", function(event){

              var link = $(this);
              $(".mainLoader").fadeIn();

              var href = $(this).attr("data-link");

              $.ajax({

                type:"post",
                url:href,
                success:function(cevap){

                  link.parent().append(cevap);
                  // alert(cevap);
                  $(".mainLoader").fadeOut();

                }

              });

            });

          });

        });
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="mainLoader"> <i class="fa fa-refresh fa-spin"></i> Yükleniyor</div>
    <div class="side">
      <div class="logo text-center">
        <h2>FtpManager</h2>
      </div>
      <div class="classAlldirList">
        Yükleniyor Lütfen Bekleyin.
      </div>
    </div>
    <div class="main">

    </div>
    <div class="modal fade" id="klasorolustur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <form class="ajaxForm" action="?dizin=<?=$dizin ?>&islem=klasorEkle" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <fieldset class="form-group">
                <label for="exampleTextarea">Bir klasör adı giriniz</label>
                <input class="form-control" name="klasor" id="exampleTextarea"/>
              </fieldset>
              <button type="submit" class="btn btn-primary pull-right">Klasörü Ekle</button>
              <button type="button" class="btn btn-default pull-right"  style="margin-right:10px;" data-dismiss="modal">Kapat</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal fade" id="dosyaolustur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <form class="ajaxForm" action="?dizin=<?=$dizin ?>&islem=creteFile" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <fieldset class="form-group">
                <label for="exampleTextarea">Bir dosya adı giriniz</label>
                <input class="form-control" name="file" id="exampleTextarea"/>
              </fieldset>
              <button type="submit" class="btn btn-primary pull-right">Dosyayı Ekle</button>
              <button type="button" class="btn btn-default pull-right"  style="margin-right:10px;" data-dismiss="modal">Kapat</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal fade" id="fileUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <form class="ajaxFormEnctype" action="?dizin=<?=$dizin ?>&islem=fileUpload" enctype="multipart/form-data" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <fieldset class="form-group">
                <label for="exampleTextarea">Dosya adını giriniz</label>
                <input type="text" class="form-control" name="name" />
              </fieldset>
              <fieldset class="form-group">
                <label for="exampleTextarea">Bir dosya seçiniz</label>
                <input type="file" class="form-control" multiple name="file" />
              </fieldset>
              <button type="submit" class="btn btn-primary pull-right">Dosyayı Ekle</button>
              <button type="button" class="btn btn-default pull-right"  style="margin-right:10px;" data-dismiss="modal">Kapat</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal fade" id="renameFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <form class="ajaxForm" id="renameForm" action="?dizin=<?=$dizin ?>&islem=dosyaEkle" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <fieldset class="form-group">
                <label for="exampleTextarea">Bir dosya adı giriniz</label>
                <input class="form-control renameInput" type="hidden" name="old" />
                <input class="form-control renameInput" name="new" />
              </fieldset>
              <button type="submit" class="btn btn-primary pull-right">Dosya Adını Güncelle</button>
              <button type="button" class="btn btn-default pull-right"  style="margin-right:10px;" data-dismiss="modal">Kapat</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
    </div>
    <div class="modal fade" id="klasorYetkisi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <form class="ajaxForm" action="?dizin=<?=$dizin ?>&islem=klasorYetkisi" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="checkbox">
                <h4>Sahip İzinleri</h4>
                <label>
                  <input type="checkbox" name="sr" value="1"> Okuma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="sw" value="1"> Yazma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="se" value="1"> Çalıştırma Yetkisi
                </label>
              </div>
              <div class="checkbox">
                <h4>Grup İzinleri</h4>
                <label>
                  <input type="checkbox" name="grr" value="1"> Okuma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="grw" value="1"> Yazma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="gre" value="1"> Çalıştırma Yetkisi
                </label>
              </div>
              <div class="checkbox">
                <h4>Genel İzinler</h4>
                <label>
                  <input type="checkbox" name="gr" value="1"> Okuma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="gw" value="1"> Yazma Yetkisi
                </label>
                <label>
                  <input type="checkbox" name="ge" value="1"> Çalıştırma Yetkisi
                </label>
              </div>
              <button type="submit" class="btn btn-primary pull-right">Yetki Güncelle</button>
              <button type="button" class="btn btn-default pull-right"  style="margin-right:10px;" data-dismiss="modal">Kapat</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/dropzone.js"></script>
    <script type="text/javascript">
      $(".dropzone").dropzone({ url: "/file/post" });
    </script>
    <script type="text/javascript">
      $(function(){
        var dizin = "";
        function refresh(dir="/"){

          $.ajax({

            type:"post",
            url:"pages/list.php?dizin="+dir+"&islem=dirList",
            success:function(cevap){

              $(".main").html(cevap);

              $(".mainLoader").fadeOut();

            }

          })

        }

        $("body").on("click",".renameClick",function(){

          var href = $(this).attr("data-link");
          var val = $(this).attr("data-val");


          $(".renameInput").val(val);

          dizin = $(".dizin").attr("data-dizin");

          $("#renameFile").find("form").attr("action","?dizin="+ dizin +"&islem=renameFile");

        });

        $("body").on("submit",".ajaxForm",function(e){

          e.preventDefault();
          $(".mainLoader").fadeIn();

          var f = $(this);
          $.ajax({

            type:f.attr("method"),
            url:f.attr("action"),
            data: f.serialize(),
            success:function(cevap){

              // $(".main").html(cevap);
              for(var i=0;i<$(".modal").length;i++){

                $(".modal").eq(i).modal("hide");

              }

              refresh(dizin);
              $(".mainLoader").fadeOut();
              $("form").trigger('reset');

            }

          });

        })

        $(".ajaxFormEnctype").submit(function(e){

          e.preventDefault();

          $(".mainLoader").fadeIn();

          var data = new FormData();
          data.append("name",$("input[name=name]").val());
          jQuery.each(jQuery('input[type=file]')[0].files, function(i, file) {
              data.append('file-'+i, file);
          });

          var f = $(this);
          $.ajax({

            type:f.attr("method"),
            url:f.attr("action"),
            data: data,
            contentType: false,
            processData: false,
            success:function(cevap){

              for(var i=0;i<$(".modal").length;i++){

                $(".modal").eq(i).modal("hide");

              }
              // $(".main").html(cevap);
              refresh(dizin);
              $(".mainLoader").fadeOut();
              $("input").val('');

            }

          });

        })

        setTimeout(function(){

          $.ajax({

            type:"post",
            url:"?dizin=/&islem=allList",
            success:function(cevap){

              $(".classAlldirList").html(cevap);

            }

          });

          refresh()

        },50);



        $("body").on("click",".klasorolustur",function(){
          dizin = $(".dizin").attr("data-dizin");
          $("#klasorolustur").find("form").attr("action","?dizin="+ dizin+"&islem=klasorEkle");
        });

        $("body").on("click",".dosyaolustur",function(){
          dizin = $(".dizin").attr("data-dizin");
          $("#dosyaolustur").find("form").attr("action","?dizin="+ dizin +"&islem=creteFile");
        });

        $("body").on("click",".fileUpload",function(){
          dizin = $(".dizin").attr("data-dizin");
          $("#fileUpload").find("form").attr("action","?dizin="+ dizin +"&islem=fileUpload");
        });

        $("body").on("click",".klasorYetkisi",function(){
          dizin = $(this).attr("data-link");
          $("#klasorYetkisi").find("form").attr("action","?dizin="+ dizin +"&islem=klasorYetkisi");
        });


      });
    </script>
  </body>
</html>

<?php $ftp->close() ?>
