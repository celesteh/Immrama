#! /usr/bin/python

import os
import shutil
from ConfigParser import SafeConfigParser
import time
import os


# read config
config = SafeConfigParser()
config.read('config.ini')

webdir =  config.get('main', 'dir') # -> "value1"
dur = config.getint('main', 'dur')
slide_dur =  config.getint('main', 'slide_dur')
init_sleep = config.getint('main', 'init_sleep')
tmp = config.get('working', 'file') # -> "value1"
data_dir = config.get('working', 'data') # -> "value1"



# in case the config has gone wrong
if webdir is None:
    webdir = './'

if webdir.endswith('/') == False:
    webdir = webdir + '/'

dest = webdir+'demo/score0.svg'

if dur is None:
    dur = 25

if slide_dur is None:
    slide_dur is 300

if init_sleep is None:
    init_sleep = 5

if tmp is None:
    tmp = '/tmp/imramma.svg'

if data_dir.endswith('/') == False:
    data_dir = data_dir +'/'


# get ready
shutil.copy(data_dir + 'start.svg', dest)


# ok keep geeting ready
total =0
loop = 1
first = 1
index = 1

# go

while loop > 0 :

    dest = webdir+'demo/score{0}.svg'.format(index)

    # gender ate the notation
    before = time.time()
    os.system("./im_render.py")
    after = time.time()
    shutil.copy(tmp, dest)


    index = index + 1

    # should we keep looping, or has it been long enough?
    loop = 14 - index
    
#end while

# finish
shutil.copy(data_dir + 'end.svg' , dest)

