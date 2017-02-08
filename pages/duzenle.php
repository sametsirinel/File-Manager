<form class="ajaxForm" action="?dizin=<?=$dizin ?>&islem=creteFile" method="post">
  <textarea class="window" name="write" class="form-control"><?=$ftp->readFile($dizin) ?></textarea>
  <input type="submit" class="btn btn-success pull-right"  value="Dosyayı Kaydet">
</form>
