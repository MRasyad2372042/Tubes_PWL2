@echo off
REM Windows helper to copy Sneat html/assets into project public/sneat
REM Edit SOURCE variable to point to where you saved the template (example path shown)
set SOURCE=D:\Tugas\PWL\sneat-1.0.0\sneat-1.0.0\html
if not exist "%SOURCE%" (
  echo SOURCE folder not found: %SOURCE%
  echo Please update the path in this script and re-run.
  exit /b 1
)
mkdir "%~dp0..\public\sneat" 2>nul
xcopy "%SOURCE%\*" "%~dp0..\public\sneat\" /E /I /Y
echo Copy complete. Verify public/sneat contains css, js, img folders.
