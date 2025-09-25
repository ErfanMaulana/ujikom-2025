@echo off
REM Script untuk menjalankan update motor status secara otomatis
REM Simpan script ini dan tambahkan ke Windows Task Scheduler untuk berjalan setiap hari jam 00:01

cd /d "C:\laragon\www\ujikom-2025-erfan"
php artisan motor:update-status

REM Log hasil ke file
echo %date% %time% - Motor status updated >> logs\scheduler.log