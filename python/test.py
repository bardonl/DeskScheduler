import mysql.connector
from datetime import datetime
import sched, time

f = open('log.txt','a')

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="password",
  database="deskscheduler"
)

mycursor = mydb.cursor()


#get dates and times
now = datetime.now()
day = datetime.now().weekday()
date = now.strftime("%d-%m-%y")
time = now.strftime("%H:%M:%S")
f.write(str(date))
f.write('\n')
f.write(str(day))
f.write('\n')
f.write(str(time))
f.write('\n')

sql = "SELECT id, day, start_time, end_time, active FROM `schedules` WHERE day = %s AND `start_time` <= %s AND `end_time` >= %s AND `active` = 0"
data = (day,time, time)
mycursor.execute(sql,data)

result = mycursor.fetchall()
        
for x in result:
    for item in result:  
        f.write(str(item))
        f.write('\n')
        
f.close()

