#! /usr/bin/python

import argparse
import os

parser = argparse.ArgumentParser(description='Change the wifi password.')
parser.add_argument('pass1', nargs='?', default='', help='Password')
parser.add_argument('pass2', nargs='?', default='', help='Confirm Password')
args = parser.parse_args()

if pass1 == pass2:
    # so far so good

    # read the file
    if pass1 != "":
        # if there is a password line, replace the first one with the new password
        print ""
    # comment out subsequent password lines
    # save
    print "Success: Password Changed"
else :
    print "Failure: Passwords do not match"
