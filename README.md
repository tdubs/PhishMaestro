# PhishMaestro
A tool to take control of the requests that come into your phishing site and send different payloads to different users. You select the payloads in real-time based on whatever parameters of the HTTP request you wish to take into account.

Defaults parameters are:
- Username
- Password
- HTTP User-Agent
- Remote IP Address (and OrgName from whois)

Add whatever variables you wish to track, and automate it!


# General Logic Flow
When a user hits the index.html page you'll prompt them for their credenitals. The credentials are submitted to the 'submit.php' at which point a PHP session is started.

If the remote subnet is listed in subnets.txt, then perform the action listed as the second argument (check the index.php for the switch case of possible actions) 

If the remote host is not in one of the subnets
Check the session-actions direcotry for a file with the name of the PHP_SESSION_ID

If the session-actions session file does not exist then create a file in the sessions-queue (if it does not already exist)

The monitor.pl script watches the sessions-queue directory for new files. When a new file is created it will prompt you for the action to take with that session.

A file is then created under session-actions directory. The filename is the PHP_SESSION_ID and the contents contain the action you chose. The file in the queue directory is then moved to the log-sessions directory.

##Future Features

Change the client side experience so rather than the page just waiting to load it uses an ajax like call to show a 'Please wait while we log you in' and query the server for the response

Use unique session id in phishing email to map back to users name, then pull this information to print to the monitor screen so you have better insight if it's a 'real user'

Make automatic decisions based on variables like User Agent - Maybe create some sort of rules file

Create timeout function on monitor.pl to perform a default action after x seconds

If 'live checking' file does not exist then send default action
If 'live checking' file exists, then proceed
If 'Disabled' file exists, then shut down site
If 'redirct_all' file exists, then redirect all traffic

Implement parameter feature to each action. Right now they are hard coded in the submit.php and payload page.


# Subnet File
subnet,action,parameter
1.1.1.0/24,payload1,
2.2.2.0/24,redirect,http://innocuous.com

#### Use subnet.list.txt to create default action
0.0.0.0/0,payload-4


# Helpful Commands
In another terminal you can watch the sessions as they come in, actions are applied and the requests are logged in the log directory. Do this in a screen session in the PhishMaestro directory:

`watch -n 0.1 'ls *session*'`
