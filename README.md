# PHP captcha generator

<p align="center">
  <strong>A simple API for creating captchas and retrieving captcha information with its identifier.</strong>
</p>

<p align="center">
  <img src="captchaImgs/a089a73bec9e129705e20c0c1.png" width="280" />
</p>

## Features 🚀

- 🔢 Complexity of readability for AI's.
- 🔄 Easy to use.
- 🌈 Automatic contrast management.

## Prerequisites for use 🛠️
- NONE

## Prerequisites for installation 🛠️

- PHP 8.0.X
- MariaDB 10.10.X

## How to Run the Project ▶️

1. Clone this repository to your local machine.
2. Import SQL file.
3. Modify your database connection information. (`model/dbModel.php`)

## Authentication key 🔑

- All keys are stored in the `keys` table in the database.


## How to use the API 🔍

### CURL
```
curl -X GET "http://localhost/generate.php?length=8&foreground=%23FF0000&background=%23FFFFFF&apiKey=test"

```

### AJAX
```
function generateCaptcha() {
    var xhr = new XMLHttpRequest();
    var url = 'http://localhost/Captcha-Gen-API/generate.php'; // Remplacez par l'URL de votre API

    // Paramètres de la requête (length, foreground, background, userKey)
    var params = "length=8&foreground=%23FF0000&background=%23FFFFFF&apiKey=test"; // Remplacez les valeurs

    xhr.open('GET', url + '?' + params, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                // Utilisez les données de réponse (code_generated, image_link, request_id)
                console.log(response);
            } else {
                console.error('Erreur lors de la requête : ' + xhr.status);
            }
        }
    };

    xhr.send();
}
```


## Authors ✨

[@KoZeuh](https://github.com/KoZeuh)

## License 📄

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
