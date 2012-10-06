#!/bin/bash

wget http://pecl.php.net/get/mongo-2.2.0.tgz
tar -xzf mongo-2.2.0.tgz
sh -c "cd mongo-2.2.0 && phpize && ./configure && sudo make install"
echo "extension=mongo.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`