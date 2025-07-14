<?php

require_once 'Dikdortgen.php';

class Kare extends Dikdortgen
{
    public function __construct($kenar)
    {
        parent::__construct($kenar, $kenar);
    }
}
