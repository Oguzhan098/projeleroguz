<?php
$cfg=require __DIR__.'/../app/config/database.php';
$dsn=sprintf('%s:host=%s;port=%s;charset=%s',$cfg['driver'],$cfg['host'],$cfg['port'],$cfg['charset']);
try{$pdo=new PDO($dsn,$cfg['username'],$cfg['password'],$cfg['options']??[]);
$sql=file_get_contents(__DIR__.'/setup.sql');if($sql===false)throw new RuntimeException("setup.sql okunamadı.");
$pdo->beginTransaction();$pdo->exec($sql);$pdo->commit();echo "[OK] Migrasyon tamamlandı.\n";}
catch(Throwable $e){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();fwrite(STDERR,"[HATA]".$e->getMessage().PHP_EOL);exit(1);}