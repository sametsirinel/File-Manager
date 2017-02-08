<?php

  class ftpManager{

    protected $ftp_server = null;

    protected $ftp_user = null;

    protected $ftp_password = null;

    protected $ftp_connect = null;

    protected $error = false;

    public function __construct($server = null,$user = null,$password = nul){

      if(empty($server) || empty($user) || empty($password)){

        if(!empty($server))
          $this->error .= "Server Bilgisi Girilmedi <br/>";

        if(!empty($user))
          $this->error .= "Ftp Kullanıcı Adı Girilmedi <br/>";

        if(!empty($password))
          $this->error .= "Ftp Şifre Bilgisi Girilmedi <br/>";

        return false;

      }

      $this->ftp_server = $server;
      $this->ftp_user = $user;
      $this->ftp_password = $password;

      return $this->server_login();

    }

    protected function server_connect(){

      $connect = @ftp_connect($this->ftp_server);

      if(!$connect){

        $this->error = "Ftp server bağlantısı sağlanamadı.";
        return false;

      }

      $this->ftp_connect = $connect;

      return true;

    }

    protected function server_login(){

      if(!$this->server_connect())
        return false;

      $login = @ftp_login($this->ftp_connect,$this->ftp_user,$this->ftp_password);
      if(!$login){

        $this->error = "Ftp kullanıcı adı veya şifresi hatalı";
        return false;

      }

      ftp_pasv($this->ftp_connect, TRUE);

      return true;

    }

    public function error(){

      return $this->error;

    }

    public function readFile($dizin = ""){

       $temp_path = tempnam(sys_get_temp_dir(), "ftp");
       ftp_get($this->ftp_connect, $temp_path, $dizin, FTP_BINARY);
       $contents = file_get_contents($temp_path);
       unlink($temp_path);
       return $contents;

    }

    public function all_dir_list($dizin1 = ""){

      echo "<ul>";

      foreach($this->rawList($dizin1) as $key => $dir){

        if($dir["type"]!="file" && !($key=="." || $key=="..")){

          echo "<li><a href='#' class='ajaxHref' data-link='pages/list.php?dizin=$dizin1/$key&islem=dirList'>$key </a><a href='#' class='ajaxAltLink' data-link='?dizin=$dizin1/$key&islem=allList'><small><i class='fa fa-arrow-down'></i></small></a></li>";

        }

      }

      echo "</ul>";

    }

    public function dir_list($dizin = "."){

      return ftp_nlist($this->ftp_connect,$dizin);

    }

    public function rawList($dizin=""){

      if (is_array($children = @ftp_rawlist($this->ftp_connect, $dizin))) {
        $items = array();
        foreach ($children as $child) {
            $chunks = preg_split("/\s+/", $child);
            list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
            $item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file';
            array_splice($chunks, 0, 8);
            $items[implode(" ", $chunks)] = $item;
        }

        return $items;
      }

    }

    public function is_dir($dizin=""){

      if (@ftp_chdir($this->ftp_connect, $dizin)) {
            ftp_chdir($this->ftp_connect, '..');
            return true;
       } else {
            return false;
       }

    }

    public function goUpDir($dizin=""){

      $dizin = rtrim($dizin,"/");

      $explode = explode("/",$dizin);

      unset($explode[count($explode)-1]);

      $dizin = rtrim($dizin,"/");

      return implode ("/", $explode );

    }

    public function fileUpload($dizin=""){

      $sayac = 0;
      foreach($_FILES as $val){

        $name = isset($_POST["name"]) && !empty($_POST["name"]) ? $_POST["name"] : $val["name"];
        print_r($val);
        if(empty($val["name"]))
          return false;
        ftp_put($this->ftp_connect,$dizin."/".($sayac==0 ? "" : $sayac).$name,$val["tmp_name"],FTP_ASCII);
        $sayac++;
      }

      return true;

    }

    public function createDir($dizin=""){

      return ftp_mkdir($this->ftp_connect,$dizin);

    }

    public function removeDir($dizin1=""){

      foreach($this->rawList($dizin1) as $key => $dir){
        echo $dizin1."/$key <br>";
        if($dir["type"]!="file"){
          $this->removeDir($dizin1."/".$key);

          if(!ftp_rmdir($this->ftp_connect,$dizin1."/".$key))
            return false;

        }else{
          // echo $dizin1."/".$key;exit;
          if(!$this->deleteFile($dizin1."/".$key))
            return false;

        }

      }
      if(!ftp_rmdir($this->ftp_connect,$dizin1))
        return false;


      return true;

    }

    public function getCreteDate($dizin=""){

      return ftp_mdtm($this->ftp_connect,$dizin);

    }

    public function getSize($dizin=""){

      return ftp_size($this->ftp_connect,$dizin);

    }

    public function getMod($dizin=""){

      return ftp_chdir($this->ftp_connect,$dizin);

    }

    public function deleteFile($dizin=""){

      return ftp_delete($this->ftp_connect,$dizin);

    }

    public function renameFile($old,$new){

      return ftp_rename($this->ftp_connect,$old,$new);

    }

    public function chMod($mod,$dizin){

      return ftp_chmod($this->ftp_connect, $mod, $dizin);

    }

    public function creteFile($dizin="",$out=""){

      $fp = fopen('php://temp', 'r+');
      fwrite($fp, $out);
      rewind($fp);
      $deger = ftp_fput($this->ftp_connect, $dizin, $fp, FTP_ASCII);
      unset($fp);
      return $deger;

    }

    public function close(){

      return ftp_close($this->ftp_connect);

    }

  }

  $ftp = new ftpManager("sametsirinel.com","sametsirinel","14021402");

  if($ftp->error())
    echo "hata var ";
  else
    echo $ftp->error();

  // foreach($ftp->rawList("public_html") as $dir){
  //
  //   print_r($dir);echo "<br/>";
  //
  // }



?>
