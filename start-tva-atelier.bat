@echo off
title The Vaughn Atelier - Service Launcher
echo ========================================================
echo   THE VAUGHN ATELIER - SERVICE LAUNCHER & HEALTH CHECK
echo ========================================================
echo.
echo [1/3] Starting MySQL Database Server...
start "" /B cmd /c "C:\laragon\bin\mysql\start-mysql.bat > C:\laragon\bin\mysql\mysql_out.log 2>&1"
timeout /t 2 /nobreak >nul

echo [2/3] Verifying MySQL connection on port 3306...
powershell -NoProfile -Command "$t = Test-NetConnection -ComputerName 127.0.0.1 -Port 3306 -WarningAction SilentlyContinue; if ($t.TcpTestSucceeded) { Write-Host '>>> MySQL is ONLINE and ACCEPTING CONNECTIONS (Port 3306)' -ForegroundColor Green } else { Write-Host '>>> MySQL is starting up...' -ForegroundColor Yellow }"

echo [3/3] Verifying Laravel Server on port 8080...
powershell -NoProfile -Command "$t = Test-NetConnection -ComputerName 127.0.0.1 -Port 8080 -WarningAction SilentlyContinue; if ($t.TcpTestSucceeded) { Write-Host '>>> Web Server is ONLINE (Port 8080)' -ForegroundColor Green } else { Write-Host '>>> Launching php artisan serve on port 8080...'; Start-Process cmd -ArgumentList '/c php artisan serve --port=8080' }"

echo.
echo ========================================================
echo   Atelier Storefront: http://127.0.0.1:8080
echo ========================================================
echo.
pause
