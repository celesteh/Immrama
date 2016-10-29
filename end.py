#! /usr/bin/python

import os
import shutil
from ConfigParser import SafeConfigParser
import time
import os
import setproctitle
import psutil
import argparse
from math import ceil

# Make sure we're the only version of this running
PROCNAME = "immrama"

for proc in psutil.process_iter():
    # check whether the process name matches
    if proc.name() == PROCNAME:
        proc.kill()

setproctitle.setproctitle(PROCNAME)


# did we get a config from the CL?
parser = argparse.ArgumentParser(description='Generate a graphic score.')
parser.add_argument('config', nargs='?', default='data/conductor/config.ini', help='Path to a config.ini file')
args = parser.parse_args()
filename = args.config

#print(filename)

# read config
config = SafeConfigParser()
config.read(filename)


webdir =  config.get('main', 'dir') # -> "value1"
dur = config.getint('main', 'dur')
slide_dur =  config.getint('main', 'slide_dur')
init_sleep = config.getint('main', 'init_sleep')

tmp_dir = config.get('working', 'tmp')
rendered = config.get('working', 'rendered')
data_dir = config.get('working', 'data') # -> "value1"
install_dir = config.get('working', 'installation')

# stuff for css
bgcolor = config.get('working', 'background')
fgcolor =config.get('working', 'foreground')
width =  config.getint('working', 'imagewidth')
height = config.getint('working', 'imageheight')


if tmp_dir is None:
    tmp_dir = '/tmp/'

if tmp_dir.endswith('/') == False:
    tmp_dir = tmp_dir +'/'

tmp = tmp_dir + rendered

# in case the config has gone wrong
if webdir is None:
    webdir = './'

if webdir.endswith('/') == False:
    webdir = webdir + '/'

dest = webdir+'score.svg'

if dur is None:
    dur = 300

if slide_dur is None:
    slide_dur is 25

if init_sleep is None:
    init_sleep = 5

if data_dir.endswith('/') == False:
    data_dir = data_dir +'/'


# finish
shutil.copy(data_dir + 'end.svg' , dest)
os.chmod(dest, 0666)
#reset
# give enough time for the finish
if (slide_dur < 12):
    slide_dur = 12

time.sleep(slide_dur)
shutil.copy(data_dir + 'start.svg', dest)
os.chmod(dest, 0666)

time.sleep(slide_dur)
shutil.copy(data_dir + 'ready.svg', dest)
os.chmod(dest, 0666)
