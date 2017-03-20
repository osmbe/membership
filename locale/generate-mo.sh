#!/bin/sh
msgfmt -c -o ./fr_BE.UTF-8/LC_MESSAGES/members.mo ./fr_BE.UTF-8/LC_MESSAGES/members.po
msgfmt -c -o ./nl_BE.UTF-8/LC_MESSAGES/members.mo ./nl_BE.UTF-8/LC_MESSAGES/members.po
sudo service php7.0-fpm restart
