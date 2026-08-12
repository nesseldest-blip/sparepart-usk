@echo off
echo ============================================
echo  Setup Firewall untuk Akses dari HP
echo ============================================
echo.
echo Menambahkan aturan firewall untuk port 80...
echo.

netsh advfirewall firewall add rule name="XAMPP Apache Port 80" dir=in action=allow protocol=TCP localport=80

echo.
echo ============================================
echo  Selesai! Sekarang HP bisa mengakses website
echo  melalui IP: http://10.1.2.2/sparepart-usk/
echo ============================================
echo.
pause