#!/bin/bash

# Author: Nexure Solutions 
# Usage: Custom IP address exporter for fail2ban.

MYSQL_CMD=$(/usr/share/nginx/nexurepanel/Automations/export-database-credentials.sh)

$MYSQL_CMD -se "SELECT ipAddress FROM nexure_networks;" \
    | awk '{print "deny " $1 ";"}' > /usr/share/nexure/ddosmit/nexure_ratelimit_iplist.conf

nginx -s reload
