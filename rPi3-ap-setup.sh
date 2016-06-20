
#!/bin/bash

if [ "$EUID" -ne 0 ]
	then echo "Must be root"
	exit
fi

if [[ $# -ne 1 ]];
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
apt-get install hostapd dnsmasq iptables-persistent conntrack nginx php5 php5-common php5-fpm hostapd-y

cat > /etc/dnsmasq.conf <<EOF
interface=wlan0
dhcp-range=10.0.0.2,10.0.0.5,255.255.255.0,12h
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

sed -i -- 's/exit 0/ /g' /etc/rc.local

cat >> /etc/rc.local <<EOF
ifconfig wlan0 down
ifconfig wlan0 10.0.0.1 netmask 255.255.255.0 up
iwconfig wlan0 power off
service dnsmasq restart
hostapd -B /etc/hostapd/hostapd.conf & > /dev/null 2>&1
exit 0
EOF

# From https://andrewwippler.com/2016/03/11/wifi-captive-portal/
# Flush all connections in the firewall
iptables -F
# Delete all chains in iptables
iptables -X
# wlan0 is our wireless card. Replace with your second NIC if doing it from a server.
# This will set up our structure
iptables -t mangle -N wlan0_Trusted
iptables -t mangle -N wlan0_Outgoing
iptables -t mangle -N wlan0_Incoming
iptables -t mangle -I PREROUTING 1 -i wlan0 -j wlan0_Outgoing
iptables -t mangle -I PREROUTING 1 -i wlan0 -j wlan0_Trusted
iptables -t mangle -I POSTROUTING 1 -o wlan0 -j wlan0_Incoming
iptables -t nat -N wlan0_Outgoing
iptables -t nat -N wlan0_Router
iptables -t nat -N wlan0_Internet
iptables -t nat -N wlan0_Global
iptables -t nat -N wlan0_Unknown
iptables -t nat -N wlan0_AuthServers
iptables -t nat -N wlan0_temp
iptables -t nat -A PREROUTING -i wlan0 -j wlan0_Outgoing
iptables -t nat -A wlan0_Outgoing -d 192.168.24.1 -j wlan0_Router
iptables -t nat -A wlan0_Router -j ACCEPT
iptables -t nat -A wlan0_Outgoing -j wlan0_Internet
iptables -t nat -A wlan0_Internet -m mark --mark 0x2 -j ACCEPT
iptables -t nat -A wlan0_Internet -j wlan0_Unknown
iptables -t nat -A wlan0_Unknown -j wlan0_AuthServers
iptables -t nat -A wlan0_Unknown -j wlan0_Global
iptables -t nat -A wlan0_Unknown -j wlan0_temp
# forward new requests to this destination
iptables -t nat -A wlan0_Unknown -p tcp --dport 80 -j DNAT --to-destination 192.168.24.1
iptables -t filter -N wlan0_Internet
iptables -t filter -N wlan0_AuthServers
iptables -t filter -N wlan0_Global
iptables -t filter -N wlan0_temp
iptables -t filter -N wlan0_Known
iptables -t filter -N wlan0_Unknown
iptables -t filter -I FORWARD -i wlan0 -j wlan0_Internet
iptables -t filter -A wlan0_Internet -m state --state INVALID -j DROP
iptables -t filter -A wlan0_Internet -o eth0 -p tcp --tcp-flags SYN,RST SYN -j TCPMSS --clamp-mss-to-pmtu
iptables -t filter -A wlan0_Internet -j wlan0_AuthServers
iptables -t filter -A wlan0_AuthServers -d 192.168.24.1 -j ACCEPT
iptables -t filter -A wlan0_Internet -j wlan0_Global
# allow access to my website :)
iptables -t filter -A wlan0_Global -d andrewwippler.com -j ACCEPT
#allow unrestricted access to packets marked with 0x2
iptables -t filter -A wlan0_Internet -m mark --mark 0x2 -j wlan0_Known
iptables -t filter -A wlan0_Known -d 0.0.0.0/0 -j ACCEPT
iptables -t filter -A wlan0_Internet -j wlan0_Unknown
# allow access to DNS and DHCP
# This helps power users who have set their own DNS servers
iptables -t filter -A wlan0_Unknown -d 0.0.0.0/0 -p udp --dport 53 -j ACCEPT
iptables -t filter -A wlan0_Unknown -d 0.0.0.0/0 -p tcp --dport 53 -j ACCEPT
iptables -t filter -A wlan0_Unknown -d 0.0.0.0/0 -p udp --dport 67 -j ACCEPT
iptables -t filter -A wlan0_Unknown -d 0.0.0.0/0 -p tcp --dport 67 -j ACCEPT
iptables -t filter -A wlan0_Unknown -j REJECT --reject-with icmp-port-unreachable
#allow forwarding of requests from anywhere to eth0/WAN
iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE

#save our iptables
iptables-save > /etc/iptables.ipv4.nat
cat > /lib/dhcpcd/dhcpcd-hooks/70-ipv4-nat <<EOF
iptables-restore < /etc/iptables.ipv4.nat
EOF

service hostapd start
service dnsmasq start

# From https://andrewwippler.com/2016/03/11/wifi-captive-portal/
# Make the HTML Document Root
mkdir /usr/share/nginx/html/portal
chown www-data /usr/share/nginx/html/portal
chmod 755 /usr/share/nginx/html/portal

# create the nginx hotspot.conf file
cat << EOF > /etc/nginx/sites-available/hotspot.conf
server {
    # Listening on IP Address.
    # This is the website iptables redirects to
    listen       80 default_server;
    root         /usr/share/nginx/html/portal;

    # For iOS
    if ($http_user_agent ~* (CaptiveNetworkSupport) ) {
        return 302 http://hotspot.localnet/hotspot.html;
    }

    # For others
    location / {
        return 302 http://hotspot.localnet/;
    }
 }

 upstream php {
    #this should match value of "listen" directive in php-fpm pool
		server unix:/tmp/php-fpm.sock;
		server 127.0.0.1:9000;
	}

server {
     listen       80;
     server_name  hotspot.localnet;
     root         /usr/share/nginx/html/portal;

     location / {
         try_files $uri $uri/ index.php;
     }

    # Pass all .php files onto a php-fpm/php-fcgi server.
    location ~ [^/]\.php(/|$) {
    	fastcgi_split_path_info ^(.+?\.php)(/.*)$;
    	if (!-f $document_root$fastcgi_script_name) {
    		return 404;
    	}
    	# This is a robust solution for path info security issue and works with "cgi.fix_pathinfo = 1" in /etc/php.ini (default)
    	include fastcgi_params;
    	fastcgi_index index.php;
    	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    	fastcgi_pass php;
    }
}

EOF

# Enable the website and reload nginx
ln -s /etc/nginx/sites-available/hotspot.conf /etc/nginx/sites-enabled/hotspot.conf
systemctl reload nginx

#copy over files
cp data/* /usr/share/nginx/html/portal/

# set up rmtrack
cat << EOF > /usr/bin/rmtrack
/usr/sbin/conntrack -L \
    |grep $1 \
    |grep ESTAB \
    |grep 'dport=80' \
    |awk \
        "{ system(\"conntrack -D --orig-src $1 --orig-dst \" \
            substr(\$6,5) \" -p tcp --orig-port-src \" substr(\$7,7) \" \
            --orig-port-dst 80\"); }"
EOF

chmod +x /usr/bin/rmtrack



pip install svgwrite

echo "We can do that by typing visudo and appending the below lines to the file.

www-data ALL = NOPASSWD: /usr/bin/rmtrack [0-9]*.[0-9]*.[0-9]*.[0-9]*
www-data ALL = NOPASSWD: /sbin/iptables -t mangle -A wlan0_Outgoing  -m mac --mac-source ??\:??\:??\:??\:??\:?? -j MARK --set-mark 2
"


echo "All done!"
