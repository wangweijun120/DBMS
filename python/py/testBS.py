from bs4 import BeautifulSoup
from parse import parse
def bs(in_stream,out_stream):
    file_name = parse('pdf/'+in_stream+'.pdf', 'html/'+out_stream+'.html')
    print(file_name)
    html_doc = open(file_name, 'r', encoding='utf8')
    soup = BeautifulSoup(html_doc, 'lxml')
    finds = soup.find_all(style='font-family: TimesNewRomanPS-BoldMT; font-size:9px')
    fp = open('test.txt', 'a', encoding='utf8');
    for find in finds:
        str = find.get_text()
        fp.writelines(str)
        print(str)
