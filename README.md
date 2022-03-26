# PHP Python Face Recognition
This application is an example how use a face recognition using a python shell scripts integrated with PHP using Laravel as framework. There are three pages: list, register and identify. It was used OpenCV (cv2) as library for training datasets and recognizer.

## Requirements
- Python 3.* including libraries opencv-contrib-python, numpy, pillow
- PHP 7.*
- Javascript, NPM
- OpenCV

## Instalation
### Vagrant
```
vagrant up
```
In your hosts file set: 
```
192.168.33.10   php-python-facerecog.local 
```


## Reset and Refresh Database
```
php artisan face-recog:reset
````
This command remove trainer filed used in Opencv, exclude dataset where contains all images used for training and refresh/migrate database.