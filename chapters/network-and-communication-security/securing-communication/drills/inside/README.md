# Inside

We make a request to the server; it tells us to "try secure".
We need to try a "secure" port instead: 33443.
We are one step closer, but the browser response doesn't offer much information.

We need instead to inspect the certificates with `openssl`:
```sh
openssl s_client -connect $ip:33443 -showcerts </dev/null 2>/dev/null | openssl x509 -text -noout
```

No flag so far, but we see this detail under X509v3 extensions:
```
X509v3 Subject Alternative Name:
    DNS:pax.imperia.org, DNS:spqr.net
```

Let's try to get the certificates by setting `spqr.net` as the server name:
```sh
openssl s_client -connect $ip:33443 -servername spqr.net -showcerts </dev/null 2>/dev/null | openssl x509 -text -noout
```

If we also add `| grep SSS`, this gives us the flag.

Solution script in `../sol/solution.sh`.
