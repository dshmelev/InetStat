#!/bin/bash
#
#
date >> /usr/local/inetstat/var/update.log
su -l root -c "cd /usr/local/inetstat/ && git pull >> /usr/local/inetstat/var/update.log"
