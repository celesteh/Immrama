# Raspberry Pi 3 Setup

## Setting up the SD card
* Get Raspbian onto an SD card, by downloading it or buying a pre-loaded card. https://www.raspberrypi.org/downloads/raspbian/
* Re-size the card and change the name of the computer by running `sudo raspi-config`
  * Expanding the file system is on the main menu
  * Renaming the card is under advanced options
  * You won't be using a GUI, so change the boot options so you don't start a desktop
  * Reboot the computer when you exit the program
* Do a software update `sudo apt-get update && sudo apt-get upgrade -y`

## Making the captive portal
Go to https://frillip.com/using-your-raspberry-pi-3-as-a-wifi-access-point-with-hostapd/ and follow all the instructions until you get to the IP tables commands.  Do not use the rules on that page, but use these instead:

`sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -t nat -I POSTROUTING -p tcp --dport 80 -j MASQUERADE
sudo iptables -A FORWARD -i eth0 -o wlan0 -m state --state RELATED,ESTABLISHED -j ACCEPT
sudo iptables -t nat -A PREROUTING -i wlan0 -m state --state NEW -p tcp --dport 80 -j DNAT --to-destination 172.24.1.1`

Then, save the rules as instructed to on that page and follow the remainder of the instructions on the page. When you're done, reboot.

## Installing the webserver

* `sudo apt-get install lighttpd -y`


## Immrama requirements

* `sudo pip install svgwrite`
