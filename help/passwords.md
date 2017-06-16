# How and when to set Passwords

## Introduction

There are two kinds of passwords on this score. One is the wifi password
And the other is the Conductor password.  Both of these are optional.

The Wifi password is the password needed to join the wifi network. All
performers must be able to join this network. If there is no password,
anyone at all can join.  If you forget this password, it is very easy
to reset it.

The conductor password is the password needed to make changes to the piece,
start and stop the score, shut down the computer, and change passwords. If there
is no password, anyone on the network can do any of these things at any time.
If you forget this password, it is a little more difficult to reset it.

Whichever combinations of password you choose, it's important to rehearse with
that combination.


## Wifi Password

In a typical performance situation, you would not want the audience on your
wifi network. This is especially true if the audience is large. If many people
are you your network, it can cause the piece to slow down or crash.

However, if you are running a workshop or in another situation where there is
no audience - only participants, it can save a lot of time to skip having a
password.

From the main page, anyone on the network can click on 'show wifi password' and
help other participants join the network.

### Setting

To set or unset the password, from the main page, click on conductor and then
click on Advanced settings. Scroll down to find the wifi password form.

Your password can only contain numbers and letters, which can be upper or
lowercase.  It must be between 8-63 characters long.

If you leave the new password form blank, it will clear the password, so one is
no longer required.

## Conductor Password

If you have an open network, or are working with children or strangers, you
should consider setting a conductor password. This way, only someone who types
in the password can start or stop the piece or change settings.

Only one person should act as a conductor for any given run-through of the piece,
however, it is a good idea to have the password written down or have a back-up
conductor who has their own password, in case the normal conductor is absent.

### Setting

To set or unset the password, from the main page, click on conductor and then
click on Advanced settings. Scroll down to find the conductor password form.

You must pick a user name. It can only be numbers and upper or lowercase letters.
It must be between 3-20 characters long.

Your password can only contain numbers and letters, which can be upper or
lowercase.  It must be between 3-20 characters long.

You can have as many conductor accounts as you like, but only one person
should be acting as a conductor at a time. Any conductor may change the
password of any other conductor. If you leave the password fields blank,
that conductor will be deleted.  If there are no remaining conductors,
all requirements to log in will be removed.

## Forgotten Passwords

To prevent forgotten passwords, it may be a good idea to write them down.
For the wifi password especially, it may be a good idea to write it directly
on the computer.

If you are planning on storing the piece for a while, it may be a good idea
to clear the passwords and reset them when the piece returns to your active
repertoire.

### Wifi

#### Web interface

To reset the wifi password, use an ethernet cable to plug the computer into
your home network or a laptop or desktop computer. Use a laptop or desktop
computer to connect to immrama.local or immrama.home or using whatever
extension is used on your home network.  If connecting via your router
does not work, try plugging in directly and vice versa.  Once you are
connected, you can view the existing wifi password, reset it or clear it
using the web interface.

#### SD Card

If you use your computer to mount the SD card, the wifi password can be read
at the bottom of /root?/etc/hostapd/hostapd.conf, in the line starting with
`wpa_passphrase=`

### Conductor

#### SD card

If you use your computer to mount the SC card, you will see a directory in the
`boot` partition of the card which is called `htpasswd/`. Inside, you will find
a file called `lighttpd-htdigest.user`. Delete
that file to clear the password.

#### SSH

Alternately, if you are on the same network as the piece, either on it's
own wifi network or connected via ethernet, you can connect via ssh:
`ssh pi@immrama.local` or `ssh@immrama` or `ssh@immrama.home` or
`pi@immrama.localnet` or `pi@172.24.1.1` or using
whatever extension is used on your local network.

The default password for an ssh connection is `graphicnotation`. If this
does not work, then [see here](http://raspberrypi.stackexchange.com/a/12676)
for what to try next.

Once you have logged in, type:
* `sudo rm /boot/htpasswd/lighttpd-htdigest.user`
* `sudo shutdown -h now`
Be careful to type this exactly.
