# Immrama
A live notated graphic score

## Requirements
This requires the python library svgwrite, psutil and setproctitle

### Bravura
Bravura is included with this software. It is realised under the SIL lisence. http://www.smufl.org/fonts/

### Inkscape
Must be version 0.91 or higher

## Display
These scripts generate HTML, which you can view in a web browser while running it, or, to view on multiple devices, you will need a web server.

To output to your web directory, *copy* the contents of data to your webdir, then edit config.ini and change dir to be your web dir.

The web pages generated by this program are compatible with any browser that displays SVGs, which should be widely supported on almost all devices, including mobile phones, made within the last 5+ years.

## Setup
You will need to decide how long long you wan the overall duration to be, in seconds. Edit the config.ini file to put that number for dur.

Then you will need to decide how long you want each image of notation to display before going on to the next one. Put that duration in seconds for slide_dur

If you want a pause before starting, set init_sleep to any number aside from 0.  This initial pause will be in addition to the pause while the first notation image is generated


## Run From Command Line
From the command line, start the piece:
    `./immrama`
