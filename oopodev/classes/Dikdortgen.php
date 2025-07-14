<?php

require_once 'Sekil.php';

class Dikdortgen extends Sekil
{
    protected $uzunKenar;
    protected $kisaKenar;

    public function __construct($uzunKenar, $kisaKenar)
    {
        $this->uzunKenar = $uzunKenar;
        $this->kisaKenar = $kisaKenar;
    }

    public function alan()
    {
        return $this->uzunKenar * $this->kisaKenar;
    }

    public function cevre()
    {
        return 2 * ($this->uzunKenar + $this->kisaKenar);
    }
}
