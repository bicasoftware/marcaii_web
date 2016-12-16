<?php
  include("rb.php");

  $host = "127.0.0.1:3306";
  $dbname = "horas";
  $user = "root";
  $pass = "master";

  R::setup("mysql:host=$host;dbname=$dbname",$user, $pass);
  R::freeze(true);

