#! /usr/bin/python3

import os
import shutil
#from ConfigParser import SafeConfigParser
import configparser
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

print(filename)

# read config
config = configparser.SafeConfigParser()
#config = configparser.ConfigParser()
#config = SafeConfigParser()
config.read(filename)

# Check the installed config bits first
webdir = config.get('automated','dir')
if not webdir:
    webdir =  config.get('main', 'dir') # -> "value1"

dur = config.getint('main', 'dur')
slide_dur =  config.getint('main', 'slide_dur')
init_sleep = config.getint('main', 'init_sleep')

tmp_dir = config.get('working', 'tmp')
rendered = config.get('working', 'rendered')

data_dir = config.get('automated', 'data') # -> "value1"
if not data_dir:
    data_dir = config.get('working', 'data') # -> "value1"

install_dir = config.get('automated', 'installation')
if not install_dir:
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

if bgcolor is None:
    bgcolor = "#000000"
if fgcolor is None:
    fgcolor = "#FFFFFF"


# create color.css and geometry.css

with open (data_dir + 'color.css', 'w') as css:
    css.write(
    """
body {
    background-color: """ + bgcolor + """;
    color: """ + fgcolor + """;
}

svg {
    fill: """ + fgcolor + """;
    path: """ + fgcolor + """;
}
    """
    )


#with open (data_dir + 'geometry.css', 'w') as geometry:
#    geom = ""
#    if height > width:
#        geom = """
#@media all and (orientation:landscape) and (min-device-width:""" + str(width+1) + """px){
#    #score {
#        /*width: """ + str(int(ceil(float(width)/height * 100))) + """vh;*/
#        /*width: """ + str(width) + """px;*/
#        /*width: """ + str(int(ceil(float(width)/height * 100))) + """%;*/
#        float: center;
#        display: block;
#        margin-top: 0;
#        /* width: """ + str(width) + """px;
#        height: """ + str(height) + """px; */
#        /*height: 100%;*/
#        /*width: auto;*/
#        height: 1vmin;
#        width: """ + '{0:.2f}'.format(float(width)/height) + """vmin;
#    }
#}
#
#        """
#        #geom = geom.format(float(width)/height)
#    geometry.write(geom)



# get ready
shutil.copy(data_dir + 'start.svg', dest)
os.chmod(dest, 0o666)
shutil.copy(data_dir + 'style.css', tmp_dir)
shutil.copy(data_dir + 'color.css', tmp_dir)

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

    # gen_dur ate the notation
    before = time.time()
    os.system(install_dir + "/im_render.py " + filename)
    after = time.time()

    shutil.copy(tmp, dest)
    os.chmod(dest, 0o666)

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
os.chmod(dest, 0o666)
#reset
# give enough time for the finish
if (slide_dur < 12):
    slide_dur = 12

time.sleep(slide_dur)
shutil.copy(data_dir + 'start.svg', dest)
os.chmod(dest, 0o666)

time.sleep(slide_dur)
shutil.copy(data_dir + 'ready.svg', dest)
os.chmod(dest, 0o666)
