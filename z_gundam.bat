cp application/controllers/Test.php application/controllers/_Test.php

@echo off
git fetch
git status
echo origin
git pull origin devel
echo this is devel not live_market
git push origin devel
echo this is devel not live_market -v
echo ====
git push github devel
git status
