#! /usr/bin/python

import os
import shutil
from ConfigParser import SafeConfigParser
import time
import os
import sys

# takes an argument for how many demos to make
if len(sys.argv) > 1:
    runs = int(sys.argv[1])
else:
    runs = 1

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



# set up directories, if they don't exist

if not os.path.exists(webdir):
    os.makedirs(webdir)

webdir = webdir+'demo/'

if not os.path.exists(webdir):
    os.makedirs(webdir)

#make the first html file
html = webdir+'index.html'

content= """
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Immrama</title>
</head>
<body>
<p><a href="0/index.html">Click here</a> to start. If multiple players, do NOT exactly synchronise your clicks</p>
</body>
</html>
"""

htmlfile = open(html, "w")
htmlfile.write(content)
htmlfile.close

for run in range(0, runs):

    demodir = '{0}/{1}/'.format(webdir, run)

    if not os.path.exists(demodir):
        os.makedirs(demodir)

    dest = demodir+'score0.svg'

    #make this run's first html file
    html = demodir+'index.html'

    content= """
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="refresh" content="1; url=index1.html">
<title>Immrama</title>
</head>
<body>
<img src="score0.svg" />
</body>
</html>
    """

    htmlfile = open(html, "w")
    htmlfile.write(content)
    htmlfile.close

    # get ready
    shutil.copy(data_dir + 'start.svg', dest)
    shutil.copy(data_dir + 'end.svg', demodir)


    # ok keep geeting ready
    total =0
    loop = 1
    first = 1
    index = 1

    # go

    while loop > 0 :


        # do the notation

        dest = '{0}/score{1}.svg'.format(demodir,index)

        # gender ate the notation
        before = time.time()
        os.system("./im_render.py")
        after = time.time()
        shutil.copy(tmp, dest)

        #do the html
        html = demodir+'index{0}.html'.format(index)
        
        content = """
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
        """

        content = content + '\n<meta http-equiv="refresh" content="30; url=index{0}.html">\n'.format(index +1)
        content = content + """
<title>Immrama</title>
</head>
<body>
        """
        content = content + '\n<img src="score{0}.svg" />\n'.format(index)
        content = content + '</body>\n</html>\n'

        htmlfile = open(html, "w")
        htmlfile.write(content)
        htmlfile.close


        index = index + 1

        # should we keep looping, or has it been long enough?
        loop = 11 - index
    
    #end while

    #finish the run
    html = demodir+'index11.html'
        
    content = """
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="refresh" content="20; url=index12.html">
<title>Immrama</title>
</head>
<body>
<img src="end.svg" />
</body>
</html>
    """
    htmlfile = open(html, "w")
    htmlfile.write(content)
    htmlfile.close

    # will there be more runs?
    if run == (runs - 1):
        #last one
        line = '<p><a href="../index.html">Go back to the start?</p>\n'
    else:
        line = '<p><a href="../{0}/index.html">Try again with new notation?</p>\n'.format(run +1)

    
    html = demodir+'index12.html'
        
    content = """
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Immrama</title>
</head>
<body>
<p>This was an example run-through and is not the piece. The piece is generated in real time and this is just a static example.</p>
    """
    content = content + line + '</body>\n</html>\n'

    htmlfile = open(html, "w")
    htmlfile.write(content)
    htmlfile.close

# finish for loop and we're done


