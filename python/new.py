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
cursor = cnx.cursor(dictionary=True)
query = """SELECT id, day, start_time, end_time, movement FROM `schedules` WHERE day = %s AND start_time <= %s"""
cursor.execute(query,(day,time))
data = cursor.fetchall()

if data:
    print(data)
    for row in data:
      print(str(day))
      print(str(time))
      print(row["start_time"])
      if(str(row["start_time"]) <= time and str(row["end_time"]) >= time and movement === 0):
          print("move up")
      if(str(row["end_time"]) <= time and movement === 1):
          print("move down")
      print(row["end_time"])
      f.write(" ".join(map(str, row)))
      f.write('\n')

cursor.close()
cnx.close()

f.close()



