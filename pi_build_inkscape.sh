#!/bin/bash

deg=0
export DH_VERBOSE=1

function pause_proc {
  #renice -n 15 $1
  kill -TSTP  $1
  for i in `ps -ef| awk '$3 == '$1' { print $2 }'`
  do
    #echo "pausing $i"
    pause_proc $i
  done
}

function resume_proc {
  kill -CONT $1
  for i in `ps -ef| awk '$3 == '$1' { print $2 }'`
  do
    #echo "restarting $i"
    resume_proc $i
  done
}

function get_temp {
  cpu=$(</sys/class/thermal/thermal_zone0/temp)
  deg=$((cpu/1000))
}

function run_proc {
  #ps $1
  stopped=0
  sleep=1

  get_temp
  echo "$deg c " `date +%X`

  while kill -0 "$1"; do #run this loop while the build is running

    #cpu=$(</sys/class/thermal/thermal_zone0/temp)
    #deg=$((cpu/1000))
    get_temp

    if [ $deg -ge 49 ] ; then
      if [ $stopped -eq 0 ]; then
        echo "too hot! $deg c " `date +%X`
      fi

      pause_proc $1
      stopped=1
      if [ $sleep -eq 5 ] ; then
        sleep=10
      elif [ $sleep -lt 5 ] ; then
        sleep=5
      fi
    fi
    if [ $stopped -eq 1 ] ;then
      #echo "paused"
      if [ $deg -le 47 ]
        then
        echo "cool enough $deg c " `date +%X`
        resume_proc $1
        stopped=0
        sleep=1
      fi
    fi

    #echo "$deg c stopped=$stopped" `date +%X`
    sleep $sleep

  done
}

dir=`pwd`
cd ~/Downloads

if [ ! -e ~/Downloads/0.91.x ]; then
  bzr branch lp:inkscape/0.91.x &
  run_proc $!
fi

if [ ! -e ~/Downloads/0.91.x/debian ]; then

  if [ ! -e ./inskcape_0.91.orig.tar.xz ]; then
    cd 0.91.x
    nice dh_make --createorig -s -p inskcape_0.91 -y &
    run_proc $!
    cd ..
  fi

  if [ ! -e inkscape-0.48.5 ]; then
    apt-get source inkscape &
    run_proc $!
  fi


  mv 0.91.x/debian/rules 0.91.x/debian/rules.orig
  cp inkscape-0.48.5/debian/rules 0.91.x/debian

  rm -r inkscape-0.48.*
#  cd ../../0.91.x
fi

cd ~/Downloads/0.91.x

nice -n 15 dpkg-buildpackage -uc -us -rfakeroot &
build=$!

run_proc $build


cd $dir


echo "built!"
