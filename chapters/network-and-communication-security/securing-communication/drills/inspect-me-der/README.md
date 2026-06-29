# Inspect Me DER

The flag is hiding in the certificate details.
We just have to inspect it with `openssl x509`.
The catch is that the certificate is DER-encoded, so we have to pass the `-inform der` options to `openssl`.