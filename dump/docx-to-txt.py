from docx import Document

# Load the DOCX file
doc = Document('1234.docx')

# Extract text
full_text = []
for paragraph in doc.paragraphs:
    full_text.append(paragraph.text)

# Join all text into a single string with line breaks
text = '\n'.join(full_text)

# Write to TXT file
with open('output.txt', 'w', encoding='utf-8') as txt_file:
    txt_file.write(text)

print("DOCX has been converted to TXT.")
