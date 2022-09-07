#!/usr/bin/bash

HTTPDIR=/var/www/html/
#CGI=$HTTPDIR/CGI
#INKSCAPE=
#MUSER= # MySQL username
#MPASS = #MySQL Password

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

function usage {
        echo "Usage: $(basename $0) [-hdcispr]" 2>&1
        echo '   -h   shows this message'
        echo '   -d   set html directory'
#	echo '   -c   set CGI directory'
        echo '   -i   set inkscape dir'
	echo '   -s   set mySQL username'
        echo '   -p   set mySQL password'
        echo '   -r   use defaults for raspberry pi using lighttp without mySQL'

}

# Define list of arguments expected in the input
optstring=":hdcispr"

while getopts ${optstring} arg; do
  case "${arg}" in
    h) usage ;;
    d) HTTPDIR="${OPTARG}";;
#    c) CGI="${OPTARG}" ;;
    i) INKSCAPE="${OPTARG}" ;;
    s) MUSER="${OPTARG}" ;;
    p) MPASS="${OPTARG}" ;;
    r) NOSQL='true' ;;

    ?)
      echo "Invalid option: -${OPTARG}."
      echo
      usage
      ;;
  esac
done

if [[ -v $MUSER ]] || [[ -v $MPASS ]]
then
	NOSQL='true'
else
	NOSQL=${NOSQL='false'}
fi

INKS=`which inkscape`

INKSCAPE=${INKSCAPE=$INKS}

#if [[ ! -d "$CGI" ]]
#then
#    mkdir $CGI
#    chmod 700 $CGI
#    echo -e "Options +ExecCGI \nAddHandler cgi-script .py" >> $HTTPDIR/.htaccess
#fi

#cp -r * $CGI
#cd $CGI
cp -r data/* $HTTPDIR

# set up fonts
if [[ ! -f $HOME/.fonts ]]
then
	mkdir $HOME/.fonts
fi

cd data
cp Bravura.ttf $HOME/.fonts


# Setup config.ini

CONFIG=$HTTPDIR/conductor/config.ini

echo "[automated]" >> $CONFIG
echo "dir = $HTTPDIR" >> $CONFIG
echo "data = $HTTPDIR" >> $CONFIG
echo "installation = $SCRIPT_DIR" >> $CONFIG
echo "inkscape = $INKSCAPE" >> $CONFIG

if [[ ! $NOSQL ]]
then
	echo "msql_user = $MUSER" >> $CONFIG
	echo "msql_pass = $MPASS" >> $CONFIG
fi



