Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "cmd /c cd C:\laragon\www\pos-droguerias && php artisan serve --host=0.0.0.0", 0
Set WshShell = Nothing