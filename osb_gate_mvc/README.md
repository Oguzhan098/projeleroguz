# OSB Giriş-Çıkış Takip — PHP 8.0 MVC (PostgreSQL)

## Kurulum
1) DB oluştur:
   CREATE DATABASE osb_gate ENCODING 'UTF8';
2) .env düzenle (DB/TZ).
3) Migrasyon:
   php bin/migrate
4) Sunucu kökü `public/`.

## Rotalar
GET /                -> Dashboard
GET /movements       -> Liste
GET /movements/create-> Form
POST /movements      -> Kaydet
GET /reports/daily   -> Günlük rapor
POST /scan           -> Placeholder
