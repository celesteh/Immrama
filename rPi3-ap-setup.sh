#!/bin/bash

if [ "$EUID" -ne 0 ]
	then echo "Must be root"
	exit
fi

if [[ $# -lt 1 ]]; then
	then echo "You need to pass a password!"
	echo "Usage:"
	echo "sudo $0 yourChosenPassword [apName]"
	exit
fi

APPASS=$1
APSSID="immrama"

if [[ $# -eq 2 ]]; then
	$APSSID=$2
fi

apt-get remove --purge hostapd -y
apt-get install hostapd dnsmasq -y

cat >> /etc/dhcpcd.conf <<EOF
interface wlan0  
    static ip_address=172.24.1.1/24
EOF

sed -i '/^ *wpa-conf /etc/wpa_supplicant/wpa_supplicant.conf/s,^,#,' /etc/network/interfaces

mv /etc/dnsmasq.conf /etc/dnsmasq.conf.orig  
cat > /etc/dnsmasq.conf <<EOF
interface=wlan0      # Use interface wlan0  
bind-interfaces      # Bind to the interface to make sure we aren't sending things elsewhere  
server=8.8.8.8       # Forward DNS requests to Google DNS  
domain-needed        # Don't forward short names  
bogus-priv           # Never forward addresses in the non-routed address spaces.  
dhcp-range=10.0.0.100,10.0.0.200,4h # Assign IP addresses between 10.0.0.100 and 10.0.0.200 with a 4 hour lease time  
EOF

cat > /etc/hostapd/hostapd.conf <<EOF
interface=wlan0
hw_mode=g
channel=10
auth_algs=1
#wpa=2
#wpa_key_mgmt=WPA-PSK
#wpa_pairwise=CCMP
#rsn_pairwise=CCMP
#wpa_passphrase=$APPASS
ssid=$APSSID
EOF

sed -i 's,^[#]DAEMON_CONFG=.*$,DAEMON_CONF="/etc/hostapd/hostapd.conf"' /etc/default/hostapd

# enable ipv4 forward
sed -i 's,#net.ipv4.ip_forward=1,net.ipv4.ip_forward=1,' /etc/sysctl.conf
echo 1 > /proc/sys/net/ipv4/ip_forward  # enable immediately

iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE  
iptables -A FORWARD -i eth0 -o wlan0 -m state --state RELATED,ESTABLISHED -j ACCEPT  
iptables -A FORWARD -i wlan0 -o eth0 -j ACCEPT  

iptables-save > /etc/iptables.ipv4.nat
cat > /lib/dhcpcd/dhcpcd-hooks/70-ipv4-nat <<EOF
iptables-restore < /etc/iptables.ipv4.nat 
EOF

service hostapd start  
service dnsmasq start  

echo "All done!"
