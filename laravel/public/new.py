#!/usr/bin/python

from datetime import datetime
import sched, time

f = open('log.txt','a')

#get dates and times
now = datetime.now()
day = datetime.now().weekday()
date = now.strftime("%d-%m-%y")
time = now.strftime("%H:%M:%S")
f.write('Script ran:')
f.write('\n')
f.write(str(date))
f.write('\n')
f.write(str(day))
f.write('\n')
f.write(str(time))
f.write('\n')

f.close()

