<?php

require_once 'classes/Dikdortgen.php';
require_once 'classes/Kare.php';
require_once 'classes/Ucgen.php';

$dik = new Dikdortgen(5, 3);
echo "Dikdortgen Alan: " . $dik->alan() . "<br>";
echo "Dikdortgen Çevre: " . $dik->cevre() . "<br><br>";

$kare = new Kare(4);
echo "Kare Alan: " . $kare->alan() . "<br>";
echo "Kare Çevre: " . $kare->cevre() . "<br><br>";

$ucgen = new Ucgen(6, 5, 4, 3);
echo "Üçgen Alan: " . $ucgen->alan() . "<br>";
echo "Üçgen Çevre: " . $ucgen->cevre() . "<br>";
?>

