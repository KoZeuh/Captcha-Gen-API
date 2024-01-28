<?php
    require_once "./config.php";
    require_once "./model/captchaModel.php";
    require_once "./model/dbModel.php";

    class CaptchaController {
        private Captcha $captchaModel;
        private DBModel $dbModel;
        private string $apiKey;

        public function __construct($captchaModel, $apiKey) {
            $this->dbModel = DBModel::getInstance();

            if (!$apiKey) {
                return $this->sendResponse(['error' => 'No API key provided']);
            }else if (!$this->dbModel->getApiKeyDoesExist($apiKey)) {
                return $this->sendResponse(['error' => 'Invalid API key']);
            }

            $this->captchaModel = $captchaModel;
            $this->apiKey = $apiKey;
        }

        private function isValidContrast($foreground, $background) {
            // Conversion des couleurs hexadécimales en valeurs RVB
            list($fr, $fg, $fb) = sscanf($foreground, "#%02x%02x%02x");
            list($br, $bg, $bb) = sscanf($background, "#%02x%02x%02x");
    
            // Calcul de la luminosité relative (formule YIQ)
            $fgLuminance = ($fr * 0.299 + $fg * 0.587 + $fb * 0.114) / 255;
            $bgLuminance = ($br * 0.299 + $bg * 0.587 + $bb * 0.114) / 255;
    
            // Vérification du contraste (exemple de seuil)
            // Modifiez cette valeur de seuil selon vos préférences de contraste
            $contrastThreshold = 0.5;
    
            // Vérifie si le contraste est suffisant (par exemple, si la différence de luminance est supérieure au seuil)
            return abs($fgLuminance - $bgLuminance) > $contrastThreshold;
        }
    
        private function generateRandomColor() {
            return sprintf("#%02x%02x%02x", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        }

        public function sendResponse($data): void {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        // Action pour générer un nouveau captcha
        public function generateCaptchaAction($length, $foreground, $background): void {
            if (!$foreground) {
                $foreground = $this->generateRandomColor();
            }

            if (!$background) {
                $background = $this->generateRandomColor();
            }

            while (!$this->isValidContrast($foreground, $background)) {
                $foreground = $this->generateRandomColor();
                $background = $this->generateRandomColor();
            }

            $length = Config::getCaptchaLength(intval($length));
            $this->captchaModel->generateCaptcha($length, $foreground, $background);

            $captchaText = $this->captchaModel->getCaptchaText();
            $captchaKey = $this->captchaModel->getCaptchaKey();

            $imagePath = './captchaImgs/' . $captchaKey . '.png';
            $baseURL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            $baseURL .= $_SERVER['HTTP_HOST'];
            $imageURL = $baseURL . '/' . $imagePath;

            $this->dbModel->insertCaptcha($captchaText, $captchaKey, $this->apiKey);

            $this->sendResponse([
                'id' => $captchaKey,
                'text' => $captchaText,
                'img' => $imageURL
            ]);
        }

        public function getCaptchaInfoAction($captchaId): void {
            if (!$captchaId) {
                $this->sendResponse(['error' => 'No captcha ID provided']);
            }

            $responseData = $this->dbModel->getCaptchaInfoById($captchaId);
        
            if ($responseData) {
                $baseURL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                $baseURL .= $_SERVER['HTTP_HOST'];

                $this->sendResponse([
                    'id' => $responseData['image_key'],
                    'text' => $responseData['text'],
                    'img' => $baseURL . '/captchaImgs/' . $responseData['image_key'] . '.png'
                ]);
            } else {
                $this->sendResponse(['error' => 'Captcha not found']);
            }
        }
    }
?>

