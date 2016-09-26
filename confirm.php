<?php $page = $_GET['page'];
$query = $_GET['query'];
$id = $_GET['id'];
header("location: $page?$query=$id");?>