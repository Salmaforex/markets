@echo off
git fetch
git status
echo origin
git pull origin live_market
git push origin live_market -v
echo ====
git status
echo other
git pull gitlab live_market
git push gitlab live_market -v
