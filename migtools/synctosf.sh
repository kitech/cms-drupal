#!/bin/bash

# usage: bash /path/to/this/script

selfsh=$0
selfdir=$(dirname $(readlink -f $0))
seckeyfile=$selfdir/seckey.sh
[[ ! -f $seckeyfile ]] || (echo 'seckey file error' && exist)
# got these vars
#sfuser=
#passwd=
#dbpass=
#locdbuser=
#locdbpass=
source $seckeyfile
dbname="n186258_drupal"
sfdir=/home/users/l/li/$sfuser
subcmd=$1

set -x

function checkrsh()
{
    echo "check temp shell ..."
    #ssh -t $sfuser,nullget@shell.sf.net create
    plink -pw "$passwd" -ssh -t -l "$sfuser,nullget" shell.sf.net "exit"
    # exit on remote host
}
function creatersh()
{
    echo "create temp shell ..."
    #ssh -t $sfuser,nullget@shell.sf.net create
    plink -pw "$passwd" -ssh -t -l "$sfuser,nullget" shell.sf.net create
    # exit on remote host
}
function tranupme()
{
    echo "put backcmd ..."
    pscp -pw "$passwd" -C $selfsh $seckeyfile $sfuser,nullget@shell.sf.net:$sfdir/
}
function trandelme()
{
    echo "del backcmd ..."
    file1=$(basename $selfsh)
    file2=$(basename $seckeyfile)
    plink -pw "$passwd" -C -ssh -batch -t -l "$sfuser,nullget" shell.sf.net "rm -fv $file1 $file2"
}
function backonsftab()
{
    tab=$1
    echo "backing drupal $tab..."
    baksqlfile="$tab.sql"
    mysqldump --add-drop-database -hmysql-n -un186258admin -p"$dbpass" n186258_drupal "$tab" > $baksqlfile
    ls -lh $baksqlfile

    echo 'select count(*) as node_of_count from node' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal > drupsync.log
    echo 'select count(*) as node_of_count from users' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal >> drupsync.log
    echo 'select count(*) as node_of_count from comment' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal >> drupsync.log
    echo '===' >> drupsync.log

    echo "backonsf all done $tab"
}
function imponsftab()
{
    tab=$1
    echo "imping drupal $tab..."
    baksqlfile="$tab.sql"
    ls -lh $baksqlfile
    mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal < $baksqlfile

    echo 'select count(*) as node_of_count from node' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal >> drupsync.log
    echo 'select count(*) as node_of_count from users' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal >> drupsync.log
    echo 'select count(*) as node_of_count from comment' | mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal >> drupsync.log
    date >> drupsync.log

    echo "imponsf all done $tab"
}
function imponsfdb()
{
    tab=$1
    echo "imping drupal db ..."
    baksqlfile="n186258_drupal.sql"
    ls -lh $baksqlfile
    mysql -hmysql-n -un186258admin -p"$dbpass" n186258_drupal < $baksqlfile

    echo "imponsf all done $tab"
}

# simple check args
if [[ $sfuser == '' ]] || [[ $passwd == '' ]] || [[ $locdbuser == '' ]] || [[ $locdbpass == '' ]]; then
    echo "invalid args"
    exit
fi

# remote cmd
if [[ $subcmd == "remotebaktab" ]]; then
    backonsftab "node_counter"
    exit
elif [[ $subcmd == "remoteimptab" ]]; then
    imponsftab "node_counter"
    exit
elif [[ $subcmd == "remoteimpdb" ]]; then
    imponsfdb
    exit
fi

# local cmd
checkrsh
ret=$?
if [ x"$ret" != x"0" ]; then
    creatersh;
    if [[ $? != 0 ]]; then
        exit
    fi
else
    echo "temp shell exist"
fi

# dump local
dbname=n186258_drupal
dbsqlfile="$dbname.sql"
echo "dump local database ..."
mysqldump --add-drop-database -h127.0.0.1 -u$locdbuser -p$locdbpass $dbname > $dbsqlfile
[[ $? == 0 ]] || (echo 'dump dbfile error' && exit)
echo "put database ..."
pscp -pw "$passwd" -C $dbsqlfile $sfuser,nullget@shell.sf.net:$sfdir/
[[ $? == 0 ]] || (echo 'put dbfile error' && exit)

tranupme;
plink -pw "$passwd" -C -ssh -batch -t -l "$sfuser,nullget" shell.sf.net "./synctosf.sh remotebaktab"
plink -pw "$passwd" -C -ssh -batch -t -l "$sfuser,nullget" shell.sf.net "./synctosf.sh remoteimpdb"
plink -pw "$passwd" -C -ssh -batch -t -l "$sfuser,nullget" shell.sf.net "./synctosf.sh remoteimptab"
trandelme;
pscp -pw "$passwd" -C $sfuser,nullget@shell.sf.net:$sfdir/drupsync.log .


