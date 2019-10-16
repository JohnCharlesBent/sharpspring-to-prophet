<?php
// absolute path
require 'config.php';

$body_class = $_SERVER['REQUEST_URI'];
$body_class = str_replace('/', '_', $body_class);

?>
<!doctype html>
<html>
<head>
  <meta name ="charset" charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SharpSpring | Prophet Account Comparison</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo dist_dir; ?>/dist/css/style.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

</head>
<html>
<body class="<?php echo $body_class; ?>">
  <div class="content_wrapper">
