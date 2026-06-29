https://jamielinux.com/docs/openssl-certificate-authority/create-the-root-pair.html

You can create a CA with a custom name using the script:

`./create-ca myca`

You can then create a certificate signed by the CA and inject the flag as the CN:

`./create-cert myca "SSS{flag}"`
