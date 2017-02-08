
<div class="dirList">

  <table class="table table-hover">
      <thead>
        <tr>
          <th>Dosya Adı</th>
          <th>Oluşturulma Tarihi</th>
          <th>Boyut</th>
          <th>Yetkiler</th>
          <th style="max-width:300px;" class="text-right">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($ftp->rawList($dizin) as $key =>$dir){ ?>
          <tr>
            <td><a href="#" data-link="<?=$dir["type"]=="directory" ? "pages/list.php?dizin=$dizin/$key&islem=dirList" : "#" ?>&"  <?=$dir["type"]=="directory" ? 'class="ajaxHref"' : "" ?>><?=$key ?></a></td>
            <td><?=$dir["day"]." ".$dir["month"]." - ".$dir["time"]; ?></td>
            <td><?=$dir["size"] ?></td>
            <td><?=$dir["rights"] ?></td>
          <td>
              <a class="btn btn-sm btn-default pull-right ajaxHref removeClick" href="#" data-link="pages/list.php?dizin=<?=$dizin."/".$key ?>&islem=<?=$dir["type"]=="file" ? "dosyaSil" : "klasorSil" ?>" data-val="<?=$key ?>"><i class="fa fa-trash"></i></a>
              <a class="btn btn-sm btn-default renameClick pull-right" style="margin-right:5px;" href="#" data-val="<?=$key ?>" data-link="?dizin=<?=$dizin."/".$key ?>&islem=renameFile" data-toggle="modal" data-target="#renameFile">Adlandır</a>
              <?php if($dir["type"]=="file"){ ?>
                <a class="btn btn-sm btn-default pull-right ajaxHref" style="margin-right:5px;" href="#" data-link="pages/list.php?dizin=<?=$dizin."/".$key ?>&islem=dosyaDegistir">Düzenle</a>
              <?php }else{ ?>
                <a class="btn btn-sm btn-default pull-right klasorYetkisi" style="margin-right:5px;" href="#" data-link="<?=$dizin."/".$key ?>" data-toggle="modal" data-target="#klasorYetkisi">Klasör Yetkileri</a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
</div>
