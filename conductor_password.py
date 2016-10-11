#! /usr/bin/python

import argparse
import os
import setproctitle
import psutil
import re
import shutil
from ConfigParser import SafeConfigParser
import subprocess


# Make sure we're the only version of this running
PROCNAME = "conductor_password.py"

for proc in psutil.process_iter():
    # check whether the process name matches
    if proc.name() == PROCNAME:
        proc.kill()

setproctitle.setproctitle(PROCNAME)

re.UNICODE

# did we get a config from the CL?
parser = argparse.ArgumentParser(description='Remove, change or add a conductor password.')
parser.add_argument('config', nargs='?', default='data/conductor/config.ini', help='Path to a config.ini file')
parser.add_argument('username', help='Username for conductor', nargs='?') #user
parser.add_argument('password', help='Password for conductor', nargs='?')#pass
parser.add_argument("--remove", help="Remove a conductor", action="store_true")
parser.add_argument("--clear", help="Remove password requirement", action="store_true")
parser.add_argument("--check", help="Remove password requirement if there are no passwords", action="store_true")

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

homedir = config.get('working', 'installation')
if homedir.endswith('/') == False:
    homedir = homedir +'/'

confdir = config.get('web', 'lighttpdconf')
if confdir.endswith('/') == False:
    confdir = confdir +'/'


passfile = config.get('web', 'htpasswd')

# possible ways of calling this

# just with config fie -> check if the passfile exists, if not: switch
# to no password config file, restart the web server

# just with user name, no password -> remove the user from the
# passfile, delete it if empty, do as above

# --clear -> delete the pass file, do as above

# user AND pass -> see if the user is in the passfile. if so:
# reaplce the old line for them with a new one; else: append to end


# needed functions: remove user from file
def remove_user(user):
    lines=0 # keep track of it new file is empty
    regex = re.compile('^{}\s*\:.*'.format(user))
    tmpfile= tmp_dir + 'htdigest.user'

    if os.path.isfile(passfile):
        with open(tmpfile, 'w') as result: #file to write
            with open(passfile) as pfile: #original file
                for line in pfile:
                    line = line.rstrip()
                    if not regex.match(line):
                        result.write(line+'\n')
                        lines=lines+1
                    #endif
                #endfor
            #endwith
        #endwith
        if lines > 0:
            shutil.copy(tmpfile, passfile)
        else:
            os.remove(passfile)
        #endif
    #endif
    return (lines > 0)
#end remove_user

def restart():
    subprocess.Popen(homedir + 'restart.sh')
#enddef

def enable_passwords():
    shutil.copy(homedir+'config/lighttpd.conf.with_auth', confdir + 'lighttpd.conf')
    restart()
#end enable_passwords

def disable_passwords():
    if os.path.isfile(passfile):
        os.remove(passfile)
    shutil.copy(homedir+'config/lighttpd.conf.no_auth', confdir + 'lighttpd.conf')
    print 'Disabling passwords'
    restart()
#end enable_passwords


def check_pass_file():
    regex = re.compile('^.*\:.*\:.*'.format(user))
    haslines = False
    if os.path.isfile(passfile):
        with open(passfile) as pfile: #original file
            for line in pfile:
                line = line.rstrip()
                if regex.match(line):
                    haslines=True
                #endif
            #endfor
        #endwith
    #endif
    if not haslines:
        disable_passwords()
#end check_pass_file



clear=False
remove=False
user=None
#check=args.check

if args.clear:
    print "clear"
    clear = True

if args.remove:
    print "remove"
    remove=True

if args.check:
    print "check"
    check=True

if args.username:
    print "user: " + args.username
    user = args.username.rstrip()
    length = len(user)

    if ((length >= 2) and (length <=20)): #arbitrary!
        check = re.compile('.*[^a-zA-Z0-9]+.*') #not letters and numbers
        match = check.match(user)
        #print match
        if (match is not None): #none is good
            user = None
            print "Failure: Username can be made up of letter and numbers only"
    else:
        print "Failure: Username must be 2-20 characters long"

    if user:
        print "user: " + user
    #else :
    #    print "Failure"

if args.password and (not (clear or remove or check)):
    print "password: " + args.password

    if user:
        passwd = args.password.rstrip()
        length = len(passwd)



        if ((length >=4) and (length <=63) ):
            # adequate length
            check = re.compile('.*[^a-zA-Z0-9]+.*') #not letters and numbers
            match = check.match(passwd)
            #print match
            if (match is None):
                # so far so good

                # if the user is already in there, get rid of them
                remove_user(user)

                # append the user
                with open(passfile, 'a') as pfile:
                    #hash=`echo -n "$user:$realm:$pass" | md5sum | cut -b -32`
                    tohash = '{}:conductor:{}'.format(user,password)
                    hashed= md5.new(tohash).hexdigest()
                    pfile.write(hashed+ '\n')

                enable_passwords()
                print "Success: Password Changed"
            else:
                print "Failure: Password not set. Use only letters and numbers."
        else :
            print "Failure: Password not set. Must be between 4-63 characters."
    else:
        print "bad user name"

elif remove:
    if user:
        remove_user(user)
        check_pass_file()
        print "Sucess: {} removed".format(user)
    else :
        print "Failure: You must specify a user to remove"

elif clear:
    #delete pass file
    # rewrite server conf
    disable_passwords()
    print "Sucess: the conductor functions are now passwordless"

else:
    # do check
    check_pass_file()
    print "Checking if users exist"
