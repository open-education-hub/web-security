# Name

Web: Web basics and browser security model: Lame Login

## Description

Get the flag from [lame-login](http://141.85.224.157:8087/lamelogin).

Score: 50

## Vulnerability

In the source you can observe two hashes:
username=d033e22ae348aeb5660fc2140aec35850c4da997(SHA)=admin
password=62d5a7eab7c13e99e355dd05b0377a6d01a8fa99(SHA)=Password123$

Then you can use the hashes to login.

## Exploit

Script in `./sol/solution`.
