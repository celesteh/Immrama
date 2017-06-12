# Raspberry Pi 3 Setup

## Physical Setup

The Pi 3 sometimes has problems with overheating. I ran into this during the setup (compiling inkscape), but never during a performance, even with more than 20 performers on the network.

To minimise the chances of overheating, set your computer so that it does not boot the desktop. All of the commands below are via the command line and can be executed via an ssh connection. Also, get a heat sink and put it over the processor.

In steps that may cause the processor to overheat, I've used the nice command to run the process more slowly.

Finally, Pi 3s have a higher power draw than earlier models. Your old mobile phone charger may not be putting out enough power. If your computer shuts down unexpectedly, try running it with an official Pi 3 power adaptor to see if that helps.

## Setting up the SD card
* Get Raspbian onto an SD card, by downloading it or buying a pre-loaded card. https://www.raspberrypi.org/downloads/raspbian/
* Re-size the card and change the name of the computer by running `sudo raspi-config`
  * Expanding the file system is on the main menu
  * Renaming the computer is under advanced options
  * You won't be using a GUI, so change the boot options so you don't start a desktop
  * Reboot the computer when you exit the program
* Do a software update `sudo apt-get update && sudo apt-get upgrade -y`

## Making the captive portal
* Go to https://frillip.com/using-your-raspberry-pi-3-as-a-wifi-access-point-with-hostapd/ and follow all the instructions until you get to the IP tables commands.  Do not use the rules on that page, but use these instead:

`sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE`

`sudo iptables -t nat -I POSTROUTING -p tcp --dport 80 -j MASQUERADE`

`sudo iptables -A FORWARD -i eth0 -o wlan0 -m state --state RELATED,ESTABLISHED -j ACCEPT`

`sudo iptables -t nat -A PREROUTING -i wlan0 -m state --state NEW -p tcp --dport 80 -j DNAT --to-destination 172.24.1.1`

Then, save the rules as instructed to on that page and follow the remainder of the instructions on the page.

* Add the following to the bottom of `/etc/dnsmasq.conf `

`server=/local/172.24.1.1`

`address=/local/172.24.1.1`

`expand-hosts`

`domain=local`

`local=/local/`


`address=/immrama.local/172.24.1.1`


`address=/www.apple.com/104.71.189.174`

`address=/gsp1.apple.com/62.24.201.58`

`address=/www.appleiphonecell.com/23.4.220.116`

`address=/www.itools.info/23.4.220.116`

`address=/www.ibook.info/23.4.220.116`

`address=/www.airport.us/23.4.220.116`

`address=/www.thinkdifferent.us/23.4.220.116`

`address=/akamaitechnologies.com/72.246.81.207`

`address=/akamaiedge.net/104.67.49.251`


`address=/msftncsi.com/62.24.131.144`

`address=/msftncsi.com.edgesuite.net/62.24.131.144`


`address=/google.com/62.24.208.147`

`address=/clients3.google.com/216.58.198.238`



* Add the following to the bottom of `/etc/hosts`

`172.24.1.1      immrama immrama.local www.immrama.local`


`104.71.189.174  www.apple.com`

`62.24.201.58    gsp1.apple.com`

`23.4.220.116    www.appleiphonecell.com`

`23.4.220.116    www.itools.info`

`23.4.220.116    www.ibook.info`

`23.4.220.116    www.airport.us`

`23.4.220.116    www.thinkdifferent.us`


`62.24.131.144   www.msftncsi.com`

`62.24.131.144   www.msftncsi.com.edgesuite.net`


`62.24.208.147   www.google.com`

`216.58.198.238  clients3.google.com`


* When you're done, reboot.

## Installing the webserver

* `sudo apt-get install lighttpd php5-cgi -y`
* `sudo lighty-enable-mod fastcgi`
* `sudo lighty-enable-mod fastcgi-php`
* `sudo mkdir /var/www/cgi-bin`
* edit `/etc/lighttpd/lighttpd.conf`
  * change the server modules section to:
`  server.modules = (`
`        "mod_access",`
`        "mod_alias",`
`        "mod_compress",`
`        "mod_redirect",`
`        "mod_rewrite",`
`        "mod_fastcgi",`
`        "mod_cgi",`
`)`

  * Add the following lines to the bottom of the file

`server.error-handler-404   = "/index.html"`

`$HTTP["host"] == "(.*)$" {`
`  url.redirect = ( "^(.*)/" => "http://immrama.local/$1" )`
`}`

`$HTTP["url"] =~ "^/cgi-bin/" {`
`    cgi.assign = (".py" => "/usr/bin/python")`
`}`

## Installing inkscape

Try `sudo apt-get install inkscape -y` then `inkscape --version` If it comes back with a number _below_ 0.91, you will have to build it (see below)





## Immrama setup

* `sudo pip install svgwrite`
* `sudo apt-get install python-dev`
* `sudo pip install setproctitle`
* `sudo pip install psutil`
* `cd ~/Documents ; git clone https://github.com/celesteh/Immrama.git`
  * If you install this elsewhere, you will need to edit the `data/conductor/config.ini` file to have the full path to your chosen location. Do this before the next step.
* `sudo chmod 777 /var/www/html/`
* `sudo mkdir /usr/share/fonts/truetype/bravura`
* `cd Immrama ; sudo cp -r data/* /var/www/html/ ; cd data ; sudo cp Bravura.ttf /usr/share/fonts/truetype/bravura && sudo fc-cache -f -v`
* `sudo chmod 777 /var/www/html/conductor/`
* `sudo chmod 666 /var/www/html/conductor/config.ini`

### Building Inkscape
* First inkscape--version! If the number is at or ABOVE 0.91, stop. Otherwise, carry on.
* `sudo nano /etc/apt/sources.list`
  * Uncomment the line with deb-src in it
* `sudo apt-get update && sudo nice apt-get upgrade -y`
* `sudo apt-get build-dep inkscape -y`
* `sudo apt-get install build-essential dpkg-dev fakeroot cmake bzr dh-make -y`
* `sudo apt-get remove inkscape`
* `sudo apt-get autoremove`
* `cd ~/Documents/Imramma ; ./pi_build_inkscape.sh && cd ~Downloads/ && sudo dpkg -i inkscape*.deb `

I was unable to build inkscape on a Pi3, as it kept shutting down during the build, but a Pi 2 was able to do it.

## Allow the shutdown via the web interface

Users need to be able to turn the computer off without actually logging into it.
(Note: if you do not set a WPA password or conductor password, any audience member can also use
their smart phone to turn off your computer, possibly cutting your performance
short).

* Set a WPA password for your wifi network or a conductor password via the web interface. Write it down
(maybe on your computer)
* Use the web interface to start running Immrama
* Log into the computer via ssh or keyboard, mouse & monitor
* `ls -las /var/www/html/color.css` This will tell you what user the
webserver runs as. For me, it was `www-data`
* `sudo visudo`
* append to the file: `www-data immrama= NOPASSWD: /sbin/halt`
but use whatever name was in the 4th column of output from the ls -las

## Allow chaning the WPA password via the web interface
* Use the web interface to start running Immrama
* Log into the computer via ssh or keyboard, mouse & monitor
* `ls -las /var/www/html/color.css` This will tell you what user the
webserver runs as. For me, it was `www-data`
* `sudo visudo`
* append to the file:
`www-data immrama= NOPASSWD: /home/pi/Documents/Immrama/wpa_password.py`
but use whatever name was in the 4th column of output from the ls -las


## Allow changing the Conductor password via the web interface
* Use the web interface to start running Immrama
* Log into the computer via ssh or keyboard, mouse & monitor
* `ls -las /var/www/html/color.css` This will tell you what user the
webserver runs as. For me, it was `www-data`
* `sudo visudo`
* append to the file:
`www-data immrama= NOPASSWD: /home/pi/Documents/Immrama/conductor_password.py`
but use whatever name was in the 4th column of output from the ls -las
* Edit CheckPass so the paths in it point to where you have put the files
* `sudo cp CheckPass /etc/init.d/CheckPass`
* Make script executable `sudo chmod 755 /etc/init.d/CheckPass`
* Test the program `sudo /etc/init.d/CheckPass`
* To register your script to be run at start-up and shutdown, `sudo update-rc.d CheckPass defaults`
* If you ever want to remove the script from start-up, `sudo update-rc.d -f CheckPass remove`

## Troubleshooting

* The first thing to check is file permissions. Re-run all the chmod commands above. Also, there are circumatsance where score.svg make have the wrong permissions. Either delete the file entirely or `sudo chmod 666 /var/www/html/score.svg`

* If you forget the wifi password, use a computer to connect to the Pi via
an ethernet cable and use the web form to reset the password
