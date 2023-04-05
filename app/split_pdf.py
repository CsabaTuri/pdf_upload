from PyPDF2 import PdfReader, PdfWriter
import sys
source = sys.argv[1]
destenation = sys.argv[2]

input_pdf = PdfReader(open(source, 'rb'))

num_pages = len(input_pdf.pages)

for page_num in range(0,num_pages):
    output_pdf = PdfWriter()
    output_pdf.add_page(input_pdf.pages[page_num])
    output_pdf.write(open(f'/var/www/html/pdf/{page_num}.pdf', 'wb'))