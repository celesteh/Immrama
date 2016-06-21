#! /usr/bin/python

import os
import shutil
from ConfigParser import SafeConfigParser
import time
import os
import setproctitle
import psutil

# Make sure we're the onle version of this running
PROCNAME = "immrama"

for proc in psutil.process_iter():
    # check whether the process name matches
    if proc.name() == PROCNAME:
        proc.kill()

setproctitle.setproctitle(PROCNAME)

# read config
config = SafeConfigParser()
config.read('data/conductor/config.ini')

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

dest = webdir+'score.svg'

if dur is None:
    dur = 300

if slide_dur is None:
    slide_dur is 25

if init_sleep is None:
    init_sleep = 5

if tmp is None:
    tmp = '/tmp/imramma.svg'

if data_dir.endswith('/') == False:
    data_dir = data_dir +'/'



# get ready
shutil.copy(data_dir + 'start.svg', dest)

# pause just for a moment
time.sleep(init_sleep)

# ok keep geeting ready
total =0
loop = 1
first = 1

start_time = time.time()
prev = start_time

# go

while loop > 0 :

    # gender ate the notation
    before = time.time()
    os.system("./im_render.py")
    after = time.time()

    shutil.copy(tmp, dest)


    # calculate the timings
    if first == 1:
        #dont' start counting until we've shown the first image
        start_time = after
        first = 0


    gen_dur = after - before

    # sleep for a bit
    if (time.time() - start_time) + slide_dur >= dur: # are we at the end?
        time.sleep(slide_dur)
    elif slide_dur > gen_dur:
        time.sleep(slide_dur - gen_dur)

    prev = after

    # should we keep looping, or has it been long enough?
    loop = dur - (time.time() - start_time)

#end while

# finish
shutil.copy(data_dir + 'end.svg' , dest)
#reset
time.sleep(slide_dur)
shutil.copy(data_dir + 'ready.svg', dest)
