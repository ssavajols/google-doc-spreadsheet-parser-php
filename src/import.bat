@echo off

set /p login=Veuillez indiquer votre login:
set /p password=Veuillez indiquer votre mot de passe:

php index.php %login% %password%
