# The Chosen One

This challenge generates an archive comprising 100 certificates.
Each contains a possible flag.
The archive also provides two CAs: one of the CAs was used to sign the certificate containing the right flag, while the other one was used to sign the other certificates.
Using `openssl verify`, you need to find the only certificate that is correctly verified by one of the CAs.
