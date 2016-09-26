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


parser = argparse.ArgumentParser(description='Change the wifi password.')
parser.add_argument('pass1', nargs='?', default='', help='Password')
parser.add_argument('pass2', nargs='?', default='', help='Confirm Password')
args = parser.parse_args()
pass1 = args.pass1
pass2 = args.pass2

if pass1 == pass2:
    # so far so good

    searching = False
    if pass1 != "": #Non blank password
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
                        line = "wpa_passphrase=" + pass1 +'\n'
                        searching = False
                    else :
                        #comment out the password line
                        line = "# " + line

                result.write(line)

            # end for
        #end with
        if searching:
            #we did not find a password line in the file
            result.write("wpa_passphrase=" + pass1 +'\n')
    #end with
    # copy file to real location
    #shutil.copy('/tmp/hostapd.conf', '/etc/hostapd/hostapd.conf')
    print "Success: Password Changed"
else :
    print "Failure: Passwords do not match"
