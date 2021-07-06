# PHP Python Face Recognition
This application is an example how use a face recognition using a python shell scripts integrated with PHP using Laravel as framework. There are three pages: list, register and identify. It was used OpenCV (cv2) as library for training datasets and recognizer.

## Requirements
- Python 3.* including libraries opencv-contrib-python, numpy, pillow
- PHP 7.*
- Javascript, NPM
- OpenCV

## Configuration
Create a file .env in root application and set a database configuration. Documentation (https://laravel.com/docs/7.x/configuration).

```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Set a new variable for Python executable. In my case is */anaconda3/bin/python*. The purpose of this variable it`s related to shell permissions. Reference (https://stackoverflow.com/questions/58627223/python-no-module-named-when-script-is-executed-by-php-with-shell-exec).

```
PYTHON_PATH=
```

Base URL for javascript.

```
MIX_APP_URL="${APP_URL}"
```

## Instalation
```
composer install
npm install && npm run dev
php artisan migrate
```

## Reset and Refresh Database
```
php artisan face-recog:reset
````
This command remove trainer filed used in Opencv, exclude dataset where contains all images used for training and refresh/migrate database.

   remove this line
