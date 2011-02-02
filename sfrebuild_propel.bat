@echo off
cls

echo ----                ----
echo ---- Building model ----
echo ----                ----
php symfony propel:build-model

echo ----              ----
echo ---- Building sql ----
echo ----              ----
php symfony propel:build-sql

echo ----                ----
echo ---- Building forms ----
echo ----                ----
php symfony propel:build-forms

echo ----                  ----
echo ---- Building filters ----
echo ----                  ----
php symfony propel:build-filters

CHOICE /C YN /M "Do you want to recreate the database?"
IF ERRORLEVEL == 2 GOTO NOCREATEDB

echo ----                     ----
echo ---- Recreating database ----
echo ----                     ----
php symfony propel:insert-sql

echo ----                  ----
echo ---- Loading fixtures ----
echo ----                  ----
php symfony propel:data-load

echo ----                    ----
echo ---- Creating superuser ----
echo ----                    ----
php symfony guard:create-user superadmin a$ston
php symfony guard:promote superadmin

:NOCREATEDB

echo ----                      ----
echo ---- Extract translations ----
echo ----                      ----
php symfony i18n:extract --auto-save --auto-delete frontend en

echo ----                    ----
echo ---- Clearing the cache ----
echo ----                    ----
php symfony cc

