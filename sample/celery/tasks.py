#!/usr/bin/python
from celery import Celery
import smtplib

smtp_user = 'your@gmail.com'
smtp_pass = 'passwd'
smtp_port = 465
smtp_host = 'smtp.gmail.com'

# create object celery
app = Celery('tasks', broker = 'pyamqp://guest@localhost:5672//')

# add worker name
@app.task(name = 'my.task', bind = True, max_retries = 10)
def sendEmail(self, email, message):
	try:
		srv = smtplib.SMTP_SSL(host=smtp_host, port=smtp_port)
		srv.ehlo()
		srv.login(smtp_user, smtp_pass)
		srv.sendmail(smtp_user, email, message)
		srv.close()
	except Exception as edef:
		return self.retry(exc = edef, countdown = 10)