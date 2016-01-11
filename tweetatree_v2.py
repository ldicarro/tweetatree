#! /usr/bin/python

import logging,time,os,urllib2,sys,subprocess

logging.basicConfig(filename='logtest.log',level=logging.DEBUG,format='%(asctime)s %(message)s')
logging.info('===>STARTING...')
logging.info('created at: %s',time.strftime('%b %d %Y %H:%M:%S',time.localtime()))
logging.info('process id: %s',os.getpid())

copy  = ''
oldid = ''	
newid = ''


def update():
		filehandler = urllib2.urlopen("http://www.mackaos.com/twitter_test/tweetatree.php")
		global copy,oldid,newid

		copy = filehandler.read()
		logging.info('copy: %s',copy)

	if len(copy) < 2:
		logging.info('copy was nil')
		return

		copy = copy.split()
		newid = copy[2]

		filehandler = ''

	if copy != '':
		if oldid != newid:
			for i in range(len(copy)):
				if copy[i] == 'deck':
					song = 'deckthehalls'
				elif copy[i] == 'frosty':
					song = 'frosty'
				elif copy[i] == 'jingle':
					song = 'jingle1'
				elif copy[i] == 'jinglerock':
					song = 'jngrock'
				elif copy[i] == 'snow':
					song = 'letitsnow'
				elif copy[i] == 'winter':
					song = 'winterwland'

			if song != '':
				logging.info('song to be played: ' + song)
				try:
					subprocess.call(['aplaymidi','--port','14','/home/pi/mid/' + song + '.mid'])
				except:
					logging.warning('problem with playing song: %s', sys.exc_info()[1])
			oldid = newid
	else:
		loggin.info('copy was nil')



while 1:
	try:
		logging.info('count')
		update()
	except:
		logging.warning('there was a problem: %s', sys.exc_info()[1])
	time.sleep(10)
