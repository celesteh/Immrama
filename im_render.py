#! /usr/bin/python

import svgwrite
import random
import cgi
import os
import xml.parsers.expat
import socket
import cgi
from ConfigParser import SafeConfigParser

# read config
config = SafeConfigParser()
config.read('config.ini')

width =  config.getint('working', 'width')
height = config.getint('working', 'height')
filename = config.get('working', 'file') # -> "value1"
data_dir = config.get('working', 'data') # -> "value1"
inkscape = config.get('working', 'inkscape')

# check config
if width is None:
    width = 800
if height is None:
    height = 600
if data_dir.endswith('/') == False:
    data_dir = data_dir +'/'


# when we want to read a file
def read_file(filename):
    text = ""
    
    #pick some text from this very file?
    with open(filename) as sourcefile:

        all_text = sourcefile.read()

        flag = True
        length = len(all_text)

        while flag:
            start = random.randint(0, length - 10)
            end = random.randint(start, length)
            flag = (((end - start) > 90) or ((end-start) < 10))

        text = all_text[start:end]
        text = cgi.escape(text).encode('ascii', 'xmlcharrefreplace')

        sourcefile.close()
    return text


def read_text():
    #pick some text from this very file?    
    texta = read_file('im_render.py')
    
    # and the config file, because why not?
    textb =  read_file('config.ini')

    if random.randrange(0,100) < 2:

        text = texta + '\n' + textb
    elif random.randrange(0,100) < 10:
        text = textb
    else:

        text = texta

    return text

# add anything unicode
def add_text(text, drawing, size, x,y, rotate):
    #print('add_text, {}, {}, {}, {}'.format(text, size, x, y))

    if width < height:
        axis=width
    else:
        axis = height

    if rotate:
        endx = abs(x+random.randint(-1 * axis, axis))
        endy = abs(y+random.randint(-1 * axis, axis))

        while (( abs(endx - x) + abs(endy -1))) < 250:
            endx = abs(x+random.randint(-1 * axis, axis))
            endy = abs(y+random.randint(-1 * axis, axis))


        path = drawing.path(d='M {} {} L {} {}'.format(x, y, endx, endy))
        drawing.add(path)
        tpath = drawing.textPath(path, text)
        text = ''

    g = drawing.g(style=("font-size:{}px,font-family:Bravura;stroke:black;stroke-width:1".format(size)))


    gtext = drawing.text(text, insert=(x,y), font_size = size, font_family='Bravura');

    #gtext.add(drawing.tspan(text))    

    if rotate:
        gtext.add(tpath)
    g.add(gtext)
    drawing.add(g)


# one of the items in the pallete
def make_dots(drawing, num, topx, topy, botx, boty, max_s, min_s):

    if botx < topx:
        x=topx
        topx = botx
        botx = x

    if boty < topy:
        y = topy
        topy = boty
        boty = y

    #print('range: {} {} {} {}'.format(topx,topy,botx,boty))

    for count in range(num):

        x = random.randint(topx, botx)
        y = random.randint(topy, boty)
        r = random.randint(max_s, min_s)
        drawing.add(drawing.circle(center=(x, y), r=r))

#end dots


# try and sort out the font
encoded_font = ""
with open(data_dir + "Bravura64.txt") as font:
    encoded_font=font.read() 

#dwg = svgwrite.Drawing(filename=filename, size = ("{}px".format(width), "{}px".format(height)))
dwg = svgwrite.Drawing(size = ("{}px".format(width), "{}px".format(height)))


dwg.defs.add(dwg.style('@font-face {\n  font-family: Bravura;\n' 
+ '  src: local("Bravura Regular"),\n    local("BravuraRegular"), \n    local("Bravura"),\n' 
#'    url(data:font/opentype;charset=utf-8;base64,{}==) format("embedded-opentype"),\n'.format(encoded_font.rstrip()) +
+ '     url(Bravura.otf) format("opentype"),\n    url(Bravura.svg) format("svg");\n}\n', type="text/css"))
dwg.add_stylesheet('{0}style.css', 'style', u'no', u'all'.format(data_dir))
#dwg.add(svgwrite.image.Image('{}Bravura.svg'.format(data_dir)))


# the pallete of music symbols
glyphs = ['&#x0e201;','&#x1d157;','&#x1d15d;','&#x0e0c1;','&#x0e0c2;','&#x0e155;','&#x0e159;',
'&#x0e15B;','&#x1d158;','&#x0e522;','&#x0e523;','&#x0e524;','&#x1D13D;','&#x0e009;','&#x1d1b1;',
'&#x0e5b6;','&#x1d1b2;','&#x0e5b7;','&#x1d116;','&#x0e020;','&#x1d117;','&#x0e021;','&#x1d118;',
'&#x0e022;','&#x1d119;','&#x0e023;','&#x1d11a;','&#x0e024;','&#x1d11e;','&#x0e080;','&#x0e081;',
'&#x1d122;','&#x0e083;','&#x0e084;','&#x0e500;','&#x1d110;','&#x0e500;','&#x0e12c;','&#x0e130;',
'&#x0e133;','&#x0e131;','&#x0e469;','&#x0e46a;','&#x0e46b;','&#x0e46e;','&#x0e132;','&#x1d1c7;',
'&#x0e135;','&#x0e134;','&#x1d1a9;','&#x1d1c8;','&#x0e142;','&#x0E61C;','&#x0e43e;','&#x0e43f;',
'&#x0e440;','&#x0e702;','&#x0e764;','&#x0E910;','&#x0E911;','&#x0E912;','&#x0E913;','&#x0E914;',
'&#x0E915;','&#x0E916;','&#x0E917;','&#x0E918;','&#x0E919;']

# this def from https://wiki.python.org/moin/EscapingXml
def unescape(s):
    want_unicode = False
    if isinstance(s, unicode):
        s = s.encode("utf-8")
        want_unicode = True
   
    # the rest of this assumes that `s` is UTF-8
    list = []
  
    # create and initialize a parser object
    p = xml.parsers.expat.ParserCreate("utf-8")
    p.buffer_text = True
    p.returns_unicode = want_unicode
    p.CharacterDataHandler = list.append
  
    # parse the data wrapped in a dummy element
    # (needed so the "document" is well-formed)
    p.Parse("<e>", 0)
    p.Parse(s, 0)
    p.Parse("</e>", 1)

    #print(list)
  
    # join the extracted strings and return
    es = ""
    if want_unicode:
        es = u""
    return es.join(list)


def rand_x():

    return random.randint(10,width-10)

def rand_y():

    return random.randint(10, height-10)


def dots():

    #dot bounds
    radius = random.randint(1, 8)
    topx = rand_x()
    topy = rand_y()
    botx = rand_x()
    boty = rand_y()

    #def make_dots(drawing, num, topx, topy, botx, boty, max_s, min_s):
    make_dots(dwg, random.randint(5, 30), topx, topy, botx, boty, radius, radius+random.randint(1,3))

def circle():

    width = random.randint(2,10)

    fill = random.choice(['white', 'white', 'white', 'white', 'black', 'black', 'yellow', 'red'])

    circle = dwg.circle(center=(rand_x(), rand_y()), r=random.randint(50,200), stroke_width =width, stroke='black')
    circle.fill(fill, opacity=0)
    dwg.add(circle)

def draw_line() :

    dwg.add(dwg.line(start=(rand_x(), rand_y()), end=(rand_x(), rand_y()),stroke='black', stroke_width=random.randint(2,20)))

def squiggle():

    opacity = 255
    if random.randrange(0,100) < 80:
        opacity = 0

    x_bias = random.randint(-2, 2)
    y_bias = random.randint(-2, 2)

    while x_bias == 0:
        x_bias = random.randint(-2, 2)

    while y_bias == 0:
        y_bias = random.randint(-2, 2)


    lowx = -2 + x_bias
    hix = 2 + x_bias
    lowy = -2 + y_bias
    hiy = 2 + y_bias

    x = rand_x()
    y = rand_y()
    width = random.randint(2,5)

    points = 'M {} {} L '.format(x,y)

    for count in range(random.randint(50,300)):
        newx = x + (random.randint(lowx, hix) * 3)
        newy = y + (random.randint(lowy, hiy) * 3)
        #dwg.add(dwg.line(start=(x, y), end=(newx,newy), stroke='black', stroke_width = width))
        points += ' {} {}'.format(newx, newy)
        x = newx
        y = newy

    path = dwg.path(d=points, stroke='black', stroke_width = width, fill='white')
    dwg.add(path)
#end squiggle

def glyph():
    #def add_text(text, drawing, size, x,y):
    #get the glyph's path from Bravura.svg in the data dir
    #path = dwg.path(d=pathdata, stroke='black', target=(rand_x(), rand_y()), rotation=(random.randrange(0,100) < 80), transform='scale({0})'.format(random.randrang(0.5, 50)))
    add_text(unescape(random.choice(glyphs)), dwg, random.randint(50,400), rand_x(), rand_y(), (random.randrange(0,100) < 80))

options = { 0: dots,
    1: circle,
    2: circle,
    3: glyph,
    4: glyph,
    5: glyph,
    6: draw_line,
    7: draw_line, 
    8: squiggle }


#link = dwg.add(dwg.a("http://yahoo.com"))
#square = link.add(dwg.rect((0, 0), (10, 10), fill='blue'))



#items = random.randint(4, 5)
items = random.randint(3, 6) #give a wider range

# is this a text image or no? 40% chance of yes? or 36, why not

if random.randrange(0,100) < 36:

    # put text into svg
    text = read_text()
    t_arr = text.split('\n')
    for line in t_arr:
        if len(line) > 0:
            rotate = (random.randrange(0, 100) < 30)
            add_text(line, dwg, random.randint(35, 70), random.randint(100, 600), random.randint(100, 300), rotate)

 
 


    #put text on path


    # text images have 1-2 items
    items = random.randrange(2,3)

#end the if that decides text stuff

tasks = range(len(options)) 
random.shuffle(tasks)
print(tasks)

tasks = tasks[0:items]
for index in tasks:

    #index = random.randint(0,6);
    options[index]()
 
#add_text('this is a test', dwg, 50, 100, 100, True)
#add_text('this is also a test', dwg, 50, 100, 100, False)

#squiggle()
#glyph()
#glyph()
#glyph()

#dwg.save()
xmlstr = """<?xml version="1.0" encoding="utf-8" ?>
<?xml-stylesheet href="style.css" type="text/css" title="style" alternate="no" media="all"?>
"""

xmlstr = xmlstr + dwg.tostring()

xmlstr = xmlstr.encode('ascii', 'xmlcharrefreplace') # re-escape the glyphs

with open(filename, 'w') as fp:
    #fp.write('<?xml version="1.0" encoding="utf-8" ?>\n')
    #fp.write('<?xml-stylesheet href="style.css" type="text/css" title="style" alternate="no" media="all"?>\n')
    fp.write(xmlstr)
    fp.close()

## skip for demo
#os.system('{0} -f {1} --verb EditSelectAll --verb SelectionUnGroup --verb EditSelectAll --verb ObjectToPath --verb FileSave --verb FileQuit'.format(inkscape, filename))

os.system('{0} {1} --export-text-to-path --export-plain-svg {1}'.format(inkscape, filename))
