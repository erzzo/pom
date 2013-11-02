#!/bin/sh
ssh jama@14.200.73.17 "mysqldump -u root -p'root' --single-transaction wtmsp | gzip -c" | gunzip -c | mysql -u root -p'root' wtmsp