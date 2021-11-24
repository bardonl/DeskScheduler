#!/usr/bin python3

from datetime import datetime
import sched, time, os,mysql, mysql.connector

path = "/var/www/html/DeskScheduler/python"

# Set working dir
os.chdir(path)

now = datetime.now()
day = datetime.now().weekday()
date = now.strftime("%d-%m-%y")
time = now.strftime("%H:%M:%S")

f = open("log.txt", "a")

#get dates and times
f.write('Script ran: ' + str(date) +', ' + str(day) + ', ' + str(time))
f.write('\n')

config = {
  'user': 'root',
  'password': 'password',
  'host': '127.0.0.1',
  'database': 'deskscheduler',
  'raise_on_warnings': True
}

cnx = mysql.connector.connect(**config)
cursor = cnx.cursor()
query = ("SELECT id, day, start_time, end_time FROM `schedules`")
cursor.execute(query)
data = cursor.fetchall()

if data:
    for row in data:
      f.write(" ".join(map(str, row)))
      f.write('\n')

cursor.close()
cnx.close()

f.close()



