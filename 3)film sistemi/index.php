<?php

// Film sınıfı
class Film {
    public $id;
    public $isim;
    public $krediFiyati;
    public $stoktaVar;

    public function __construct($id, $isim, $krediFiyati, $stoktaVar = true) {
        $this->id = $id;
        $this->isim = $isim;
        $this->krediFiyati = $krediFiyati;
        $this->stoktaVar = $stoktaVar;
    }

    public function filmBilgisi() {
        return "{$this->isim} - {$this->krediFiyati} kredi - " . ($this->stoktaVar ? "Stokta" : "Stokta Yok");
    }
}

// Kullanici sınıfı
class Kullanici {
    public $id;
    public $isim;
    public $email;

    public function __construct($id, $isim, $email) {
        $this->id = $id;
        $this->isim = $isim;
        $this->email = $email;
    }

    public function filmSatınAl(Film $film) {
        echo "{$this->isim}, '{$film->isim}' filmini satın aldı.<br>";
    }
}

// Abone sınıfı
class Abone extends Kullanici {
    public $kredi;

    public function __construct($id, $isim, $email, $kredi = 0) {
        parent::__construct($id, $isim, $email);
        $this->kredi = $kredi;
    }

    public function krediYükle($miktar) {
        $this->kredi += $miktar;
        echo "{$miktar} kredi yüklendi. Yeni bakiye: {$this->kredi}<br>";
    }

    public function filmKirala(Film $film) {
        if (!$film->stoktaVar) {
            echo "Film stokta yok!<br>";
            return;
        }

        if ($this->kredi >= $film->krediFiyati) {
            $this->kredi -= $film->krediFiyati;
            echo "{$this->isim}, '{$film->isim}' filmini kiraladı. Kalan kredi: {$this->kredi}<br>";
        } else {
            echo "Yetersiz kredi!<br>";
        }
    }
}

// Film Talep Sınıfı
class FilmTalep {
    public static function talepEt(Kullanici $kullanici, $filmIsmi) {
        echo "{$kullanici->isim}, '{$filmIsmi}' filmini talep etti.<br>";
    }
}

// Örnek
$film1 = new Film(1, "Inception", 10);
$film2 = new Film(2, "Interstellar", 15, false);

$kullanici1 = new Kullanici(101, "Ali", "ali@example.com");
$abone1 = new Abone(102, "Ayşe", "ayse@example.com", 20);

$kullanici1->filmSatınAl($film1); // Satın alma
$abone1->filmKirala($film1);      // Kiralama
$abone1->filmKirala($film2);      // Stok yok

FilmTalep::talepEt($kullanici1, "Matrix 5");
