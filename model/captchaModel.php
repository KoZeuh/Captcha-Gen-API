<?php
    class Captcha {
        private string $captchaText;
        private string $captchaKey;

        // Méthode pour générer un captcha
        public function generateCaptcha($length, $foreground, $background): void {
            (string) $this->captchaText = $this->generateRandomString($length);
            (string) $this->captchaKey = $this->generateCaptchaKey();
            
            $this->createCaptchaImage($this->captchaText, $foreground, $background);
        }

        // Méthode pour récupérer le texte du captcha
        public function getCaptchaText(): string {
            return $this->captchaText;
        }

        // Méthode pour récupérer la clé du captcha
        public function getCaptchaKey(): string {
            return $this->captchaKey;
        }

        // Méthode pour valider le captcha
        public function validateCaptcha($userInput) {
            return $userInput == $this->captchaText;
        }

        // Méthode pour générer une chaîne aléatoire
        private function generateRandomString($length): string {
            return substr(bin2hex(random_bytes($length)), 0, $length);
        }

        // Méthode pour créer l'image du captcha
        private function createCaptchaImage($text, $foreground, $background): void {
            $fontSize = Config::FONT_SIZE;
            $fontFolder = Config::FONT_FOLDER;
            $imgHeight = Config::CAPTCHA_IMG_HEIGHT;

            $x = Config::INITIAL_X;
            $y = Config::INITIAL_Y;

            $imageWidth = strlen($text) * $fontSize * 1.5; // La largeur dépend de la taille de chaque caractère
        
            $image = imagecreatetruecolor($imageWidth, $imgHeight);
        
            $foregroundRGB = sscanf($foreground, "#%02x%02x%02x");
            $backgroundRGB = sscanf($background, "#%02x%02x%02x");
        
            $foregroundColor = imagecolorallocate($image, $foregroundRGB[0], $foregroundRGB[1], $foregroundRGB[2]);
            $backgroundColor = imagecolorallocate($image, $backgroundRGB[0], $backgroundRGB[1], $backgroundRGB[2]);
        
            imagefilledrectangle($image, 0, 0, $imageWidth, $imgHeight, $backgroundColor);
        

            for ($i = 0; $i < strlen($text); $i++) {
                $font = $fontFolder . mt_rand(1, 10) . '.ttf';
                $angle = mt_rand(-30, 30);
        
                // Distorsion aléatoire des caractères pour les rendre illisibles
                $charX = $x + mt_rand(-5, 5); // Variation horizontale
                $charY = $y + mt_rand(-5, 5); // Variation verticale

                // Ajout de formes géométriques aléatoires
                if (mt_rand(0, 1) == 1) {
                    // Ajout de lignes au-dessus des caractères
                    $y1 = $charY - mt_rand(5, 15);
                    $y2 = $y1 - mt_rand(5, 15);
                    imageline($image, $charX - 10, $y1, $charX + 10, $y2, $foregroundColor);
                } else {
                    // Ajout de lignes en dessous des caractères
                    $y1 = $charY + mt_rand(5, 15);
                    $y2 = $y1 + mt_rand(5, 15);
                    imageline($image, $charX - 10, $y1, $charX + 10, $y2, $foregroundColor);
                }
        
                imagettftext($image, $fontSize, $angle, $charX, $charY, $foregroundColor, $font, $text[$i]);
        
                $x += $fontSize * 1.5;
            }
        
            imagepng($image, './captchaImgs/' . $this->captchaKey . '.png');
            imagedestroy($image);
        }

        // Méthode pour générer une clé unique pour le captcha
        private function generateCaptchaKey(): string {
            return $this->generateRandomString(25);
        }
    }
?>