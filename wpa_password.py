#! /usr/bin/python

import argparse
import os
import setproctitle
import psutil
import re
import shutil

# Make sure we're the only version of this running
PROCNAME = "wpa_password.py"

for proc in psutil.process_iter():
    # check whether the process name matches
    if proc.name() == PROCNAME:
        proc.kill()

setproctitle.setproctitle(PROCNAME)

#holy shit do not do this from a command line
#parser = argparse.ArgumentParser(description='Change the wifi password.')
#parser.add_argument('pass1', nargs='?', default='', help='Password')
#parser.add_argument('pass2', nargs='?', default='', help='Confirm Password')
#args = parser.parse_args()
#pass1 = args.pass1
#pass2 = args.pass2

# did we get a config from the CL?
parser = argparse.ArgumentParser(description='Change the wifi password.')
parser.add_argument('config', nargs='?', default='data/conductor/config.ini', help='Path to a config.ini file')
args = parser.parse_args()
filename = args.config

# read config
config = SafeConfigParser()
config.read(filename)

tmp_dir = config.get('working', 'tmp')
if tmp_dir is None:
    tmp_dir = '/tmp/'

if tmp_dir.endswith('/') == False:
    tmp_dir = tmp_dir +'/'

pass_file = tmp_dir + 'newpass.txt'

with open pass_file as f:
    passwd = f.readline()

passwd = passwd.rstrip()
length = len(passwd)

if (length == 0 || ((length >=8) && (length <=63) )):
    check = re.compile('\w+')
        if check.match(passwd):
            # so far so good

            searching = False
            if passwd != "": #Non blank password
                searching=True

            pass_line = re.compile('$wpa_passphrase=\w+')

            with open('/tmp/hostapd.conf', 'w') as result: #file to write
                with open('/etc/hostapd/hostapd.conf') as conf: #original file
                    for line in conf:
                        match = pass_line.match(line)
                        if match:
                            # found a password line
                            if searching:
                                #replace with our password
                                line = "wpa_passphrase=" + passwd +'\n'
                                searching = False
                            else :
                                #comment out the password line
                                line = "# " + line

                        result.write(line)

                    # end for
                #end with
                if searching:
                    #we did not find a password line in the file
                    result.write("wpa_passphrase=" + passwd +'\n')
            #end with
            # copy file to real location
            #shutil.copy('/tmp/hostapd.conf', '/etc/hostapd/hostapd.conf')
            print "Success: Password Changed"
else :
    print "Failure: Password not set"
