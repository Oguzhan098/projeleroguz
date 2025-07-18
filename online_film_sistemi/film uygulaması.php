<?php

class Film {
    public $id, $ad, $fiyat, $kiraFiyati, $mevcut;

    public function __construct($id, $ad, $fiyat, $kiraFiyati, $mevcut = true) {
        $this->id = $id;
        $this->ad = $ad;
        $this->fiyat = $fiyat;
        $this->kiraFiyati = $kiraFiyati;
        $this->mevcut = $mevcut;
    }

    public function filmTalepEt() {
        echo "⛔ '{$this->ad}' filmi şu an mevcut değil. Talebiniz alındı.<br>";
    }
}

class Kullanici {
    public $id, $isim, $email, $sifre;

    public function __construct($id, $isim, $email, $sifre) {
        $this->id = $id;
        $this->isim = $isim;
        $this->email = $email;
        $this->sifre = $sifre;
    }

    public function filmSatınAl(Film $film) {
        echo "💰 {$this->isim}, '{$film->ad}' filmini ₺{$film->fiyat} karşılığında satın aldı.<br>";
    }

    public function filmKirala(Film $film) {
        echo "❌ {$this->isim}: Sadece aboneler film kiralayabilir.<br>";
    }
}

class Abone extends Kullanici {
    public $kredi;

    public function __construct($id, $isim, $email, $sifre, $kredi = 0) {
        parent::__construct($id, $isim, $email, $sifre);
        $this->kredi = $kredi;
    }

    public function krediYukle($miktar) {
        $this->kredi += $miktar;
        echo "💳 {$this->isim}, ₺{$miktar} kredi yükledi. Yeni bakiye: ₺{$this->kredi}<br>";
    }

    public function filmKirala(Film $film) {
        if (!$film->mevcut) {
            $film->filmTalepEt();
            return;
        }

        if ($this->kredi >= $film->kiraFiyati) {
            $this->kredi -= $film->kiraFiyati;
            echo "🎬 {$this->isim}, '{$film->ad}' filmini ₺{$film->kiraFiyati} ile kiraladı. Kalan kredi: ₺{$this->kredi}<br>";
        } else {
            echo "⚠️ {$this->isim}, yeterli krediye sahip değil. Gerekli: ₺{$film->kiraFiyati}, Bakiye: ₺{$this->kredi}<br>";
        }
    }
}

class FilmSistemi {
    public $filmler = [];
    public $kullanicilar = [];

    public function listeleFilmler() {
        echo "<h3>🎞️ Film Listesi:</h3>";
        foreach ($this->filmler as $film) {
            echo "- {$film->ad} | Satış: ₺{$film->fiyat} | Kira: ₺{$film->kiraFiyati} | Mevcut: " . ($film->mevcut ? "✅" : "❌") . "<br>";
        }
    }

    public function siralaFilmler() {
        usort($this->filmler, fn($a, $b) => $a->fiyat <=> $b->fiyat);
    }
}


class Film {

    public $id, $ad, $fiyat, $kiraFiyati, $mevcut;

    public function __construct($id, $ad, $fiyat, $kiraFiyati, $mevcut = true)
    {
        $this->id = $id;
        $this->ad = $ad;
        $this->fiyat = $fiyat;
        $this->kiraFiyati = $kiraFiyati;
        $this->mevcut = $mevcut;
    }

    public function filmTalepEt()
    {
        echo "⛔ '{$this->ad}' filmi şu an mevcut değil. Talebiniz alındı.<br>";
    }
}

class Kullanici
{
    public $id, $isim, $email, $sifre;

    public function __construct($id, $isim, $email, $sifre)
    {
        $this->id = $id;
        $this->isim = $isim;
        $this->email = $email;
        $this->sifre = $sifre;
    }

    public function filmSatınAl(Film $film)
    {
        echo "💰 {$this->isim}, '{$film->ad}' filmini ₺{$film->fiyat} karşılığında satın aldı.<br>";
    }

    public function filmKirala(Film $film)
    {
        echo "❌ {$this->isim}: Sadece aboneler film kiralayabilir.<br>";
    }
}

class Abone extends Kullanici;
{
    public $kredi;

    public function __construct($id, $isim, $email, $sifre, $kredi = 0)
    {
        parent::__construct($id, $isim, $email, $sifre);
        $this->kredi = $kredi;
    }

    public function krediYukle($miktar)
    {
        $this->kredi += $miktar;
        echo "💳 {$this->isim}, ₺{$miktar} kredi yükledi. Yeni bakiye: ₺{$this->kredi}<br>";
    }

    public function filmKirala(Film $film)
    {
        if (!$film->mevcut) {
            $film->filmTalep

// ==========================
// Sınıf Tanımları
// ==========================

class Film
{
    public $id, $ad, $fiyat, $kiraFiyati, $mevcut;

    public function __construct($id, $ad, $fiyat, $kiraFiyati, $mevcut = true)
    {
        $this->id = $id;
        $this->ad = $ad;
        $this->fiyat = $fiyat;
        $this->kiraFiyati = $kiraFiyati;
        $this->mevcut = $mevcut;
    }

    public function filmTalepEt()
    {
        echo "⛔ '{$this->ad}' filmi şu an mevcut değil. Talebiniz alındı.<br>";
    }
}

class Kullanici
{
    public $id, $isim, $email, $sifre;

    public function __construct($id, $isim, $email, $sifre)
    {
        $this->id = $id;
        $this->isim = $isim;
        $this->email = $email;
        $this->sifre = $sifre;
    }

    public function filmSatınAl(Film $film)
    {
        echo "💰 {$this->isim}, '{$film->ad}' filmini ₺{$film->fiyat} karşılığında satın aldı.<br>";
    }

    public function filmKirala(Film $film)
    {
        echo "❌ {$this->isim}: Sadece aboneler film kiralayabilir.<br>";
    }
}

class Abone extends Kullanici
{
    public $kredi;

    public function __construct($id, $isim, $email, $sifre, $kredi = 0)
    {
        parent::__construct($id, $isim, $email, $sifre);
        $this->kredi = $kredi;
    }

    public function krediYukle($miktar)
    {
        $this->kredi += $miktar;
        echo "💳 {$this->isim}, ₺{$miktar} kredi yükledi. Yeni bakiye: ₺{$this->kredi}<br>";
    }

    public function filmKirala(Film $film)
    {
        if (!$film->mevcut) {
            $film->filmTalepEt();
            return;
        }

        if ($this->kredi >= $film->kiraFiyati) {
            $this->kredi -= $film->kiraFiyati;
            echo "🎬 {$this->isim}, '{$film->ad}' filmini ₺{$film->kiraFiyati} ile kiraladı. Kalan kredi: ₺{$this->kredi}<br>";
        } else {
            echo "⚠️ {$this->isim}, yeterli krediye sahip değil. Gerekli: ₺{$film->kiraFiyati}, Bakiye: ₺{$this->kredi}<br>";
        }
    }
}

class FilmSistemi
{
    public $filmler = [];
    public $kullanicilar = [];

    public function listeleFilmler()
    {
        echo "<h3>🎞️ Film Listesi:</h3>";
        foreach ($this->filmler as $film) {
            echo "- {$film->ad} | Satış: ₺{$film->fiyat} | Kira: ₺{$film->kiraFiyati} | Mevcut: " . ($film->mevcut ? "✅" : "❌") . "<br>";
        }
    }

    public function siralaFilmler()
    {
        usort($this->filmler, fn($a, $b) => $a->fiyat <=> $b->fiyat);
    }
}

// ==========================
// Sistem Kurulumu ve Örnek Kullanım
// ==========================

// Film sistemi oluştur
$sistem = new FilmSistemi();

// Filmler ekle
$sistem->filmler[] = new Film(1, "Matrix", 40, 10);
$sistem->filmler[] = new Film(2, "Inception", 50, 15, false); // mevcut değil
$sistem->filmler[] = new Film(3, "Interstellar", 60, 20);

// Kullanıcılar
$ali = new Kullanici(1, "Ali", "ali@example.com", "1234");
$veli = new Abone(2, "Veli", "veli@example.com", "abcd", 25);

$sistem->kullanicilar[] = $ali;
$sistem->kullanicilar[] = $veli;

// Film listesi
$sistem->listeleFilmler();

echo "<hr>";

// Ali (Normal Kullanıcı)
$ali->filmSatınAl($sistem->filmler[0]);     // Satın alabilir
$ali->filmKirala($sistem->filmler[0]);      // Kiralayamaz

echo "<br>";

// Veli (Abone)
$veli->filmKirala($sistem->filmler[1]);     // Mevcut değil → talep
$veli->filmKirala($sistem->filmler[2]);     // Kiralama başarılı
$veli->krediYukle(10);                      // Kredi yükleme
$veli->filmKirala($sistem->filmler[0]);     // Kiralama başarılı
Et();
            return;
        }

        if ($this->kredi >= $film->kiraFiyati) {
            $this->kredi -= $film->kiraFiyati;
            echo "🎬 {$this->isim}, '{$film->ad}' filmini ₺{$film->kiraFiyati} ile kiraladı. Kalan kredi: ₺{$this->kredi}<br>";
        } else {
            echo "⚠️ {$this->isim}, yeterli krediye sahip değil. Gerekli: ₺{$film->kiraFiyati}, Bakiye: ₺{$this->kredi}<br>";
        }
    }
}

class FilmSistemi
{
    public $filmler = [];
    public $kullanicilar = [];

    public function listeleFilmler()
    {
        echo "<h3>🎞️ Film Listesi:</h3>";
        foreach ($this->filmler as $film) {
            echo "- {$film->ad} | Satış: ₺{$film->fiyat} | Kira: ₺{$film->kiraFiyati} | Mevcut: " . ($film->mevcut ? "✅" : "❌") . "<br>";
        }
    }

    public function siralaFilmler()
    {
        usort($this->filmler, fn($a, $b) => $a->fiyat <=> $b->fiyat);
    }
}
