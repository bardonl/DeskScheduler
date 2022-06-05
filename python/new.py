#!/usr/bin python3

from datetime import datetime
import sched, time, os,mysql, mysql.connector, pigpio

pi = pigpio.pi()
PIN_UP = 18
PIN_DOWN = 17

path = "/var/www/html/DeskScheduler/python"

# Set working dir
os.chdir(path)

now = datetime.now()
day = datetime.now().weekday()
date = now.strftime("%d-%m-%y")
time_now = now.strftime("%H:%M:%S")

f = open("log.txt", "a")

#get dates and times
f.write('Script ran: ' + str(date) +', ' + str(day) + ', ' + str(time_now))
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
query = """SELECT id, day, start_time, end_time, movement FROM `schedules` WHERE `day` = %s AND `start_time` <= %s;"""
cursor.execute(query,(day,time_now,))
data = cursor.fetchall()

if data:
    print(str(day))
    print(str(time_now))
    t_end = time.time() + 9
    for row in data:
      if(str(row["start_time"]) <= str(time_now) and str(row["end_time"]) >= str(time_now) and row["movement"] == 0):
          print("move up")
          print(row["id"])
          while time.time() < t_end:
              pi.set_PWM_dutycycle(18,200)
          
          pi.set_PWM_dutycycle(18,0)
          query = """UPDATE `schedules` SET `movement` = 1 WHERE `id` = %s;"""
          cursor.execute(query,(row["id"],))
          cnx.commit()
      elif(str(row["end_time"]) <= str(time_now) and row["movement"] == 1):
          print("move down")
          while time.time() < t_end:
              pi.set_PWM_dutycycle(17,200)
          
          pi.set_PWM_dutycycle(17,0)
          query = """UPDATE `schedules` SET `movement` = 0 WHERE `id` = %s;"""
          cursor.execute(query,(row["id"],))
          cnx.commit()
      else:
          print("stay idle")
          
      print(row["end_time"])
      f.write(" ".join(map(str, row)))
      f.write('\n')
else:
    print("idle")
cursor.close()
cnx.close()

f.close()



