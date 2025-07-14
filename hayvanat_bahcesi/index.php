<?php

abstract class Hayvan
{
    protected $turAdi;
    protected $yas;
    protected $agirlik;

    public function __construct($turAdi, $yas, $agirlik)
    {
        $this->turAdi = $turAdi;
        $this->yas = $yas;
        $this->agirlik = $agirlik;
    }

    abstract public function getDosage();          // Her hayvan grubu kendine göre hesaplayacak

    abstract public function getFeedSchedule();    // Her hayvan grubu farklı şekilde beslenecek

    public function bilgiGoster()
    {
        echo "<b>Tür:</b> {$this->turAdi} | <b>Yaş:</b> {$this->yas} | <b>Ağırlık:</b> {$this->agirlik} kg<br>";
    }
}

class At extends Hayvan
{
    public function getDosage()
    {
        return $this->agirlik * 0.02 . " mg ilaç";
    }

    public function getFeedSchedule()
    {
        return "Günde 3 kez: Sabah, Öğlen, Akşam";
    }
}

class Kedigil extends Hayvan
{
    public function getDosage()
    {
        return $this->agirlik * 0.03 . " mg ilaç";
    }

    public function getFeedSchedule()
    {
        return "Günde 2 kez: Sabah ve Akşam";
    }
}

class Kemirgen extends Hayvan
{
    //test
    public function getDosage()
    {
        return $this->agirlik * 0.01 . " mg ilaç";
    }

    public function getFeedSchedule()
    {
        return "Günde 4 kez: Sabah, Öğle, İkindi, Akşam";
    }
}

$at = new At("Zebra", 5, 300);
$kedi = new Kedigil("Kaplan", 3, 220);
$kemirgen = new Kemirgen("Kunduz", 2, 15);

// Listeleme
$hayvanlar = [$at, $kedi, $kemirgen];

foreach ($hayvanlar as $hayvan) {
    $hayvan->bilgiGoster();
    echo "İlaç Dozu: " . $hayvan->getDosage() . "<br>";
    echo "Beslenme Programı: " . $hayvan->getFeedSchedule() . "<br><br>";
}
