<?php

require_once 'Sekil.php';

class Ucgen extends Sekil
{
    protected $kenar1;
    protected $kenar2;
    protected $kenar3;
    protected $yukseklik;

    public function __construct($kenar1, $kenar2, $kenar3, $yukseklik)
    {
        $this->kenar1 = $kenar1;
        $this->kenar2 = $kenar2;
        $this->kenar3 = $kenar3;
        $this->yukseklik = $yukseklik;
    }

    public function alan()
    {
        return ($this->kenar1 * $this->yukseklik) / 2;
    }

    public function cevre()
    {
        return $this->kenar1 + $this->kenar2 + $this->kenar3;
    }
}
