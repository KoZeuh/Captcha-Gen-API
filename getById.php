<?php
    $apiKey = $_GET['apiKey'] ?? null;
    $captchaId = $_GET['id'] ?? null;

    require_once "./controller/captchaController.php";

    $dbModel = new DBModel();
    $captchaModel = new Captcha(); 
    $captchaController = new CaptchaController($captchaModel, $apiKey, $dbModel);

    $captchaController->getCaptchaInfoAction($captchaId);
?>