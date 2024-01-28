<?php 
    class DBModel {
        private PDO $db;
        private static $instance;

        private string $host = "localhost";
        private string $dbName = "captcha";
        private string $userName = "root";
        private string $password = "";

        public function __construct() {
            try {
                $this->db = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->userName, $this->password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }

        public static function getInstance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function getApiKeyDoesExist($apiKey) {
            $query = $this->db->prepare("SELECT * FROM `keys` WHERE `value` = :keyValue");
            $query->execute(['keyValue' => $apiKey]);

            return $query->rowCount() > 0;
        }

        public function getApiKeyIDByValue($apiKey) {
            $query = $this->db->prepare("SELECT `id` FROM `keys` WHERE `value` = :keyValue");
            $query->execute(['keyValue' => $apiKey]);

            return $query->fetch(PDO::FETCH_ASSOC)['id'];
        }

        public function getCaptchaInfoById($captchaId) {
            $query = $this->db->prepare("SELECT text, image_key FROM `captcha` WHERE `image_key` = :id");
            $query->execute(['id' => $captchaId]);

            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function insertCaptcha($captchaText, $imageId, $apiKey) {
            $keyId = $this->getApiKeyIDByValue($apiKey);

            $query = $this->db->prepare("INSERT INTO `captcha` (`api_key_id`, `text`, `image_key`) VALUES (:keyId, :text, :imageId)");
            $query->execute(['keyId' => $keyId, 'text' => $captchaText, 'imageId' => $imageId]);
        }

        public function deleteCaptcha($captchaId) {
            $query = $this->db->prepare("DELETE FROM `captcha` WHERE `id` = :id");
            $query->execute(['id' => $captchaId]);
        }
    }
?>