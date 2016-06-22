#! /usr/bin/python

import os
import ConfigParser
import cgi, cgitb

      # Import modules for CGI handling

# Create instance of FieldStorage
form = cgi.FieldStorage()

new_config = ConfigParser.RawConfigParser()

config = ConfigParser.ConfigParser()
config.readfp(open('config.ini'))

sections = config.sections()
for header in sections:
    new_config.add_section(header)
    options = config.options(header)
    for option in options:
        if form.getvalue(option):
            new_config.set(header, option, form.getvalue(option))
        else :
            new_config.set(header, option, config.get(header, option))

with open('config.ini', 'wb') as configfile:
    new_config.write(configfile)

os.system("" + os.path.expanduser('~/Documents/Immrama/im_render.py') + " config.ini &")

print """
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <title>Playing</title>
  <META http-equiv="refresh" content="1;URL=../piece.html">
  </head>
  <body>
  <p>Redirecting to <a href="../piece.html">piece</a></p>
  </body>
  </html>
"""
