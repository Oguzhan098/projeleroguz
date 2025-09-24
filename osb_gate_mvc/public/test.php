<?php
require __DIR__ . '/../app/bootstrap.php';
var_dump(class_exists('App\Core\Url'));           // true
echo \App\Core\Url::to('/movements');             // bir URL basmalı
