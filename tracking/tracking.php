<?php
  if( $_POST["coingecko_ranking"] != "" && $_POST["coingecko_value"] != "" ){

    $fp=fopen("../coingecko_ranking.csv","r");
    $CSV=fread($fp,filesize("../coingecko_ranking.csv"));
    fclose($fp);

    $fp=fopen("lastentries.json","r");
    $L=json_decode( fread($fp,filesize("lastentries.json")));
    fclose($fp);

    $D=preg_split("/-/", $L->{"coingecko"}->{"last_date"});
    $newD=date("Y-m-d",time());

    $fp=fopen("../coingecko_ranking.csv","w+");
    fwrite($fp,preg_replace("/

/","
",$CSV."
".$newD.",".$_POST["coingecko_ranking"].",".$_POST["coingecko_value"]));
    fclose($fp);

    $L->{"coingecko"}->{"last_date"} = $newD;
    $L->{"coingecko"}->{"fields"}->{"ranking"} = $_POST["coingecko_ranking"];
    $L->{"coingecko"}->{"fields"}->{"value"} = $_POST["coingecko_value"];

    $fp=fopen("lastentries.json","w+");
    fwrite($fp,json_encode($L));
    fclose($fp);

    echo "<meta http-equiv='refresh' content='0'>";
  }
?>
<html>
<head>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
  <style>
    .row:first-child {
      font-weight: bold;
    }
    .form-control {
      display:contents !important;
      width:80% !important
    }
  </style>
</head>
<body>
  <form method="POST">
  <div class="container">
<?php
  $fp=fopen("setup.json","r");
  $J=json_decode( fread($fp,filesize("setup.json")));
  fclose($fp);

  $fp=fopen("lastentries.json","r");
  $L=json_decode( fread($fp,filesize("lastentries.json")));
  fclose($fp);

?>
  <div class="row">
    <div class="col-2">data label</div>
    <div class="col-2">next </div>
    <div class="col">append new entries</div>
  </div>
<?php

  $d_Y=intval( date("Y",time()));
  $d_m=intval( date("m",time()));
  $d_d=intval( date("d",time()));

  foreach($J as $j){
    // check if new entry is necessary

    $newD=date("Y-m-d", mktime(0,0,0,$d_m ,$d_d, $d_Y) );

    if( $newD == $L->{ "coingecko" }->{"last_date"} ) continue;


      echo "<div class=\"row\">
  <div class=\"col-2\">".$j->{"label"}."</div>
  <div class=\"col-2\">".$newD."</div>
  ";
    foreach($j->{"fields"} as $k){
      echo "<div class=\"col\"><input name=\"".$j->{"id"}."_".$k->{"id"}."\" type=\"text\" class=\"form-control form-control-sm\" placeholder=\"".$k->{"label"}." ( ".$k->{"value_min"}." to ".$k->{"value_max"}." , last ".$L->{ $j->{"id"} }->{"fields"}->{ $k->{"id"} }." )\"> <a href='".$k->{"url"}."' target='datasource'>URL</a></div>
      ";
    }
    echo "</div>";
  }

?>
    <div class="row">
      <div class="col">
        <button onclick="save_content()" class="btn btn-primary">save</button>
      </div>
    </div>
  </div>
  </form>
      <script><!--

  function save_content(){
    $("form").submit();
  }



  --></script>

  <script
       src="https://code.jquery.com/jquery-1.11.2.min.js"
       integrity="sha256-Ls0pXSlb7AYs7evhd+VLnWsZ/AqEHcXBeMZUycz/CcA="
       crossorigin="anonymous"></script>

</body>
</html>
