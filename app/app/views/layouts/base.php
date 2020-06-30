<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QUIZ</title>
    <link href="<?=getenv('DOMAIN') ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=getenv('DOMAIN') ?>/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div role="main" class="container" id="content">
      <?php if (!empty($body)) { echo $body; } ?>
    </div>
    <footer class="footer">
      <div class="container">
        <span class="text-muted"></span>
      </div>
    </footer>
    <script src="<?=getenv('DOMAIN') ?>/assets/js/jquery.min.js"></script>
    <script src="<?=getenv('DOMAIN') ?>/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=getenv('DOMAIN') ?>/assets/js/custom.js"></script>
</body>
</html>