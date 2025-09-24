<?php
// Tüm hataları göster
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Soyut Temel Sınıf (Abstraction)
abstract class Cihaz {
    protected $aktif = false;

    public function ac() {
        $this->aktif = true;
    }

    public function kapat() {
        $this->aktif = false;
    }

    public function durum() {
        return $this->aktif ? "Açık" : "Kapalı";
    }
}

// Asansör Sınıfı (Encapsulation, Inheritance)
class Asansor extends Cihaz {
    private $id;
    private $bulunduguKat = 1;
    private $hedefKat = 1;
    private $kapiAcik = false;
    private $kapasite = 6;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getKat() {
        return $this->bulunduguKat;
    }

    public function getKapiDurumu() {
        return $this->kapiAcik ? "Açık" : "Kapalı";
    }

    public function kataGit($kat) {
        if ($kat < 1 || $kat > 12) {
            echo "❌ Geçersiz kat: $kat<br>";
            return;
        }

        echo "🚀 Asansör {$this->id} {$this->bulunduguKat}. kattan $kat. kata gidiyor...<br>";
        sleep(1);
        $this->bulunduguKat = $kat;
        echo "✅ Asansör {$this->id} $kat. kata ulaştı<br>";
    }

    public function kapiAc() {
        $this->kapiAcik = true;
        echo "🚪 Asansör {$this->id} kapı açıldı<br>";
    }

    public function kapiKapat() {
        $this->kapiAcik = false;
        echo "🚪 Asansör {$this->id} kapı kapandı<br>";
    }
}

// Polymorphism örneği: AcilAsansor farklı davranır
class AcilAsansor extends Asansor {
    public function kataGit($kat) {
        echo "🚨 Acil Asansör öncelikli olarak hareket ediyor!<br>";
        parent::kataGit($kat);
    }
}

// Kat sınıfı
class Kat {
    private $numara;

    public function __construct($numara) {
        $this->numara = $numara;
    }

    public function zilCal() {
        echo "🔔 Kat {$this->numara} zili çaldı<br>";
    }
}

// Saat (simülasyon kontrolü)
class Saat {
    public static function zamanDamgasi() {
        return date("H:i:s");
    }
}

// Simülasyon Başlatıcı
class Simulasyon {
    private $asansorler = [];

    public function __construct($adet = 5) {
        for ($i = 1; $i <= $adet; $i++) {
            $this->asansorler[] = new Asansor($i);
        }
    }

    public function yolcuCagir($kat) {
        echo "<hr><b>🕓 " . Saat::zamanDamgasi() . "</b> - $kat. katta yolcu asansör çağırıyor...<br>";
        $secilen = $this->asansorler[rand(0, count($this->asansorler) - 1)];
        $secilen->kataGit($kat);
        $secilen->kapiAc();
        sleep(1);
        $hedef = rand(1, 12);
        while ($hedef == $kat) $hedef = rand(1, 12); // Aynı kata gitmesin
        echo "🧍 Yolcu $hedef. kata gitmek istiyor<br>";
        $secilen->kapiKapat();
        $secilen->kataGit($hedef);
        $secilen->kapiAc();
        echo "🧍 Yolcu indi<br>";
        $secilen->kapiKapat();
    }
}

// Simülasyon Çalıştır
$sim = new Simulasyon();
$sim->yolcuCagir(rand(1, 12));  // rastgele bir kat
$sim->yolcuCagir(rand(1, 12));
