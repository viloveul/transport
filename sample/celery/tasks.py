#!/usr/bin/python
from celery import Celery

# create object celery
app = Celery('tasks', broker = 'pyamqp://guest@localhost:5672//')

# add worker name
@app.task(name = 'my.task', bind = True, max_retries = 10)
def sendEmail(self, foo, bar):
	try:
		print('Foo {0}, bar: {1}'.format(foo, bar))
	except Exception as edef:
		return self.retry(exc = edef, countdown = 10)