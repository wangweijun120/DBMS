from bs4 import BeautifulSoup
import pymysql
import pymysql.cursors
import os
import re


def bs(name):
    html_doc = open(name, 'r', encoding='utf8')
    soup = BeautifulSoup(html_doc, 'lxml')
    atrr = 'font-family: TimesNewRomanPS-BoldMT; font-size:9px'
    for span in soup.find_all('span'):
        if span.get_text().strip().lower() == 'uses':
            atrr = span['style']
            break
    finds = soup.find_all(style=atrr)
    strs = []
    data = {}
    for find in finds:
        my_text = find.get_text().replace('\n', '')
        strs.append(my_text)
    contents = soup.get_text()
    contents = contents.split('\n')
    index = ''
    jsp = False
    for content in contents:
        if content in strs and content != '':
            index = content.strip()
        else:
            if not index == '':
                if 'jsp' in content or jsp:
                    data['other info'] = content
                    index = 'other info'
                    continue
                msg = data.get(index, "null")
                if msg == 'null':
                    data[index] = content
                else:
                    temp = str(msg) + '\n' + content
                    data[index] = temp
    return data



def exeUpdate(conn, cur, sql):  # 更新或插入操作
    sta = cur.execute(sql);
    conn.commit();
    return (sta);


def exeQuery(cur, sql):  # 查找操作
    cur.execute(sql);
    return cur.fetchall();


def connClose(conn, cur):  # 关闭连接，释放资源
    cur.close();
    conn.close();


def increase_data(data, pre, cur, con, name):
    sql = 'desc plant'
    curArr = exeQuery(cur, sql);
    fields = []
    for cu in curArr:
        fields.append(cu[0])
    for key, value in data.items():
        if bool(re.search(r'\d', key)) :
            continue
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
        key=key[0:25]
        key = pre + "_" + key.replace(' ', '_')
        value=value.replace('table','')
        value = value.replace('database', '')
        print(name)
        if key not in fields:
            sql1 = "alter table plant add COLUMN " + key + " text not null"
            exeUpdate(con, cur, sql1)
            print(key)
            print(sql1)
        value = con.escape(value)
        sql = "update plant set " + key + " =" + value + " WHERE Symbol='" + name + "'"
        print(key)
        exeUpdate(con, cur, sql)


if __name__ == "__main__":
    prefix = './pg/pg_'
    conn = pymysql.connect(host="localhost", user="root", passwd="123", db="t", charset='utf8mb4');
    cur = conn.cursor()
    sql = 'select Symbol from plant'
    cur1 = exeQuery(cur, sql);
    counter=1
    for item in cur1:
        sname = item[0].lower()
        name = prefix + sname + '.html'
        if not os.path.exists(name):
            continue
        data = bs(name)
        increase_data(data, "pg_", cur, conn, sname)
        print("第"+str(counter)+"个pdf文件！")
        counter = counter+1
