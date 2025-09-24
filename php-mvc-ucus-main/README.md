
# Refactored MVC Skeleton

Bu klasör, orijinal projenizi daha düzenli bir **MVC** yapısına taşımak için otomatik olarak oluşturulmuştur.
Aşağıdaki yapıyı içerir:

```
refactored_mvc/
  public/
    index.php             # Front Controller & Router entegrasyonu
  app/
    config.php            # DB ve yol ayarları
    core/
      Router.php          # Basit Router
    controllers/
      HomeController.php
      DemoController.php
      LegacyController.php
    models/
      Database.php        # PDO tabanlı bağlantı ve query yardımı
      BaseModel.php
      *Model.php          # Tablolardan türetilen model iskeletleri
    views/
      header.php
      footer.php
      legacy/             # Orijinalde HTML içeren sayfalar kopyalandı
  legacy/                 # Orijinal projenin tamamı referans için
  REPORT_sql_map.csv      # SQL içeren dosyalar ve bulunan tablolar
```

## Nasıl Çalıştırılır?
- Web kökü `public/` dizinidir. Sunucunuzun kökünü `public`'e yönlendirin.
- `app/config.php` içinde DB bilgilerinizi doğrulayın/güncelleyin.
- Tarayıcıdan:
  - `/public/index.php?c=Home&a=index`
  - `/public/index.php?c=Legacy&a=show&path=index.php` (orijinal görünümü göstermek için)
  - `/public/index.php?c=Demo&a=index` (DB bağlantı testi)

## Notlar
- Otomatik sınıflandırma **heuristic** ile yapıldı. Tüm sayfalar tam olarak MVC kurallarına taşınmadı.
- `views/legacy/` içine taşınan dosyalar büyük oranda HTML barındıran sayfalardır.
- SQL içeren dosyalar `REPORT_sql_map.csv` dosyasında listelendi. Buradaki tablolar için iskelet *Model* sınıfları oluşturuldu.
- Controller ve View ayrımı için ek düzenlemeler (form handler'ların controller'a taşınması, view'larda PHP iş mantığının azaltılması) önerilir.

## DB Config Kaynağı
DB bilgileri otomatik tespit edilemedi; varsayılan değerler yazıldı.
