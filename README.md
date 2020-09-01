#PHP Python Face Recognition
This application is a example how use a face recognition using a python shell scripts integrated with PHP using Laravel as framework. There are three pages: list, register and identify. It was used Opencv (cv2) as library for training datasets and recognizer.

##Requisites
Python 3.* including libraries cv2, numpy
PHP 7.*
Javascript, NPM
OpenCV

##Configuration
Create a file .env in root application and set a database configuration. Documentation (https://laravel.com/docs/7.x/configuration).

>DB_CONNECTION=
>DB_HOST=
>DB_PORT=
>DB_DATABASE=
>DB_USERNAME=
>DB_PASSWORD=

Set a new variable for Python executable. In mycase is /anaconda3/bin/python. The purpose of this variable it`s about shell permissions. Reference (https://stackoverflow.com/questions/58627223/python-no-module-named-when-script-is-executed-by-php-with-shell-exec).

>PYTHON_PATH=

##Instalation
>composer install
>npm install && npm run dev
>php artisan migrate