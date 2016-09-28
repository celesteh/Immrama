#! /usr/bin/python

import argparse
import os
import setproctitle
import psutil
import re
import shutil
from ConfigParser import SafeConfigParser


# Make sure we're the only version of this running
PROCNAME = "wpa_password.py"

for proc in psutil.process_iter():
    # check whether the process name matches
    if proc.name() == PROCNAME:
        proc.kill()

setproctitle.setproctitle(PROCNAME)


class lineMangler:
    """Hold stuff for doing pattern matching"""

    def __init__(self, regex, newline):
        #if isinstance(regex, basestring):
        #    regex = re.compile(regex)
        self.regex = re.compile(regex)
        self.newline = newline
        self.found = False

    def findreplace(self, line, shouldReplace):
        if self.regex.match(line):
            if (shouldReplace and (not self.found)):
                line = self.newline
                self.found = True
            else:
                line = '# ' + line

        return line

    def missingLine(self):
        if not self.found:
            self.found = True
            return self.newline
        else:
            return ""

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

with open (pass_file) as f:
    passwd = f.readline()

passwd = passwd.rstrip()
length = len(passwd)



if ((length == 0) or ((length >=8) and (length <=63) )):
    # adequate length
    check = re.compile('\W+') #not letters and numbers
    match = check.match(passwd)
    if ((match is None) or (len(match) == 0) or (length == 0)):
        # so far so good

        havePass = False
        if passwd != "": #Non blank password
            havePass=True

        pass_obj = lineMangler('$wpa_passphrase\s*=\s*\w+',
        'wpa_passphrase='+passwd)
        wpa2_obj = lineMangler('$wpa\s*=\s*[0-9]', 'wpa=2')
        key_obj = lineMangler('$wpa_key_mgmt\s*=\s*\w+','wpa_key_mgmt=WPA-PSK')
        rsn_obj = lineMangler('$rsn_pairwise\s*=\s*\w+','rsn_pairwise=CCMP')

        with open('/tmp/hostapd.conf', 'w') as result: #file to write
            with open('/etc/hostapd/hostapd.conf') as conf: #original file
                for line in conf:
                    line = line.rstrip()

                    line = pass_obj.findreplace(line, havePass)
                    line = wpa2_obj.findreplace(line, havePass)
                    line = key_obj.findreplace(line, havePass)
                    line = rsn_obj.findreplace(line, havePass)
                    result.write(line+'\n')

                # end for
            #end with
            if havePass:
                #make sure we get our lines into the file
                result.write(pass_obj.missingLine() + '\n')
                result.write(wpa2_obj.missingLine() + '\n')
                result.write(key_obj.missingLine() + '\n')
                result.write(rsn_obj.missingLine() + '\n')
        #end with
        # copy file to real location
        #shutil.copy('/tmp/hostapd.conf', '/etc/hostapd/hostapd.conf')
        #os.system('/etc/init.d/hostapd restart')
        if havePass:
            print "Success: Password Changed"
        else:
            print "Success: Wifi is passwordless"
    else:
        print "Failure: Password not set"
else :
    print "Failure: Password not set"
