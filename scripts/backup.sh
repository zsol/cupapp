#!/bin/sh

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

tar cjf $BACKUPDIR/$FILENAME.tar.bz2 --preserve-permissions --preserve-order $FILENAME/ 2> /dev/null || echo "ERROR DURING TAR"

cd - > /dev/null

rm -rf $BACKUP
