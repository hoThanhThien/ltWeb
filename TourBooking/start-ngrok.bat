@echo off
echo Đang tạo tunnel với domain cố định: awaited-hog-causal.ngrok-free.app

ngrok.exe start tour_web --config="ngrok.yml"

pause
