import re

# Read the content of the file
with open('index.md', 'r') as file:
    content = file.read()

# Define the regex pattern to match and the replacement pattern
pattern = r'<img src="\.\./media/([^"]+)" width=\d+ height=\d+>'
replacement = r'![\1](../media/\1)'

# Replace the content
modified_content = re.sub(pattern, replacement, content)

# Write the modified content back to the file
with open('index.md', 'w') as file:
    file.write(modified_content)

print("Replacement done!")

