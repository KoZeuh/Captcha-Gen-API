<?php
    $apiKey = $_GET['apiKey'] ?? null;
    $captchaId = $_GET['id'] ?? null;

    require_once "./controller/captchaController.php";

    $captchaModel = new Captcha(); 
    $captchaController = new CaptchaController($captchaModel, $apiKey);

    $captchaController->getCaptchaInfoAction($captchaId);
?>