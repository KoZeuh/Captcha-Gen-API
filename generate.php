<?php
    $apiKey = $_GET['apiKey'] ?? null;
    $length = $_GET['length'] ?? null;
    $foreground = $_GET['foreground'] ?? null;
    $background = $_GET['background'] ?? null;

    require_once "./controller/captchaController.php";

    $captchaModel = new Captcha(); 
    $captchaController = new CaptchaController($captchaModel, $apiKey);

    $captchaController->generateCaptchaAction($length, $foreground, $background);
?>