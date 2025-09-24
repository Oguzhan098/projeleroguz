<?php
class Airport {
    public $id;
    public $name;
    public $pist_sayisi;
    public $ucak_kapasitesi;

    public function __construct($name, $pist_sayisi, $ucak_kapasitesi, $id = null) {
        $this->name = $name;
        $this->pist_sayisi = $pist_sayisi;
        $this->ucak_kapasitesi = $ucak_kapasitesi;
        $this->id = $id;
    }
}
