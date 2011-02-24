#!/bin/bash

BASEDIR=${BASEDIR:-$(dirname $(readlink -f $0))}
WWWUSER=${WWWUSER:-"www-data"}
MYSQLUSER=${MYSQLUSER:-"cupapp"}
MYSQLDB=${MYSQLDB:-"cupapp"}
MYSQLARGS=${MYSQLARGS:-"-u root -p"}

if [ "$MYSQLPASSWORD" != "" ]; then
    MYSQLIDENTIFIEDBY="identified by '$MYSQLPASSWORD'"
fi

function run_cmd()
{
    echo Running: \"$@\" ...
    if eval "$@"; then
	echo OK
    else
	echo "Failed..."
	exit 1
    fi
}

pushd "$BASEDIR" > /dev/null

run_cmd mkdir -p cache log web/uploads/userimg
run_cmd sudo chgrp -R $WWWUSER cache log web/uploads
run_cmd chmod -R g+rwx cache log web/uploads

run_cmd "mysql $MYSQLARGS -e \"create database $MYSQLDB default character set utf8; create user '$MYSQLUSER'@'localhost' $MYSQLIDENTIFIEDBY; grant all on ${MYSQLDB}.* to '$MYSQLUSER'@'localhost';\""

run_cmd "echo \"all:
  propel:
    class: sfPropelDatabase
    param:
      classname: PropelPDO
      dsn: 'mysql:host=localhost;dbname=$MYSQLDB'
      username: $MYSQLUSER
      password: $MYSQLPASSWORD
      encoding: utf8
      persistent: true
      pooling: true\" > config/databases.yml"

run_cmd ./sfrebuild_propel

popd > /dev/null
