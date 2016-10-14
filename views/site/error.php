<?php

/* @var $this yii\web\View */

?>

<h1 class="alert-danger" style="text-align: center">Error code: <?= $exception->statusCode ?></h1>

<h2 class="alert-warning" style="text-align: center">Message: <?= $exception->xdebug_message ?></h2>

<?php var_dump($exception); ?>
