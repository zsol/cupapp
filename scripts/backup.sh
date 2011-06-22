#!/bin/bash

INSTANCE="$1"

BACKUPDIR=/home/cupapp/Dropbox/backup/$INSTANCE

if [ "x$INSTANCE" = "x" ]; then
    echo "Specify instance"
    exit 1
fi

FILENAME=$(date +%Y-%m-%d)
if [ -e $BACKUPDIR/$FILENAME ]; then
    echo $BACKUPDIR/$FILENAME exists
    exit 2
fi

USER=$(grep username: $HOME/$INSTANCE/config/databases.yml | sed 's/^.*:\s*\(\S\+\)/\1/')
PASSWORD=$(grep password: $HOME/$INSTANCE/config/databases.yml | sed 's/^.*:\s*\(\S\+\)/\1/')

BACKUP=$(mktemp -d)

mkdir ${BACKUP}/$FILENAME
cd $BACKUP

mysqldump -u $USER --password=$PASSWORD --dump-date $INSTANCE > $FILENAME/sql
cp -a $HOME/$INSTANCE/web/uploads $FILENAME/

tar cjf $BACKUP/$FILENAME.tar.bz2 --preserve-permissions --preserve-order $FILENAME/ 2> /dev/null || echo "ERROR DURING TAR"

if [[ ! -e $HOME/$INSTANCE/.key ]]; then
    pwgen -s 20 | head -n 6 | tail -n 1 | cut -d' ' -f1 > $HOME/$INSTANCE/.key
fi

openssl enc -aes256 -e -pass file:$HOME/$INSTANCE/.key -in $BACKUP/$FILENAME.tar.bz2 -out $BACKUPDIR/$FILENAME.tar.bz2.enc
openssl enc -aes256 -d -pass file:$HOME/$INSTANCE/.key -in $BACKUPDIR/$FILENAME.tar.bz2.enc -out $BACKUP/dec

if ! cmp -s $BACKUP/dec $BACKUP/$FILENAME.tar.bz2; then
    echo "Error during encryption check, backup dir is $BACKUP"
    exit 3
fi

find "${BACKUPDIR}" -mtime +5 -printf '%f %p\n' | sort -n | while read REPLY; do 
    yearweek=$(date -d $(echo $REPLY | cut -d' ' -f1 | cut -d'.' -f1) "+%Y %W"); 
    if [[ "${current}" != "" && "${current}" == "${yearweek}" ]]; then 
        rm $(echo $REPLY | cut -d' ' -f2); 
    else 
        current="$yearweek"; 
    fi; 
done
cd - > /dev/null

rm -rf $BACKUP
