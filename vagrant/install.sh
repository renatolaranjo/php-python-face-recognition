sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password secret'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password secret'
curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
sudo apt-get -y install apache2 php php-mbstring php-mysql php-dom mysql-server nodejs python3-pip python3-opencv
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo cp composer.phar /usr/bin/composer   
cd /var/www/html && composer install --no-dev && npm install && npm run prod
sudo a2enmod ssl
sudo a2enmod rewrite
sudo cp vagrant/php-python-facerecog.conf /etc/apache2/sites-available/php-python-facerecog.conf
sudo a2ensite php-python-facerecog.conf
sudo systemctl reload apache2
mysqladmin create face-recog --user=root --password=secret --host=127.0.0.1 --protocol=tcp
cd /var/www/html && php artisan migrate
sudo pip3 install --upgrade pip
sudo pip3 install opencv-contrib-python pillow
sudo update-alternatives --install /usr/bin/python python /usr/bin/python3 1