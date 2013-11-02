#!/bin/sh
mysqldump -u root -p'root' --single-transaction wtmsp | gzip -c | ssh jama@14.200.73.17 "gunzip -c | mysql -u root -p'root' wtmsp"