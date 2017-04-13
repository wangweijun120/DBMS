## 爬取characher页面
from bs4 import BeautifulSoup
import requests
import pymysql
import pymysql.cursors

def exeQuery(cur, sql):  # 查找操作
    cur.execute(sql);
    return cur.fetchall();

def exeUpdate(conn, cur, sql):  # 更新或插入操作
    sta = cur.execute(sql);
    conn.commit();
    return (sta);

def bs(content):
    html_doc = content
    soup = BeautifulSoup(html_doc, 'lxml')
    tables = soup.find(cellpadding='3')
    if tables==None:
        return None
    trs = tables.find_all('tr');
    character={}
    trs=trs[1:]
    for tr in trs :
      tds=tr.find_all('td')
      if len(tds)==2:
          td1=tds[0]
          td1=td1.get_text().replace(' ','_')
          td1=td1.replace('/','_')
          td2=tds[1].get_text();
          character[td1]=td2
    return character


def convert(name):
    url = 'https://plants.usda.gov/java/charProfile?symbol='+name.upper()
    #url='https://plants.usda.gov/java/charProfile?symbol=ABFR3'
    headers = {'user-agent': 'Mozilla/5.0 (Windows NT 6.3;'
                             ' WOW64) AppleWebKit/537.36 (KHTML, like Gecko) '
                             'Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2372.400 '
                             'QQBrowser/9.5.10548.400'}
    r = requests.get(url, headers=headers)
    print(r.headers['Content-Type'])
    if r.headers['Content-Type'] == 'text/html;charset=UTF-8':
        return bs(r.text)
def fliter_field(key):
    key = key.strip().lower()
    key = key.replace('-', '')
    key = key.replace(':', '')
    key = key.replace(',', '')
    key = key.replace('&', '')
    key = key.replace('=', '')
    key = key.replace('(', '')
    key = key.replace(')', '')
    key = key.replace('/', '')
    key = key.replace('\\', '')
    return key
def save_to_db(data,conn,cur,name):
    sql = 'desc python_usdaplant'
    curArr = exeQuery(cur, sql);
    fields = []
    for cu in curArr:
        fields.append(cu[0].lower())
    print(fields)
    for key, value in data.items():
        key=fliter_field(key);
        if key not in fields:
            sql1 = "alter table python_usdaplant add COLUMN " + key + " text not null"
            print(sql1)
            exeUpdate(conn, cur, sql1)
        value = conn.escape(value)
        sql = "update python_usdaplant set " + key + " =" + value + " WHERE Symbol='" + name + "'"
        exeUpdate(conn, cur, sql)

prefix = 'fs_'
table_name = 'python_usdaplant'
# sql = "select * from " + table_name
sql = 'select Symbol from  python_usdaplant'
conn = pymysql.connect(host="localhost", user="root", passwd="123", db="t", charset='utf8mb4');
cur = conn.cursor()
try:
     cout = cur.execute(sql)
     print("数量： " + str(cout))
     counter = 1;
     for row in cur.fetchall():
        name = row[0]
        print(name)
        data=convert(name)
        if data==None:
            print(1)
            continue
        print(data)
        save_to_db(data,conn,cur,name)
        print("第" + str(counter) + "个转换完成！   文件名称：" + name)
        counter = counter + 1
finally:
    cur.close()
    conn.close()
