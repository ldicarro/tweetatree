#! /usr/bin/python

import logging,time,os,urllib2,sys,subprocess

# Start logging when the application starts
logging.basicConfig(filename='logtest.log',level=logging.DEBUG,format='%(asctime)s %(message)s')
logging.info('===>STARTING...')
logging.info('created at: %s',time.strftime('%b %d %Y %H:%M:%S',time.localtime()))
logging.info('process id: %s',os.getpid())

copy  = ''     # Text to display on sreen
oldid = ''     # Holder for old tweet id
newid = ''     # Holder for new tweet id

# Gets the latest tweet from a php script on the internets. 
# Reads data, sets newid from data and
# compares to oldid. Plays song if ids are different. 
def update():
	filehandler = urllib2.urlopen("http://www.mackaos.com/twitter_test/tweetatree.php")
	global copy,oldid,newid

	copy = filehandler.read()      # get the tweet data from the script
	logging.info('copy: %s',copy)  # log it so we know what is going on.

	# check to make sure there is data there
	# get out if there is not
	if len(copy) < 2:
		logging.info('copy was nil')
		return

	copy = copy.split()  # split copy into an array
	newid = copy[2]      # set newid to the id of the tweet

	filehandler = ''     # clear out filehandler object


	# if there is copy and the old id does not equal the new id, 
	# set the song variable to the file
	if copy != '':
		if oldid != newid:
			for i in range(len(copy)):
				if copy[i] == 'deck':
					song = 'sample/ovenrake_deck-the-halls.mp3'
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
				elif copy[i] == 'doctor':
					song = '01-doctor-who-title-theme.mp3'
				elif copy[i] == 'mario':
					song = '01-super-mario-bros.mp3'
				elif copy[i] == 'star':
					song = '02-invincibility-star.mp3'
				elif copy[i] == 'jedi':
					song = 'super-star-wars-return-of-the-jedi-hopelessness.mp3'

			# try to play the song by calling an external script
			# the script will play the song and flash the relays using
			# the lightpishow scripts
			if song != '':
				logging.info('song to be played: ' + song)
				try:
					subprocess.call(['python','py/synchronized_lights.py','--file=/home/pi/lightshowpi/music/' + song])
				except:
					logging.warning('problem with playing song: %s', sys.exc_info()[1])

			oldid = newid # set the old id to the new id
	else:
		loggin.info('copy was nil')


# main loop
while 1:
	try:
		logging.info('count')
		update()
	except:
		logging.warning('there was a problem: %s', sys.exc_info()[1])
	
	time.sleep(10) # sleep for 10 seconds before trying again
