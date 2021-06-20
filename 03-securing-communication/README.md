# Securing Communication

## Introduction

## SSL, TLS, HTTPS

http connections are plain text

attackers controlling the middleboxes can intercept traffic (man-in-the-middle, MitM attack)

information on MitM: https://www.thesslstore.com/blog/man-in-the-middle-attack-2/, https://charlesreid1.com/wiki/Man_in_the_Middle, https://charlesreid1.com/wiki/Man_in_the_Middle/HTTPS

connections need to be security: confidentiality and integrity

confidentiality: encryption and identity

TLS: certificates (identity) + encryption (confidentiality)

certificates: identity + symmetric key generation for encryption

SSL1.0, SSL2.0, SSL3.0, TLS1.0, TLS1.1, TLS1.2, TLS1.3: https://maulwuff.de/research/ssl-debugging.html

## Tutorials

use wget + https (google.com)

save certificate

use `openssl s_client`

investigate certificate

self-signed certificates

use ssllabs

certificate internals
  * key exchange
  * public key algorithm
  * ciphers

issues: https://www.venafi.com/education-center/ssl/common-ssl-attacks

HTTP spoofing: client (referal), server-name close, https://doubleoctopus.com/security-wiki/threats-and-tools/https-spoofing/

client authentication

SSL stripping: https://charlesreid1.com/wiki/SSLStrip, https://www.venafi.com/blog/what-are-ssl-stripping-attacks

use SSLSniff: https://charlesreid1.com/wiki/SSLSniff, https://github.com/moxie0/sslsniff

redirection HTTP to HTTPS, issues

HSTS: https://https.cio.gov/hsts/, https://www.sentinelstand.com/article/http-strict-transport-security-hsts-canonical-www-redirects, https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security, https://security.stackexchange.com/questions/129273/whats-the-difference-between-using-hsts-and-doing-a-301-redirection

mitmproxy: https://mitmproxy.org/, https://tools.kali.org/sniffingspoofing/mitmproxy

certificate pinning

TLS / SSL attacks: https://tools.ietf.org/html/rfc7457, https://www.acunetix.com/blog/articles/tls-vulnerabilities-attacks-final-part/, https://www.cloudinsidr.com/content/known-attack-vectors-against-tls-implementation-vulnerabilities/
* Logjam: https://weakdh.org/logjam.html
* BACKRONYM: http://backronym.fail/
* DROWN: https://drownattack.com/

connection downgrade

protocol downgrade: https://www.venafi.com/blog/preventing-downgrade-attacks

thc-ssl-dos: https://tools.kali.org/stress-testing/thc-ssl-dos

sslsplit: https://tools.kali.org/information-gathering/sslsplit

mitmengine: https://portswigger.net/daily-swig/sysadmins-offered-new-tools-to-detect-https-interception, https://github.com/cloudflare/mitmengine

using stunnel, securing a plain-text connection: https://www.stunnel.org/

TODO: Create a CA self-signed certificate, create new certificate, sign it with the CA self-signed certificate. See https://blogg.bekk.no/how-to-sign-a-certificate-request-with-openssl-e046c933d3ae

Resources
* https://tlseminar.github.io/class1/

## Summary of Commands

```
razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl s_client -connect www.upb.ro:443 -servername www.upb.ro

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl s_client -connect swarm.cs.pub.ro:443 -servername swarm.cs.pub.ro

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl s_client -connect facebook.com:443 -servername facebook.com

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl x509 -in facebook.crt -noout -text

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl s_client -show-certs -connect facebook.com:443 -servername facebook.com

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl s_client -show_certs -connect facebook.com:443 -servername facebook.com

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl verify -CAfile intermediary.crt facebook.crt

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl verify -CApath /etc/ssl/certs/ intermediary.crt

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl verify -CAfile /etc/ssl/certs/DigiCert_High_Assurance_EV_Root_CA.pem intermediary.crt

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ wget --no-check-certificate security.cs.pub.ro/summer-school/

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ telnet security.cs.pub.ro 80
Trying 141.85.227.114...
Connected to koala.cs.pub.ro.
Escape character is '^]'.
GET / HTTP/1.0

HTTP/1.1 200 OK
[...]

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ telnet 141.85.227.114 80
Trying 141.85.227.114...
Connected to 141.85.227.114.
Escape character is '^]'.
GET / HTTP/1.1
Host: security.cs.pub.ro

[...]

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl x509 -in local/ssl.crt/awesome-ca.com.crt -noout -text

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl genrsa -out local/ssl.key/bucuresti.ro.key 4096

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl rsa -in local/ssl.key/bucuresti.ro.key -noout -text

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl req -new -key local/ssl.key/bucuresti.ro.key -out local/ssl.csr/bucuresti.ro.csr

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl req -noout -text -in local/ssl.csr/bucuresti.ro.csr

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ cat local/ca.conf 
[ ca ]
default_ca = ca_default
[ ca_default ]
dir = ./local/
certs = $dir
new_certs_dir = $dir/ssl.crt
database = $dir/ca.db.index
serial = $dir/ca.db.serial
RANDFILE = $dir/ca.db.rand
certificate = $dir/ssl.crt/awesome-ca.com.crt
private_key = $dir/ssl.key/awesome-ca.com.key
default_days = 365
default_crl_days = 30
default_md = sha256
preserve = no
policy = generic_policy
[ generic_policy ]
countryName = optional
stateOrProvinceName = optional
localityName = optional
organizationName = optional
organizationalUnitName = optional
commonName = optional
emailAddress = optional

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ echo "1234" > local/ca.db.serial
razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ touch local/ca.db.index

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl ca -config local/ca.conf -out local/ssl.crt/bucuresti.ro.crt -infiles local/ssl.csr/bucuresti.ro.csr
Using configuration from local/ca.conf
Check that the request matches the signature
Signature ok
The Subject's Distinguished Name is as follows
countryName           :PRINTABLE:'RO'
stateOrProvinceName   :ASN.1 12:'Bucharest'
organizationName      :ASN.1 12:'Internet Widgits Pty Ltd'
commonName            :ASN.1 12:'bucuresti.ro'
Certificate is to be certified until Jul 16 15:19:40 2021 GMT (365 days)
Sign the certificate? [y/n]:y


1 out of 1 certificate requests certified, commit? [y/n]y
Write out database with 1 new entries
Data Base Updated

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl x509 -noout -text -in local/ssl.crt/bucuresti.ro.crt

razvan@yggdrasil:~/.../admin/admin.git/certs-ssl$ openssl verify -CAfile local/ssl.crt/awesome-ca.com.crt local/ssl.crt/bucuresti.ro.crt 
local/ssl.crt/bucuresti.ro.crt: OK
```

## Challenges

### Investigate SSL/TLS-enabled Websites

Investigate the SSL/TLS configuration strength for different websites.
Use:
* [SSL Server Test from SSL Labs](https://www.ssllabs.com/ssltest/) in a web browser
* [testssl.sh](https://testssl.sh/) in the command line

Investigate the following websites:
* https://curs.upb.ro/
* https://ing.ro/
* https://senat.ro/
* https://republica.ro/
* https://www.emag.ro/

Look for the following:
* the overall grade
* reasons for not getting the maximum grade
* certificate expiration date
* certification authority (CA)
* SSL/TLS version supported

Fill the information above in a Google spreadsheet, a copy of [this one](https://docs.google.com/spreadsheets/d/1ufpcQcwSL3LEziqg5tjBK-e7B2xVq0N5xiRcq9yeRHY/edit?usp=sharing).

### Investigate SSL/TLS Certificates

Download and investigate locally remote certificates.
Use [openssl s_client](https://www.misterpki.com/openssl-s-client/) to download a certificate.
Use [openssl x509](https://serverfault.com/a/215617/410841) to investigate the downloaded certificate.

Investigate the following websites:
* https://koala.cs.pub.ro
* https://security.cs.pub.ro
* https://wiki.cs.pub.ro

These websites are colocated on the same IP address:
```
$ host security.cs.pub.ro
security.cs.pub.ro has address 141.85.227.114
security.cs.pub.ro mail is handled by 5 security.cs.pub.ro.

$ host koala.cs.pub.ro
koala.cs.pub.ro has address 141.85.227.114

$ host wiki.cs.pub.ro
wiki.cs.pub.ro is an alias for koala.cs.pub.ro.
koala.cs.pub.ro has address 141.85.227.114
```

So be sure to use SNI (*Server Name Indication*) support for the `openssl s_client` command to download the correct certificate.

### Inspect HTTPS Configuration (Let's Encrypt)

[Let's Encrypt](https://letsencrypt.org/)

### Create HTTPS Configuration (Let's Encrypt)

[Let's Encrypt](https://letsencrypt.org/)

### Inspect HTTPS Traffic

Capture and decrypt HTTPS traffic when you have the private key.

Use [tcpdump](https://www.tcpdump.org/) or [Wireshark](https://www.wireshark.org/).

### Self-Signed Certificates

Create a self-signed certificate with [openssl](https://www.openssl.org/).

### mitmproxy

Use [mitmproxy](https://mitmproxy.org/) to capture HTTPS traffic.

### HTTPS Attack

Attack SSLv1, SSLv2

[TLSSled](https://tools.kali.org/information-gathering/tlssled)

Use [sslstrip](https://tools.kali.org/information-gathering/sslstrip)

## Resources and Tools

* [testssl.sh](https://testssl.sh/)
* [SSL Server Test from SSL Labs](https://www.ssllabs.com/ssltest/)
* [Let's Encrypt](https://letsencrypt.org/)
* [tcpdump](https://www.tcpdump.org/)
* [Wireshark](https://www.wireshark.org/)
* [HSTS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security)
* [sslstrip](https://tools.kali.org/information-gathering/sslstrip)
* [openssl](https://www.openssl.org/)
* [mitmproxy](https://mitmproxy.org/)
* [TLSSled](https://tools.kali.org/information-gathering/tlssled)
* [sslstrip](https://tools.kali.org/information-gathering/sslstrip)
* [OpenSSL Essentials](https://www.digitalocean.com/community/tutorials/openssl-essentials-working-with-ssl-certificates-private-keys-and-csrs)
* [The Most Common OpenSSL Commands](https://www.sslshopper.com/article-most-common-openssl-commands.html)
* [OpenSSL Examples](https://geekflare.com/openssl-commands-certificates/)

## Further Reading

* https://geekflare.com/ssl-test-certificate/
* https://www.feistyduck.com/library/openssl-cookbook/
