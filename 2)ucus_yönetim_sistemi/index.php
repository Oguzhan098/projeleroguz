<?php


// ---------------------- Havaalani Sınıfı ----------------------
class Havaalani
{
    public $id;
    public $isim;

    public function __construct($id, $isim)
    {
        $this->id = $id;
        $this->isim = $isim;
    }
}

// ---------------------- Pilot Sınıfı ----------------------
class Pilot
{
    public $id;
    public $adSoyad;
    public $deneyimYili;

    public function __construct($id, $adSoyad, $deneyimYili)
    {
        $this->id = $id;
        $this->adSoyad = $adSoyad;
        $this->deneyimYili = $deneyimYili;
    }
}

// ---------------------- UcakTipi Sınıfı ----------------------
class UcakTipi
{
    public $isim;
    public $gerekliPilotSayisi;

    public function __construct($isim, $gerekliPilotSayisi)
    {
        $this->isim = $isim;
        $this->gerekliPilotSayisi = $gerekliPilotSayisi;
    }
}

// ---------------------- Ucak Sınıfı ----------------------
class Ucak
{
    public $id;
    public $tip;
    public $durum; // 'Calisir' veya 'Onarimda'

    public function __construct($id, UcakTipi $tip, $durum = 'Calisir')
    {
        $this->id = $id;
        $this->tip = $tip;
        $this->durum = $durum;
    }

    public function durumGetir()
    {
        return $this->durum;
    }
}

// ---------------------- Ucus Sınıfı ----------------------
class Ucus
{
    public $id;
    public $kalkisSaati;
    public $inisSaati;
    public $kalkisHavaalani;
    public $varisHavaalani;
    public $pilot;
    public $yardimciPilot;
    public $ucak;

    public function __construct($id, $kalkisSaati, $inisSaati, Havaalani $kalkis, Havaalani $varis, Pilot $pilot, Pilot $yardimci, Ucak $ucak)
    {
        $this->id = $id;
        $this->kalkisSaati = $kalkisSaati;
        $this->inisSaati = $inisSaati;
        $this->kalkisHavaalani = $kalkis;
        $this->varisHavaalani = $varis;
        $this->pilot = $pilot;
        $this->yardimciPilot = $yardimci;
        $this->ucak = $ucak;
    }

    public function ucusBilgi()
    {
        echo "<hr>";
        echo "<b>Uçuş ID:</b> {$this->id}<br>";
        echo "<b>Kalkış:</b> {$this->kalkisHavaalani->isim} ({$this->kalkisSaati})<br>";
        echo "<b>Varış:</b> {$this->varisHavaalani->isim} ({$this->inisSaati})<br>";
        echo "<b>Uçak:</b> {$this->ucak->id} ({$this->ucak->tip->isim}) - Durum: {$this->ucak->durumGetir()}<br>";
        echo "<b>Pilot:</b> {$this->pilot->adSoyad} ({$this->pilot->deneyimYili} yıl deneyim)<br>";
        echo "<b>Yardımcı Pilot:</b> {$this->yardimciPilot->adSoyad}<br>";
    }
}

// ---------------------- HavayoluSirketi Sınıfı ----------------------
class HavayoluSirketi
{
    public $id;
    public $isim;
    public $pilotlar = [];
    public $ucaklar = [];
    public $ucuslar = [];

    public function __construct($id, $isim)
    {
        $this->id = $id;
        $this->isim = $isim;
    }

    public function pilotEkle(Pilot $pilot)
    {
        $this->pilotlar[] = $pilot;
    }

    public function ucakEkle(Ucak $ucak)
    {
        $this->ucaklar[] = $ucak;
    }

    public function ucusEkle(Ucus $ucus)
    {
        $this->ucuslar[] = $ucus;
    }

    public function listeleUcuslar()
    {
        echo "<h2>{$this->isim} - Uçuş Listesi</h2>";
        foreach ($this->ucuslar as $ucus) {
            $ucus->ucusBilgi();
        }
    }
}

// ---------------------- Örnek Veri ve Kullanım ----------------------

// Havaalanları
$istanbul = new Havaalani(1, "İstanbul Havalimanı");
$ankara = new Havaalani(2, "Ankara Esenboğa");

// Uçak Tipi
$tip737 = new UcakTipi("Boeing 737", 2);

// Uçak
$ucak1 = new Ucak("UC-100", $tip737, "Calisir");

// Pilotlar
$pilot1 = new Pilot(1, "Ahmet Demir", 10);
$pilot2 = new Pilot(2, "Mehmet Yıldız", 5);

// Havayolu Şirketi
$thy = new HavayoluSirketi(1001, "Türk Hava Yolları");
$thy->pilotEkle($pilot1);
$thy->pilotEkle($pilot2);
$thy->ucakEkle($ucak1);

// Uçuş
$ucus1 = new Ucus("THY-1234", "08:00", "09:30", $istanbul, $ankara, $pilot1, $pilot2, $ucak1);
$thy->ucusEkle($ucus1);

// Listeleme
$thy->listeleUcuslar();


