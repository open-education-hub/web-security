---
linkTitle: 03. Securing Communication
type: docs
weight: 10
---

# Securing Communication

## Introduction

As part of this session, we look into how HTTP connections can be made secure, to prevent attacks that capture traffic, also called [man-in-the-middle (MitM) attacks](https://www.thesslstore.com/blog/man-in-the-middle-attack-2/).
The session is focused on understanding certificates and HTTPS and investigating configurations of existing setups.

## Reminders and Prerequisites

HTTP (*Hypertext Transfer Protocol*) is the main protocol of the web.
It has several characteristics:

* it is plain text
* it doesn't maintain an active connection
* it's a request-response protocol
* it provides a series of codes to mark the types of requests and replies
* HTTP requests consists of paths (routes) that are mapped to resources

The lack of an active connection session is compensated by the use of cookies.
Similarly, the plain text nature of the protocol means that anyone could read contents in HTTP network packets.
This is alleviated by the use of HTTPS.
Nowadays, most connections use HTTPS precisely because of the need for confidentiality.

In the previous session, you used web browsers and the [Developer Tools](https://developer.mozilla.org/en-US/docs/Learn/Common_questions/What_are_browser_developer_tools) feature of modern browsers to inspect traffic, update cookies, inspect rendered pages.
GUI web browsers (such as Firefox, Chrome, Edge, Safari) provide the appealing interface for users to surf the web.
For quick and dirty tasks such as testing connections, making requests and downloading large files, we use CLI web clients such as `curl` and `wget`.
We will be using these in this session as well.

## Confidentiality

Confidentiality is a security property that prevents captured data from being understood by an attacker.
If an attacker captures data with confidentiality ensured, the attacker must not be able to extract actual information from it.
Confidentiality is generally provided with encryption.

For example, a classical HTTP connection is plain text and thus non-confidential.
Let's exemplify this.
On one terminal, start a `tcpdump` capture session for HTTP connections:

```
$ sudo tcpdump -A tcp port 80
```

On another terminal, make an HTTP connection using `curl`:

```
$ curl http://elf.cs.pub.ro
```

As the connection is HTTP, you will see plain text messages as part of the `tcpdump` output:

```
Host: elf.cs.pub.ro
User-Agent: curl/7.58.0
Accept: */*

[...]
Date: Sun, 03 Jul 2022 15:51:46 GMT
Server: Apache/2.4.38 (Debian)
Last-Modified: Mon, 02 Aug 2010 17:58:06 GMT
ETag: "a8-48cdaf14da780"
Accept-Ranges: bytes
Content-Length: 168
Vary: Accept-Encoding
Content-Type: text/html

<html>
	<head>
		<meta name="google-site-verification" content="gTsIxyV43HSJraRPl6X1A5jzGFgQ3N__hKAcuL2QsO8" />
	</head>

	<body>
		<h1>It works!</h1>
	</body>
</html>
```

However, if we used `curl` and an HTTPS connection:

```
$ curl https://elf.cs.pub.ro
```

there would be no plain text output on the `tcpdump` console, because the connection is using a confidential (encrypted) channel.

The same traffic inspection can be done in a more visual manner using Wireshark.

As long as traffic is not encrypted, an attacker able to capture packets (either fooling someone to get the data or simply accessing a networking equipment along the way) will read the traffic contents.
HTTPS uses public key cryptography to ensure the confidentiality of network traffic.

## Public Key Cryptography. Certificates

There are mainly two types of encryption: symmetric and public-key encryption.

In symmetric encryption, a key is shared among the two ends in the communication.

![symmetric encryption](assets/symmetric-encryption.svg)

That same key is used for both encrypting and decrypting data.
AES (*Advanced Encryption Standard*) is the standard symmetric encryption algorithm.
The main benefit of symmetric encryption algorithms is their relative simplicity and speed: they are easy to implement and are fast, with the option of having hardware support.
The downside is related to the shared key.
If the key is captured by an attacker or if it is lost by one of the ends, confidentiality is compromised.

So, the goal is that each connection should use a temporary shared key.
After the connection ends, the shared key is discarded.
A new connection will generate a new key.
Of course, that shared key must be known only by the two ends.
For this two happen, key exchange algorithms, such as [Diffie Hellman](https://en.wikipedia.org/wiki/Diffie%E2%80%93Hellman_key_exchange) are used.

Diffie-Hellman (often abbreviated as DH) is based on public-key encryption.
In short we use public-key encryption to set up a temporary shared key for the actual communication.

Public-key encryption, as its name implies, relies on a pair of private and public keys that are connected mathematically.
RSA (*Rivest Shamir Adleman* - named after its inventors) is the classical public key cryptographic algorithm.
The private key is generated as a random set of bytes and the public key is generated from it, via a mathematical algorithm.
The private key is only available to the owner, whereas the public key is available to everyone.
Anyone can encrypt a message using the public key, but only the owner can decrypt the message using the private key, as shown below.

![public-key encryption](assets/public-key-encryption.svg)

Because of this, public-key encryption is considered more secure than symmetric encryption, as it doesn't require the passing of a shared key between parties, an act that may be intercepted.
At the same time, public-key encryption is much slower than symmetric encryption.
Because of this, public-key encryption is only used to set up an initial session and enable a key exchange algorithm (such as Diffie-Hellman) to generate a temporary session-specific shared key.

### Identity Management. Certificates

A public-private key pair is not only used for encryption.
It's also used for identity management.

Identity management means making sure a given entity is who they claim they are.
In HTTPS that means that if we connect to `google.com` we are offered guarantees that the server we connect to is indeed `google.com`.
Otherwise, another server could impersonate the target server and capture all traffic.

Identity management relies on signing and verifying messages using public-private keys.
The private key is used to sign a message.
The signed message is provided publicly.
Then, the public key is used to verify the message.
This is called a **digital signature**, as shown below:

![digital signature](assets/digital-signature.svg)

In HTTPS, this means that the web server will sign the message with its private key and web clients will verify the message with the public key.

In order for this to work, the public key has to be attached the identity: the name of the server.
This is done via a **digital certificate**.
A certificate is a file that consists of a public key and an identity.
A certificate itself is also signed to ensure its validity.
This means that a certificate will also be verified using a public key, found as part of another certificate, as shown below:

![digital certificate](assets/digital-certificate.png)

This dependency between certificates creates a **public-key infrastructure** (PKI), on top of which self-signed root certificates are located.
Self-signed root certificates part of **Certification Authorities**.
These are entities that sign other certificates to validate the binding of a public key to an identity.

A browser stores root certificates as part of its default installation.
Each connection to the server will get the server to provide the certificate: identity and public key.
The browser uses the root certificate and intermediary certificates to verify the certificate.
After its verification the public key is used to create the actual HTTPS (secure) connection.

### Inspecting Certificates

To get a better understanding of how certificates work, let's take a look at one.

In order to get a certificate to inspect, it is easiest to export a root certificate from a browser.
In Firefox, we can use the Certificate Manager, as shown in the image below to export a certificate:

![Firefox Certificate Manager](assets/firefox-certificate-manager.png)

The CA (root) certificate from Verisign is located in `assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt`.
The certificate, as most certificates, is exported in PEM (*Privacy Enhanced Mail*) format, a Base64 encoding:

```
$ cat assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt
-----BEGIN CERTIFICATE-----
MIIEGjCCAwICEQCLW3VWhFSFCwDPrzhIzrGkMA0GCSqGSIb3DQEBBQUAMIHKMQsw
CQYDVQQGEwJVUzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZl
cmlTaWduIFRydXN0IE5ldHdvcmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWdu
LCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxRTBDBgNVBAMTPFZlcmlT
aWduIENsYXNzIDEgUHVibGljIFByaW1hcnkgQ2VydGlmaWNhdGlvbiBBdXRob3Jp
dHkgLSBHMzAeFw05OTEwMDEwMDAwMDBaFw0zNjA3MTYyMzU5NTlaMIHKMQswCQYD
VQQGEwJVUzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlT
aWduIFRydXN0IE5ldHdvcmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWduLCBJ
bmMuIC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxRTBDBgNVBAMTPFZlcmlTaWdu
IENsYXNzIDEgUHVibGljIFByaW1hcnkgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkg
LSBHMzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAN2E1Lm0+afY8wR4
nN493GwTFtl63SRRZsDHJlkNrAYIwpTRMx/wgzUfbhvI3qpuFU5UJ+/EbRrsC+MO
8ESlV8dAWB6jRx9x7GD2bZTIGDnt/kIYVt/kTEkQeE4BdjVjEjbdZrwBBDajVWjV
ojYJrKshJlQGrT/KFOCsyq0GHZXi+J3x4GD/wn91K0zM2v6HmSHquv4+VNfSWXjb
PG7PoBMAGrgnoeS+Z5bKoMWznN3JdZ7rMJpfo83ZrngZPyPpXNspva1VyBtUjGP2
6KbqxzcSXKMpHgLZ2x87tNcPVkeBFQRKr4Mn0cVYiMHd9qqnoxjaaKptEVHhv2Vr
n5Z20T0CAwEAATANBgkqhkiG9w0BAQUFAAOCAQEAq2aN17O6x5q25lXQBfGfMY1a
qtmqRiYPce2lrVNWYgFHKkTp/j90CxObufRNG7LRX7K20ohcs5/Ny9Sn2WCVhDr4
wTcdYcrnsMXlkdpUpqwxga6X3s0IrLjAl4B/bnKk52kTlWUfxJM8/XmPBNQ+T+r3
ns7NZ3xPZQL/kYVUc8f/NveGLezQXk//EZ9yBta4GvFMDSZl4kSAHsef493oCtrs
pSCAaWihT37ha88HQfqDjrw43bAuEbFrskLMmrz5SCJ5ShkPshw+IHTZasO+8ih4
E1Z5T21Q6huwtVexN2ZYI/PcD98Kh8TvhgXVOBRgmaNL3gaWcSzy27YfpO8/7g==
-----END CERTIFICATE-----
```

In its basic format, the certificate is a binary file.
The PEM format is used to make it printable.
The PEM format is also used for storing private and public SSH keys, so it may seem familiar.

We can inspect the certificate with `openssl`:

```
$ openssl x509 -noout -text -in assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt
Certificate:
    Data:
        Version: 1 (0x0)
        Serial Number:
            8b:5b:75:56:84:54:85:0b:00:cf:af:38:48:ce:b1:a4
        Signature Algorithm: sha1WithRSAEncryption
        Issuer: C = US, O = "VeriSign, Inc.", OU = VeriSign Trust Network, OU = "(c) 1999 VeriSign, Inc. - For authorized use only", CN = VeriSign Class 1 Public Primary Certification Authority - G3
        Validity
            Not Before: Oct  1 00:00:00 1999 GMT
            Not After : Jul 16 23:59:59 2036 GMT
        Subject: C = US, O = "VeriSign, Inc.", OU = VeriSign Trust Network, OU = "(c) 1999 VeriSign, Inc. - For authorized use only", CN = VeriSign Class 1 Public Primary Certification Authority - G3
        Subject Public Key Info:
            Public Key Algorithm: rsaEncryption
                RSA Public-Key: (2048 bit)
                Modulus:
                    00:dd:84:d4:b9:b4:f9:a7:d8:f3:04:78:9c:de:3d:
                    dc:6c:13:16:d9:7a:dd:24:51:66:c0:c7:26:59:0d:
                    ac:06:08:c2:94:d1:33:1f:f0:83:35:1f:6e:1b:c8:
                    de:aa:6e:15:4e:54:27:ef:c4:6d:1a:ec:0b:e3:0e:
                    f0:44:a5:57:c7:40:58:1e:a3:47:1f:71:ec:60:f6:
                    6d:94:c8:18:39:ed:fe:42:18:56:df:e4:4c:49:10:
                    78:4e:01:76:35:63:12:36:dd:66:bc:01:04:36:a3:
                    55:68:d5:a2:36:09:ac:ab:21:26:54:06:ad:3f:ca:
                    14:e0:ac:ca:ad:06:1d:95:e2:f8:9d:f1:e0:60:ff:
                    c2:7f:75:2b:4c:cc:da:fe:87:99:21:ea:ba:fe:3e:
                    54:d7:d2:59:78:db:3c:6e:cf:a0:13:00:1a:b8:27:
                    a1:e4:be:67:96:ca:a0:c5:b3:9c:dd:c9:75:9e:eb:
                    30:9a:5f:a3:cd:d9:ae:78:19:3f:23:e9:5c:db:29:
                    bd:ad:55:c8:1b:54:8c:63:f6:e8:a6:ea:c7:37:12:
                    5c:a3:29:1e:02:d9:db:1f:3b:b4:d7:0f:56:47:81:
                    15:04:4a:af:83:27:d1:c5:58:88:c1:dd:f6:aa:a7:
                    a3:18:da:68:aa:6d:11:51:e1:bf:65:6b:9f:96:76:
                    d1:3d
                Exponent: 65537 (0x10001)
    Signature Algorithm: sha1WithRSAEncryption
         ab:66:8d:d7:b3:ba:c7:9a:b6:e6:55:d0:05:f1:9f:31:8d:5a:
         aa:d9:aa:46:26:0f:71:ed:a5:ad:53:56:62:01:47:2a:44:e9:
         fe:3f:74:0b:13:9b:b9:f4:4d:1b:b2:d1:5f:b2:b6:d2:88:5c:
         b3:9f:cd:cb:d4:a7:d9:60:95:84:3a:f8:c1:37:1d:61:ca:e7:
         b0:c5:e5:91:da:54:a6:ac:31:81:ae:97:de:cd:08:ac:b8:c0:
         97:80:7f:6e:72:a4:e7:69:13:95:65:1f:c4:93:3c:fd:79:8f:
         04:d4:3e:4f:ea:f7:9e:ce:cd:67:7c:4f:65:02:ff:91:85:54:
         73:c7:ff:36:f7:86:2d:ec:d0:5e:4f:ff:11:9f:72:06:d6:b8:
         1a:f1:4c:0d:26:65:e2:44:80:1e:c7:9f:e3:dd:e8:0a:da:ec:
         a5:20:80:69:68:a1:4f:7e:e1:6b:cf:07:41:fa:83:8e:bc:38:
         dd:b0:2e:11:b1:6b:b2:42:cc:9a:bc:f9:48:22:79:4a:19:0f:
         b2:1c:3e:20:74:d9:6a:c3:be:f2:28:78:13:56:79:4f:6d:50:
         ea:1b:b0:b5:57:b1:37:66:58:23:f3:dc:0f:df:0a:87:c4:ef:
         86:05:d5:38:14:60:99:a3:4b:de:06:96:71:2c:f2:db:b6:1f:
         a4:ef:3f:ee
```

The options passed to the `openssl` command are:

* `x509`: work with X.509 certificates - a standard for certificates
* `-noout`: do not print the PEM output of the certificate
* `-text`: print as text the contents of the certificate
* `-in`: the input certificate file

As shown in the output, a certificate comprises of data and the signature of that data.
The data is primarily composed of the:

* identity (the `Subject` attribute): `C = US, O = "VeriSign, Inc.", OU = VeriSign Trust Network, OU = "(c) 1999 VeriSign, Inc. - For authorized use only", CN = VeriSign Class 1 Public Primary Certification Authority - G3`
* the public key (comprised of a `Modulus` and `Exponent`)

There are two other important items:

* the issuer, i.e. the entity that signed the certificate;
  in this case, it's a self signed certificate, so the `Issuer` is the same as the `Subject`
* the validity of the certificate, in this case it's `July 16, 2036`

Generally, a certificate is only valid for one year and then it will have to be renewed.
Renewing means a new public key is generated and, together with the same identity information, a new certificate.

The `openssl` utility has command-line options to only print parts of the certificate.
For example, to only print the issuer or the public key, we would use the `-issuer` or `-pubkey` options:

```
$ openssl x509 -noout -issuer -in assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt
issuer=C = US, O = "VeriSign, Inc.", OU = VeriSign Trust Network, OU = "(c) 1999 VeriSign, Inc. - For authorized use only", CN = VeriSign Class 1 Public Primary Certification Authority - G3

$ openssl x509 -noout -pubkey -in assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3YTUubT5p9jzBHic3j3c
bBMW2XrdJFFmwMcmWQ2sBgjClNEzH/CDNR9uG8jeqm4VTlQn78RtGuwL4w7wRKVX
x0BYHqNHH3HsYPZtlMgYOe3+QhhW3+RMSRB4TgF2NWMSNt1mvAEENqNVaNWiNgms
qyEmVAatP8oU4KzKrQYdleL4nfHgYP/Cf3UrTMza/oeZIeq6/j5U19JZeNs8bs+g
EwAauCeh5L5nlsqgxbOc3cl1nuswml+jzdmueBk/I+lc2ym9rVXIG1SMY/bopurH
NxJcoykeAtnbHzu01w9WR4EVBEqvgyfRxViIwd32qqejGNpoqm0RUeG/ZWuflnbR
PQIDAQAB
-----END PUBLIC KEY-----
```

### Verifying Certificates

As the VeriSign certificate is self-signed, we can use it to verify itself:

```
$ openssl verify -CAfile assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt
assets/VerisignClass1PublicPrimaryCertificationAuthority-G3.crt: OK
```

The `-CAfile` option points to the CA certificate to verify the current one.
As this is is a self signed certificate, we use itself as CA.

## HTTPS, SSL and TLS

HTTPS is the secure version of HTTP providing identity management, confidentiality and integrity.
HTTPS relies on at least the HTTP server providing a certificate that validates the identity.
The client can also do that, as part of [client certificate authentication](https://comodosslstore.com/blog/what-is-ssl-tls-client-authentication-how-does-it-work.html).

After validating the identity of the server, the client-server pair create a secure channel by agreeing on a per-session shared symmetric encryption key.
This is negotiated via a key-exchange algorithm such as Diffie-Hellman.
Then, all traffic between client and server will be encrypted.

This entire process is facilitated by the use of SSL (*Secure Sockets Layer*) or TLS (*Transport Layer Security*).
We say that HTTPS is HTTP plus SSL / TLS support.
Note that this is the case with other secure protocol variants such as SMTPS, IMAPS, LDAPS.

SSL / TLS usually refer to the same thing.
TLS is a newer version of the protocol.
Version are SSL1.0, SSL2.0, SSL3.0, TLS1.0, TLS1.1, TLS1.2, TLS1.3.
Each newer version comes with fixes and extra security guarantees.
Nowadays (2022) all SSL versions and TLS1.0 are considered insecure.
This is due both to internal design issues and to weak cryptographic algorithms or the allowing cryptographic keys of insufficient size.

A summary of attacks on SSL / TLS is summarized as part of [RFC 7457](https://tools.ietf.org/html/rfc7457).
Additional information can be found [here](https://www.acunetix.com/blog/articles/tls-vulnerabilities-attacks-final-part/) and [here](https://www.cloudinsidr.com/content/known-attack-vectors-against-tls-implementation-vulnerabilities/).
Well known SSL / TLS attacks are:

* [Logjam](https://weakdh.org/logjam.html)
* [BACKRONYM](http://backronym.fail/)
* [DROWN](https://drownattack.com/)

A typical target of the attacker is downgrading the connection from HTTPS to HTTP.
Or downgrading the SSL / TLS protocol version to a less secure variant.
Read more on protocol downgrade [here](https://www.venafi.com/blog/preventing-downgrade-attacks).

### Capturing, Inspecting and Verifying HTTPS Certificates

If you want to extract and inspect the certificate of an HTTPS server we would use the command below:

```
$ openssl s_client -showcerts -connect www.google.com:443 -servername www.google.com < /dev/null
CONNECTED(00000005)
depth=2 C = US, O = Google Trust Services LLC, CN = GTS Root R1
verify return:1
depth=1 C = US, O = Google Trust Services LLC, CN = GTS CA 1C3
verify return:1
depth=0 CN = www.google.com
verify return:1
---
Certificate chain
 0 s:CN = www.google.com
   i:C = US, O = Google Trust Services LLC, CN = GTS CA 1C3
-----BEGIN CERTIFICATE-----
MIIEiTCCA3GgAwIBAgIRAJ8HSxF0Xxb8EiN1+lh5k/AwDQYJKoZIhvcNAQELBQAw
RjELMAkGA1UEBhMCVVMxIjAgBgNVBAoTGUdvb2dsZSBUcnVzdCBTZXJ2aWNlcyBM
TEMxEzARBgNVBAMTCkdUUyBDQSAxQzMwHhcNMjIwNjA2MDk0MDAwWhcNMjIwODI5
MDkzOTU5WjAZMRcwFQYDVQQDEw53d3cuZ29vZ2xlLmNvbTBZMBMGByqGSM49AgEG
CCqGSM49AwEHA0IABD8O7cXWSPQhh/GihqJi+gdtpS0vAt2GeDRHBaVeB8x5dDtx
3us2TGW2WJGfC7VeSVHCX1uDXkjAIOTauMUjCu2jggJoMIICZDAOBgNVHQ8BAf8E
BAMCB4AwEwYDVR0lBAwwCgYIKwYBBQUHAwEwDAYDVR0TAQH/BAIwADAdBgNVHQ4E
FgQUWVwHKuk+m9ZD0/h/+Jsgactucp8wHwYDVR0jBBgwFoAUinR/r4XN7pXNPZzQ
4kYU83E1HScwagYIKwYBBQUHAQEEXjBcMCcGCCsGAQUFBzABhhtodHRwOi8vb2Nz
cC5wa2kuZ29vZy9ndHMxYzMwMQYIKwYBBQUHMAKGJWh0dHA6Ly9wa2kuZ29vZy9y
ZXBvL2NlcnRzL2d0czFjMy5kZXIwGQYDVR0RBBIwEIIOd3d3Lmdvb2dsZS5jb20w
IQYDVR0gBBowGDAIBgZngQwBAgEwDAYKKwYBBAHWeQIFAzA8BgNVHR8ENTAzMDGg
L6AthitodHRwOi8vY3Jscy5wa2kuZ29vZy9ndHMxYzMvUU92SjBOMXNUMkEuY3Js
MIIBBQYKKwYBBAHWeQIEAgSB9gSB8wDxAHYAUaOw9f0BeZxWbbg3eI8MpHrMGyfL
956IQpoN/tSLBeUAAAGBOJmjFwAABAMARzBFAiEA7Pub0IWm5kMWJrfJGLqP4lZU
71J6No/RLMwsvXWzVfACICJMzt/AFBsNQ1t970tVRnhmgsgz2s6deykihInBRfZR
AHcARqVV63X6kSAwtaKJafTzfREsQXS+/Um4havy/HD+bUcAAAGBOJmjPwAABAMA
SDBGAiEA92vym4NTX/SmjhAx7ICLE4KXpQFsWfhvRf1m5B6qby8CIQCVyyWh2t22
UhaaKSS+nIypJ9jWtOO4wG1gVkty8c/XETANBgkqhkiG9w0BAQsFAAOCAQEAI8fX
MKLNXXoMJk6WTJvV1ORE6kYVtyZm0wM64yV9V1zmksWDgOx9xHmoAUTQYeSq6rhI
tTxgb9EmDF8gVrOXwY31WpWjJyJQAfQcn3LhPUzJnr8yqyiwfVD1FG5gKQTTlblr
g9sZ+zfETFPXTFJeGT5yBxcT8xQDQNERblVkaQ1H5f2XYuXAJJ4vlNCu7AFil1tp
U4bau/EfQPx5Jd1bLxJwbeF9FbuQvcMeow+4ElcpC5BSkzsRk7lUbZfj7NWZas5t
3yp0UncNl+Pib3p0ooLDJ3HQvlQuL4AAg2nYkL+UKusZ9d/22RmbiyGkqr+3L/3+
PKvAVy9/DNPwW3YUbQ==
-----END CERTIFICATE-----
 1 s:C = US, O = Google Trust Services LLC, CN = GTS CA 1C3
   i:C = US, O = Google Trust Services LLC, CN = GTS Root R1
-----BEGIN CERTIFICATE-----
MIIFljCCA36gAwIBAgINAgO8U1lrNMcY9QFQZjANBgkqhkiG9w0BAQsFADBHMQsw
CQYDVQQGEwJVUzEiMCAGA1UEChMZR29vZ2xlIFRydXN0IFNlcnZpY2VzIExMQzEU
MBIGA1UEAxMLR1RTIFJvb3QgUjEwHhcNMjAwODEzMDAwMDQyWhcNMjcwOTMwMDAw
MDQyWjBGMQswCQYDVQQGEwJVUzEiMCAGA1UEChMZR29vZ2xlIFRydXN0IFNlcnZp
Y2VzIExMQzETMBEGA1UEAxMKR1RTIENBIDFDMzCCASIwDQYJKoZIhvcNAQEBBQAD
ggEPADCCAQoCggEBAPWI3+dijB43+DdCkH9sh9D7ZYIl/ejLa6T/belaI+KZ9hzp
kgOZE3wJCor6QtZeViSqejOEH9Hpabu5dOxXTGZok3c3VVP+ORBNtzS7XyV3NzsX
lOo85Z3VvMO0Q+sup0fvsEQRY9i0QYXdQTBIkxu/t/bgRQIh4JZCF8/ZK2VWNAcm
BA2o/X3KLu/qSHw3TT8An4Pf73WELnlXXPxXbhqW//yMmqaZviXZf5YsBvcRKgKA
gOtjGDxQSYflispfGStZloEAoPtR28p3CwvJlk/vcEnHXG0g/Zm0tOLKLnf9LdwL
tmsTDIwZKxeWmLnwi/agJ7u2441Rj72ux5uxiZ0CAwEAAaOCAYAwggF8MA4GA1Ud
DwEB/wQEAwIBhjAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwEgYDVR0T
AQH/BAgwBgEB/wIBADAdBgNVHQ4EFgQUinR/r4XN7pXNPZzQ4kYU83E1HScwHwYD
VR0jBBgwFoAU5K8rJnEaK0gnhS9SZizv8IkTcT4waAYIKwYBBQUHAQEEXDBaMCYG
CCsGAQUFBzABhhpodHRwOi8vb2NzcC5wa2kuZ29vZy9ndHNyMTAwBggrBgEFBQcw
AoYkaHR0cDovL3BraS5nb29nL3JlcG8vY2VydHMvZ3RzcjEuZGVyMDQGA1UdHwQt
MCswKaAnoCWGI2h0dHA6Ly9jcmwucGtpLmdvb2cvZ3RzcjEvZ3RzcjEuY3JsMFcG
A1UdIARQME4wOAYKKwYBBAHWeQIFAzAqMCgGCCsGAQUFBwIBFhxodHRwczovL3Br
aS5nb29nL3JlcG9zaXRvcnkvMAgGBmeBDAECATAIBgZngQwBAgIwDQYJKoZIhvcN
AQELBQADggIBAIl9rCBcDDy+mqhXlRu0rvqrpXJxtDaV/d9AEQNMwkYUuxQkq/BQ
cSLbrcRuf8/xam/IgxvYzolfh2yHuKkMo5uhYpSTld9brmYZCwKWnvy15xBpPnrL
RklfRuFBsdeYTWU0AIAaP0+fbH9JAIFTQaSSIYKCGvGjRFsqUBITTcFTNvNCCK9U
+o53UxtkOCcXCb1YyRt8OS1b887U7ZfbFAO/CVMkH8IMBHmYJvJh8VNS/UKMG2Yr
PxWhu//2m+OBmgEGcYk1KCTd4b3rGS3hSMs9WYNRtHTGnXzGsYZbr8w0xNPM1IER
lQCh9BIiAfq0g3GvjLeMcySsN1PCAJA/Ef5c7TaUEDu9Ka7ixzpiO2xj2YC/WXGs
Yye5TBeg2vZzFb8q3o/zpWwygTMD0IZRcZk0upONXbVRWPeyk+gB9lm+cZv9TSjO
z23HFtz30dZGm6fKa+l3D/2gthsjgx0QGtkJAITgRNOidSOzNIb2ILCkXhAd4FJG
AJ2xDx8hcFH1mt0G/FX0Kw4zd8NLQsLxdxP8c4CU6x+7Nz/OAipmsHMdMqUybDKw
juDEI/9bfU1lcKwrmz3O2+BtjjKAvpafkmO8l7tdufThcV4q5O8DIrGKZTqPwJNl
1IXNDw9bg1kWRxYtnCQ6yICmJhSFm/Y3m6xv+cXDBlHz4n/FsRC6UfTd
-----END CERTIFICATE-----
 2 s:C = US, O = Google Trust Services LLC, CN = GTS Root R1
   i:C = BE, O = GlobalSign nv-sa, OU = Root CA, CN = GlobalSign Root CA
-----BEGIN CERTIFICATE-----
MIIFYjCCBEqgAwIBAgIQd70NbNs2+RrqIQ/E8FjTDTANBgkqhkiG9w0BAQsFADBX
MQswCQYDVQQGEwJCRTEZMBcGA1UEChMQR2xvYmFsU2lnbiBudi1zYTEQMA4GA1UE
CxMHUm9vdCBDQTEbMBkGA1UEAxMSR2xvYmFsU2lnbiBSb290IENBMB4XDTIwMDYx
OTAwMDA0MloXDTI4MDEyODAwMDA0MlowRzELMAkGA1UEBhMCVVMxIjAgBgNVBAoT
GUdvb2dsZSBUcnVzdCBTZXJ2aWNlcyBMTEMxFDASBgNVBAMTC0dUUyBSb290IFIx
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAthECix7joXebO9y/lD63
ladAPKH9gvl9MgaCcfb2jH/76Nu8ai6Xl6OMS/kr9rH5zoQdsfnFl97vufKj6bwS
iV6nqlKr+CMny6SxnGPb15l+8Ape62im9MZaRw1NEDPjTrETo8gYbEvs/AmQ351k
KSUjB6G00j0uYODP0gmHu81I8E3CwnqIiru6z1kZ1q+PsAewnjHxgsHA3y6mbWwZ
DrXYfiYaRQM9sHmklCitD38m5agI/pboPGiUU+6DOogrFZYJsuB6jC511pzrp1Zk
j5ZPaK49l8KEj8C8QMALXL32h7M1bKwYUH+E4EzNktMg6TO8UpmvMrUpsyUqtEj5
cuHKZPfmghCN6J3Cioj6OGaK/GP5Afl4/Xtcd/p2h/rs37EOeZVXtL0m79YB0esW
CruOC7XFxYpVq9Os6pFLKcwZpDIlTirxZUTQAs6qzkm06p98g7BAe+dDq6dso499
iYH6TKX/1Y7DzkvgtdizjkXPdsDtQCv9Uw+wp9U7DbGKogPeMa3Md+pvez7W35Ei
Eua++tgy/BBjFFFy3l3WFpO9KWgz7zpm7AeKJt8T11dleCfeXkkUAKIAf5qoIbap
sZWwpbkNFhHax2xIPEDgfg1azVY80ZcFuctL7TlLnMQ/0lUTbiSw1nH69MG6zO0b
9f6BQdgAmD06yK56mDcYBZUCAwEAAaOCATgwggE0MA4GA1UdDwEB/wQEAwIBhjAP
BgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBTkrysmcRorSCeFL1JmLO/wiRNxPjAf
BgNVHSMEGDAWgBRge2YaRQ2XyolQL30EzTSo//z9SzBgBggrBgEFBQcBAQRUMFIw
JQYIKwYBBQUHMAGGGWh0dHA6Ly9vY3NwLnBraS5nb29nL2dzcjEwKQYIKwYBBQUH
MAKGHWh0dHA6Ly9wa2kuZ29vZy9nc3IxL2dzcjEuY3J0MDIGA1UdHwQrMCkwJ6Al
oCOGIWh0dHA6Ly9jcmwucGtpLmdvb2cvZ3NyMS9nc3IxLmNybDA7BgNVHSAENDAy
MAgGBmeBDAECATAIBgZngQwBAgIwDQYLKwYBBAHWeQIFAwIwDQYLKwYBBAHWeQIF
AwMwDQYJKoZIhvcNAQELBQADggEBADSkHrEoo9C0dhemMXoh6dFSPsjbdBZBiLg9
NR3t5P+T4Vxfq7vqfM/b5A3Ri1fyJm9bvhdGaJQ3b2t6yMAYN/olUazsaL+yyEn9
WprKASOshIArAoyZl+tJaox118fessmXn1hIVw41oeQa1v1vg4Fv74zPl6/AhSrw
9U5pCZEt4Wi4wStz6dTZ/CLANx8LZh1J7QJVj2fhMtfTJr9w4z30Z209fOU0iOMy
+qduBmpvvYuR7hZL6Dupszfnw0Skfths18dG9ZKb59UhvmaSGZRVbNQpsg3BZlvi
d0lIKO2d1xozclOzgjXPYovJJIultzkMu34qQb9Sz/yilrbCgj8=
-----END CERTIFICATE-----
---
Server certificate
subject=CN = www.google.com

issuer=C = US, O = Google Trust Services LLC, CN = GTS CA 1C3

---
No client certificate CA names sent
Peer signing digest: SHA256
Peer signature type: ECDSA
Server Temp Key: X25519, 253 bits
---
SSL handshake has read 4295 bytes and written 396 bytes
Verification: OK
---
New, TLSv1.3, Cipher is TLS_AES_256_GCM_SHA384
Server public key is 256 bit
Secure Renegotiation IS NOT supported
Compression: NONE
Expansion: NONE
No ALPN negotiated
Early data was not sent
Verify return code: 0 (ok)
---
DONE
```

The command is pretty verbose.
We can make it print just the certificates by using:

```
$ openssl s_client -showcerts -connect www.google.com:443 -servername www.google.com < /dev/null 2> /dev/null | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p'
-----BEGIN CERTIFICATE-----
MIIEiTCCA3GgAwIBAgIRAJ8HSxF0Xxb8EiN1+lh5k/AwDQYJKoZIhvcNAQELBQAw
RjELMAkGA1UEBhMCVVMxIjAgBgNVBAoTGUdvb2dsZSBUcnVzdCBTZXJ2aWNlcyBM
TEMxEzARBgNVBAMTCkdUUyBDQSAxQzMwHhcNMjIwNjA2MDk0MDAwWhcNMjIwODI5
MDkzOTU5WjAZMRcwFQYDVQQDEw53d3cuZ29vZ2xlLmNvbTBZMBMGByqGSM49AgEG
CCqGSM49AwEHA0IABD8O7cXWSPQhh/GihqJi+gdtpS0vAt2GeDRHBaVeB8x5dDtx
3us2TGW2WJGfC7VeSVHCX1uDXkjAIOTauMUjCu2jggJoMIICZDAOBgNVHQ8BAf8E
BAMCB4AwEwYDVR0lBAwwCgYIKwYBBQUHAwEwDAYDVR0TAQH/BAIwADAdBgNVHQ4E
FgQUWVwHKuk+m9ZD0/h/+Jsgactucp8wHwYDVR0jBBgwFoAUinR/r4XN7pXNPZzQ
4kYU83E1HScwagYIKwYBBQUHAQEEXjBcMCcGCCsGAQUFBzABhhtodHRwOi8vb2Nz
cC5wa2kuZ29vZy9ndHMxYzMwMQYIKwYBBQUHMAKGJWh0dHA6Ly9wa2kuZ29vZy9y
ZXBvL2NlcnRzL2d0czFjMy5kZXIwGQYDVR0RBBIwEIIOd3d3Lmdvb2dsZS5jb20w
IQYDVR0gBBowGDAIBgZngQwBAgEwDAYKKwYBBAHWeQIFAzA8BgNVHR8ENTAzMDGg
L6AthitodHRwOi8vY3Jscy5wa2kuZ29vZy9ndHMxYzMvUU92SjBOMXNUMkEuY3Js
MIIBBQYKKwYBBAHWeQIEAgSB9gSB8wDxAHYAUaOw9f0BeZxWbbg3eI8MpHrMGyfL
956IQpoN/tSLBeUAAAGBOJmjFwAABAMARzBFAiEA7Pub0IWm5kMWJrfJGLqP4lZU
71J6No/RLMwsvXWzVfACICJMzt/AFBsNQ1t970tVRnhmgsgz2s6deykihInBRfZR
AHcARqVV63X6kSAwtaKJafTzfREsQXS+/Um4havy/HD+bUcAAAGBOJmjPwAABAMA
SDBGAiEA92vym4NTX/SmjhAx7ICLE4KXpQFsWfhvRf1m5B6qby8CIQCVyyWh2t22
UhaaKSS+nIypJ9jWtOO4wG1gVkty8c/XETANBgkqhkiG9w0BAQsFAAOCAQEAI8fX
MKLNXXoMJk6WTJvV1ORE6kYVtyZm0wM64yV9V1zmksWDgOx9xHmoAUTQYeSq6rhI
tTxgb9EmDF8gVrOXwY31WpWjJyJQAfQcn3LhPUzJnr8yqyiwfVD1FG5gKQTTlblr
g9sZ+zfETFPXTFJeGT5yBxcT8xQDQNERblVkaQ1H5f2XYuXAJJ4vlNCu7AFil1tp
U4bau/EfQPx5Jd1bLxJwbeF9FbuQvcMeow+4ElcpC5BSkzsRk7lUbZfj7NWZas5t
3yp0UncNl+Pib3p0ooLDJ3HQvlQuL4AAg2nYkL+UKusZ9d/22RmbiyGkqr+3L/3+
PKvAVy9/DNPwW3YUbQ==
-----END CERTIFICATE-----
-----BEGIN CERTIFICATE-----
MIIFljCCA36gAwIBAgINAgO8U1lrNMcY9QFQZjANBgkqhkiG9w0BAQsFADBHMQsw
CQYDVQQGEwJVUzEiMCAGA1UEChMZR29vZ2xlIFRydXN0IFNlcnZpY2VzIExMQzEU
MBIGA1UEAxMLR1RTIFJvb3QgUjEwHhcNMjAwODEzMDAwMDQyWhcNMjcwOTMwMDAw
MDQyWjBGMQswCQYDVQQGEwJVUzEiMCAGA1UEChMZR29vZ2xlIFRydXN0IFNlcnZp
Y2VzIExMQzETMBEGA1UEAxMKR1RTIENBIDFDMzCCASIwDQYJKoZIhvcNAQEBBQAD
ggEPADCCAQoCggEBAPWI3+dijB43+DdCkH9sh9D7ZYIl/ejLa6T/belaI+KZ9hzp
kgOZE3wJCor6QtZeViSqejOEH9Hpabu5dOxXTGZok3c3VVP+ORBNtzS7XyV3NzsX
lOo85Z3VvMO0Q+sup0fvsEQRY9i0QYXdQTBIkxu/t/bgRQIh4JZCF8/ZK2VWNAcm
BA2o/X3KLu/qSHw3TT8An4Pf73WELnlXXPxXbhqW//yMmqaZviXZf5YsBvcRKgKA
gOtjGDxQSYflispfGStZloEAoPtR28p3CwvJlk/vcEnHXG0g/Zm0tOLKLnf9LdwL
tmsTDIwZKxeWmLnwi/agJ7u2441Rj72ux5uxiZ0CAwEAAaOCAYAwggF8MA4GA1Ud
DwEB/wQEAwIBhjAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwEgYDVR0T
AQH/BAgwBgEB/wIBADAdBgNVHQ4EFgQUinR/r4XN7pXNPZzQ4kYU83E1HScwHwYD
VR0jBBgwFoAU5K8rJnEaK0gnhS9SZizv8IkTcT4waAYIKwYBBQUHAQEEXDBaMCYG
CCsGAQUFBzABhhpodHRwOi8vb2NzcC5wa2kuZ29vZy9ndHNyMTAwBggrBgEFBQcw
AoYkaHR0cDovL3BraS5nb29nL3JlcG8vY2VydHMvZ3RzcjEuZGVyMDQGA1UdHwQt
MCswKaAnoCWGI2h0dHA6Ly9jcmwucGtpLmdvb2cvZ3RzcjEvZ3RzcjEuY3JsMFcG
A1UdIARQME4wOAYKKwYBBAHWeQIFAzAqMCgGCCsGAQUFBwIBFhxodHRwczovL3Br
aS5nb29nL3JlcG9zaXRvcnkvMAgGBmeBDAECATAIBgZngQwBAgIwDQYJKoZIhvcN
AQELBQADggIBAIl9rCBcDDy+mqhXlRu0rvqrpXJxtDaV/d9AEQNMwkYUuxQkq/BQ
cSLbrcRuf8/xam/IgxvYzolfh2yHuKkMo5uhYpSTld9brmYZCwKWnvy15xBpPnrL
RklfRuFBsdeYTWU0AIAaP0+fbH9JAIFTQaSSIYKCGvGjRFsqUBITTcFTNvNCCK9U
+o53UxtkOCcXCb1YyRt8OS1b887U7ZfbFAO/CVMkH8IMBHmYJvJh8VNS/UKMG2Yr
PxWhu//2m+OBmgEGcYk1KCTd4b3rGS3hSMs9WYNRtHTGnXzGsYZbr8w0xNPM1IER
lQCh9BIiAfq0g3GvjLeMcySsN1PCAJA/Ef5c7TaUEDu9Ka7ixzpiO2xj2YC/WXGs
Yye5TBeg2vZzFb8q3o/zpWwygTMD0IZRcZk0upONXbVRWPeyk+gB9lm+cZv9TSjO
z23HFtz30dZGm6fKa+l3D/2gthsjgx0QGtkJAITgRNOidSOzNIb2ILCkXhAd4FJG
AJ2xDx8hcFH1mt0G/FX0Kw4zd8NLQsLxdxP8c4CU6x+7Nz/OAipmsHMdMqUybDKw
juDEI/9bfU1lcKwrmz3O2+BtjjKAvpafkmO8l7tdufThcV4q5O8DIrGKZTqPwJNl
1IXNDw9bg1kWRxYtnCQ6yICmJhSFm/Y3m6xv+cXDBlHz4n/FsRC6UfTd
-----END CERTIFICATE-----
-----BEGIN CERTIFICATE-----
MIIFYjCCBEqgAwIBAgIQd70NbNs2+RrqIQ/E8FjTDTANBgkqhkiG9w0BAQsFADBX
MQswCQYDVQQGEwJCRTEZMBcGA1UEChMQR2xvYmFsU2lnbiBudi1zYTEQMA4GA1UE
CxMHUm9vdCBDQTEbMBkGA1UEAxMSR2xvYmFsU2lnbiBSb290IENBMB4XDTIwMDYx
OTAwMDA0MloXDTI4MDEyODAwMDA0MlowRzELMAkGA1UEBhMCVVMxIjAgBgNVBAoT
GUdvb2dsZSBUcnVzdCBTZXJ2aWNlcyBMTEMxFDASBgNVBAMTC0dUUyBSb290IFIx
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAthECix7joXebO9y/lD63
ladAPKH9gvl9MgaCcfb2jH/76Nu8ai6Xl6OMS/kr9rH5zoQdsfnFl97vufKj6bwS
iV6nqlKr+CMny6SxnGPb15l+8Ape62im9MZaRw1NEDPjTrETo8gYbEvs/AmQ351k
KSUjB6G00j0uYODP0gmHu81I8E3CwnqIiru6z1kZ1q+PsAewnjHxgsHA3y6mbWwZ
DrXYfiYaRQM9sHmklCitD38m5agI/pboPGiUU+6DOogrFZYJsuB6jC511pzrp1Zk
j5ZPaK49l8KEj8C8QMALXL32h7M1bKwYUH+E4EzNktMg6TO8UpmvMrUpsyUqtEj5
cuHKZPfmghCN6J3Cioj6OGaK/GP5Afl4/Xtcd/p2h/rs37EOeZVXtL0m79YB0esW
CruOC7XFxYpVq9Os6pFLKcwZpDIlTirxZUTQAs6qzkm06p98g7BAe+dDq6dso499
iYH6TKX/1Y7DzkvgtdizjkXPdsDtQCv9Uw+wp9U7DbGKogPeMa3Md+pvez7W35Ei
Eua++tgy/BBjFFFy3l3WFpO9KWgz7zpm7AeKJt8T11dleCfeXkkUAKIAf5qoIbap
sZWwpbkNFhHax2xIPEDgfg1azVY80ZcFuctL7TlLnMQ/0lUTbiSw1nH69MG6zO0b
9f6BQdgAmD06yK56mDcYBZUCAwEAAaOCATgwggE0MA4GA1UdDwEB/wQEAwIBhjAP
BgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBTkrysmcRorSCeFL1JmLO/wiRNxPjAf
BgNVHSMEGDAWgBRge2YaRQ2XyolQL30EzTSo//z9SzBgBggrBgEFBQcBAQRUMFIw
JQYIKwYBBQUHMAGGGWh0dHA6Ly9vY3NwLnBraS5nb29nL2dzcjEwKQYIKwYBBQUH
MAKGHWh0dHA6Ly9wa2kuZ29vZy9nc3IxL2dzcjEuY3J0MDIGA1UdHwQrMCkwJ6Al
oCOGIWh0dHA6Ly9jcmwucGtpLmdvb2cvZ3NyMS9nc3IxLmNybDA7BgNVHSAENDAy
MAgGBmeBDAECATAIBgZngQwBAgIwDQYLKwYBBAHWeQIFAwIwDQYLKwYBBAHWeQIF
AwMwDQYJKoZIhvcNAQELBQADggEBADSkHrEoo9C0dhemMXoh6dFSPsjbdBZBiLg9
NR3t5P+T4Vxfq7vqfM/b5A3Ri1fyJm9bvhdGaJQ3b2t6yMAYN/olUazsaL+yyEn9
WprKASOshIArAoyZl+tJaox118fessmXn1hIVw41oeQa1v1vg4Fv74zPl6/AhSrw
9U5pCZEt4Wi4wStz6dTZ/CLANx8LZh1J7QJVj2fhMtfTJr9w4z30Z209fOU0iOMy
+qduBmpvvYuR7hZL6Dupszfnw0Skfths18dG9ZKb59UhvmaSGZRVbNQpsg3BZlvi
d0lIKO2d1xozclOzgjXPYovJJIultzkMu34qQb9Sz/yilrbCgj8=
-----END CERTIFICATE-----
```

There are three certificates.
This is because the server is using a certificate chain.
A **certificate chain** is when the CA signs and intermediate certificate which will then sign the actual certificate.
Or multiple intermediate certificates.
This is used to decentralize the certificate signing process, creating the hierarchical public key infrastructure with self signed root CAs at the top.

We store the three certificates from Google in the `assets/` folder.
We inspect the subject and issuer of each:

```
$ openssl x509 -noout -subject -issuer -in assets/google.crt
subject=CN = www.google.com
issuer=C = US, O = Google Trust Services LLC, CN = GTS CA 1C3

$ openssl x509 -noout -subject -issuer -in assets/google_interm2.crt
subject=C = US, O = Google Trust Services LLC, CN = GTS CA 1C3
issuer=C = US, O = Google Trust Services LLC, CN = GTS Root R1

$ openssl x509 -noout -subject -issuer -in assets/google_interm1.crt
subject=C = US, O = Google Trust Services LLC, CN = GTS Root R1
issuer=C = BE, O = GlobalSign nv-sa, OU = Root CA, CN = GlobalSign Root CA
```

The server certificate is for `www.google.com` and is issued and signed by `C = US, O = Google Trust Services LLC, CN = GTS CA 1C3`, who in turn is signed by `C = US, O = Google Trust Services LLC, CN = GTS Root R1`, who in turn is signed by the GlobalSign Root CA.
We extracted the GlobalSign Root CA from Firefox and placed it in the `assets/` folder as well:

```
$ openssl x509 -noout -subject -issuer -in assets/GlobalSignRootCA.crt
subject=C = BE, O = GlobalSign nv-sa, OU = Root CA, CN = GlobalSign Root CA
issuer=C = BE, O = GlobalSign nv-sa, OU = Root CA, CN = GlobalSign Root CA
```

To verify the entire security chain we use:

```
$ cat google.crt google_interm2.crt google_interm1.crt > google_chain.crt
$ openssl verify -CAfile google_interm2.crt google_chain.crt
google_chain.crt: OK
```

The first command creates the `google_chain.crt` file with the chain of certificates, the most specific certificate first.
The we use `openssl verify` to successfully verify the certificate chain.

### Validation and Assessment of Remote Certificates

Mostly for testing purposes, we want to know whether a given HTTPS server setup is valid and whether it is secure (i.e. it uses strong TLS parameters).
For this we can use the [SSLTest Web App](https://www.ssllabs.com/ssltest/) or the [`testssl.sh` CLI tool](https://testssl.sh/).

**SSLTest**

Using [SSLTest](https://www.ssllabs.com/ssltest/) is quite easy.
Enter the URL of the target web server and then wait for the results.
Results come in a grade summary, that's an average of several criteria, and a detailed analysis of the HTTPS security features.

Generally, it is advised to aim for a higher score.
Note that a higher score may mean using certain security features that some browsers don't support.
So there needs to be a trade-off between security and browser support.

**testssl.sh**

Installing `testssl.sh` is as simple as cloning [the repository](https://github.com/drwetter/testssl.sh) and changing to a stable branch:

```
$ git clone https://github.com/drwetter/testssl.sh
[...]

$ cd testssl.sh/

$ git checkout -b v3.0.7 v3.0.7
```

Now just pass an URL to the `testssl.sh` script:

```
$ ./testssl.sh security.cs.pub.ro
```

If that doesn't work, you can use Docker as detailed in [the repository](https://github.com/drwetter/testssl.sh).

The output is similar to the one from SSLTest.
The benefit of using `testssl.sh` is it allows automation and it doesn't require a GUI browser to do the test.

## Summary of Commands

See below a summary of commands useful for working with HTTPS and digital certificates.

Capture HTTP packets and print their contents (ash human-readable ASCII characters):

```
$ sudo tcpdump -A tcp port 80
```

Get remote web page:

```
$ wget http://www.google.com

$ wget https://www.google.com

$ curl http://www.google.com

$ curl https://www.google.com
```

Inspect certificate file:

```
$ openssl x509 -noout -text -in certificate.crt

$ openssl x509 -noout -subject -issuer -in certificate.crt
```

Verify certificate:

```
$ openssl verify -CAfile CA.crt certificate.crt
```

Extract certificate(s) from remote end:

```
$ openssl s_client -showcerts -connect www.google.com:443 -servername www.google.com < /dev/null 2> /dev/null | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p'
```

Assess remote HTTPS and certificate security:

```
$ ./testssl.sh security.cs.pub.ro
```

## Challenges

### 01. Investigate SSL/TLS-enabled Websites

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

### 02. Investigate Remote SSL/TLS Certificates

Download, inspect and verify remote certificates.
Use `openssl s_client` to download a certificate.
Use `openssl x509` to investigate the downloaded certificate.
Use the Certificate Manager-like interface in you browser to extract the corresponding root certificates.
Use `openssl verify` to verify a certificate;
use the extracted root certificate.

Investigate the following websites:
* https://slack.com/
* https://www.pornhub.com/
* https://www.emag.ro/

### 03. Investigate Remote SSL/TLS Certificates with SNI

Download, inspect and verify remote certificates.

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

So be sure to use [SNI support](https://major.io/2012/02/07/using-openssls-s_client-command-with-web-servers-using-server-name-indication-sni/) (*Server Name Indication*) for the `openssl s_client` command to download the correct certificate.
This means using the `-servername` option.

### 04. Inspect Me PEM

Get the flag from the certificate: https://sss-web.cyberedu.ro/challenge/25d5c870-fc5a-11ec-bf6f-33097481169b

### 05. Inspect Me DER

Get the flag from the certificate: https://sss-web.cyberedu.ro/challenge/82120dc0-fc5a-11ec-8907-a767cfc56b45

### 06. The Chosen One

Find the correct certificate and get the flag from it: https://sss-web.cyberedu.ro/challenge/38b30e80-fc68-11ec-9c1f-6177b11a278c

### 07. Proper Naming

Get the flag from http://141.85.224.117:3280
Submit the flag to: https://sss-web.cyberedu.ro/challenge/32215080-fc7b-11ec-940b-ad7b1c8e700c

### 08. Inside

Get the flag from http://141.85.224.117:3380
Submit the flag to: https://sss-web.cyberedu.ro/challenge/298a4d30-fc85-11ec-9624-c3b4658b387a

### 09. Only for Members

Connect via HTTPS to a https://141.85.224.117:31443.
Use client certificate authentication to retrieve the flag.

The client certificate needs to be signed by the same certification authority as that of the server.
See the files and scripts in the `securing-communication/assets/ca/` folder in the repository.

Submit flag to: https://sss-web.cyberedu.ro/challenge/c8d977d0-fc9c-11ec-80d9-0de38261593f

### Extra: Tutorial: Inspect HTTPS Traffic

In this tutorial challenge, we capture and aim to decrypt HTTPS traffic.
We use Wireshark to capture traffic.

To decrypt traffic, we need to have access to the private key of the server.
Copy the contents of the private key from the Nginx server set up above, from the `/etc/letsencrypt/live/<hostname>/privkey.pem` into a local file.

Start Wireshark (as `root`).
Load the private key in Wireshark using instructions [here](https://accedian.com/blog/how-to-decrypt-an-https-exchange-with-wireshark/).

Start packet capture in Wireshark and filter packets to / from the IP address of the server.
Use a string such as `ip.addr = <server_IP_address>` in the filter line in Wireshark.

Use `curl` to request the index page from the server:
```
curl https://<hostname>
```

Packet capture in Wireshark will not show decrypted content, similar to the image below.

![HTTPS not decrypted](https-capture-decrypt/wireshark-no-decrypt.png)

This is because, by default, the connection uses SSL / TLS ciphers with [PFS](https://en.wikipedia.org/wiki/Forward_secrecy) (*Perfect Forward Secrecy*) usually enabled with DHE (*Diffie-Hellman Exchange*).
Don't bother with the acronyms and their significance, we use them to let you know the terms and maybe look for additional information later on.

However, we can request `curl` to not use PFS, by choosing a simpler cipher.
This simple cipher will use the private key that we are in possession of (and that we loaded into Wireshark) to encrypt traffic.
This is also explained [here](https://accedian.com/blog/how-to-decrypt-an-https-exchange-with-wireshark/).

Use `curl` to request the index page from the server with a simpler cipher that does not use DHE:
```
curl --ciphers AES256-SHA https://<hostname>
```

Now, the packet capture shows actual decrypted HTTP content, similar to the image below.

![HTTPS decrypted](https-capture-decrypt/wireshark-decrypt.png)

You can use `Right click` -> `Follow` -> `HTTP stream` to extract the HTTP traffic only.

In summary, with access to the private key, if the cipher used in the HTTPS connection (HTTP + SSL / TLS) doesn't use DHE, we can decrypt the traffic.
Of course, this requires access to the private key.
In an actual attack this is another part of the attack vector where some server-side vulnerability allows the extraction of the private key.

## Resources and Tools

* [tcpdump](https://www.tcpdump.org/)
* [Wireshark](https://www.wireshark.org/)
* [openssl](https://www.openssl.org/)
* [OpenSSL Essentials](https://www.digitalocean.com/community/tutorials/openssl-essentials-working-with-ssl-certificates-private-keys-and-csrs)
* [The Most Common OpenSSL Commands](https://www.sslshopper.com/article-most-common-openssl-commands.html)
* [OpenSSL Examples](https://geekflare.com/openssl-commands-certificates/)
* [OpenSSL Certificate Authority](https://jamielinux.com/docs/openssl-certificate-authority/index.html)
* [testssl.sh](https://testssl.sh/)
* [SSL Server Test from SSL Labs](https://www.ssllabs.com/ssltest/)

## Further Reading

* https://geekflare.com/ssl-test-certificate/
* https://www.feistyduck.com/library/openssl-cookbook/
