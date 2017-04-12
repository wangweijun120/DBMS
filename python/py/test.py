from __future__ import print_function
import requests
from parse import parse
import pymysql
import pymysql.cursors


def save_convert(name):
    # url = 'https://plants.usda.gov/plantguide/pdf/' + name + '.pdf'
    url = 'https://plants.usda.gov/factsheet/pdf/' + name + '.pdf'
    # url='https://plants.usda.gov/factsheet/pdf/fs_abba.pdf'
    print(url)
    # url = 'https://plants.usda.gov/plantguide/pdf/pg_abco.pdf'
    headers = {'user-agent': 'Mozilla/5.0 (Windows NT 6.3;'
                             ' WOW64) AppleWebKit/537.36 (KHTML, like Gecko) '
                             'Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2372.400 '
                             'QQBrowser/9.5.10548.400'}
    r = requests.get(url, headers=headers)
    print(r.headers['Content-Type'])

    if r.headers['Content-Type'] == 'application/pdf':
        file_name = 'pdf/' + name + '.pdf'
        fp = open(file_name, 'ab')
        fp.write(r.content)
        print(file_name, '！')
        fp.close()
        print(name + "下载完成！")
        parse(file_name, 'fs/' + name + '.html');
        print(name + "转换完成！")


prefix = 'fs_'
table_name = 'plant_usda'
# sql = "select * from " + table_name
sql = 'desc pdf'
connection = pymysql.connect(host='localhost',
                             user='root',
                             password='123',
                             db='t',
                             port=3306,
                             charset='utf8')
try:
    with connection.cursor() as cursor:
        cout = cursor.execute(sql)
        print("数量： " + str(cout))
        counter = 1;

    for row in cursor.fetchall():
        name = row[0].lower()
        print(row[0])
        name = prefix + name;
        # save_convert(name)
        print("第" + str(counter) + "个pdf文件转换完成！   文件名称：" + name)
        counter = counter + 1
    connection.commit()

finally:
    connection.close()
