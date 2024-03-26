@echo off
title VKPageBot
:bot_start
bin\php\php.exe bot.php
timeout 5
goto bot_start
pause
