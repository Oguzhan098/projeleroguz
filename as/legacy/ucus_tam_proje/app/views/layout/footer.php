<?php

if (!isset($BASE) || !$BASE) {
    $BASE = preg_replace('#/app/.*$#', '', $_SERVER['SCRIPT_NAME']);
    if ($BASE === null || $BASE === '') { $BASE = '/'; }
}
?>
