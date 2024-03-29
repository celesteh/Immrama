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
optstring=":h:d:c:i:s:p:r"

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


echo $HTTPDIR

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


# Setup config.ini

CONFIG=data/conductor/config.ini

echo $CONFIG

FLAG='true'

while IFS= read -r line; do
  #printf '%s\n' "$line"
  if [[ $line =~ \[automated\] ]]
  then
    FLAG='false'
  fi

  if $FLAG
  then
    echo $line >> $CONFIG.new
  fi
done < $CONFIG


echo -e "\n[automated]" >> $CONFIG.new
echo "dir = $HTTPDIR" >> $CONFIG.new
echo "data = $HTTPDIR" >> $CONFIG.new
echo "installation = $SCRIPT_DIR" >> $CONFIG.new
echo "inkscape = $INKSCAPE" >> $CONFIG.new

if [[ ! $NOSQL ]]
then
	echo "msql_user = $MUSER" >> $CONFIG.new
	echo "msql_pass = $MPASS" >> $CONFIG.new
fi

mv --backup=numbered --verbose $CONFIG $CONFIG.bak
mv $CONFIG.new $CONFIG

#DESTINATION = ${HTTPDIR%/}/conductor/config.ini
cp $CONFIG ${HTTPDIR%/}/conductor/config.ini


# set up fonts
if [[ ! -d $HOME/.fonts ]]
then
	mkdir $HOME/.fonts
fi

cd data/fonts
cp Bravura.ttf $HOME/.fonts

