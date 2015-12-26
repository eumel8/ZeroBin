<?php

// Set Variables
$LOCAL_ROOT         = "/data/www/paste.eumel.de/";
$LOCAL_REPO_NAME    = "paste2.eumel.de";
$LOCAL_REPO         = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
$REMOTE_REPO        = "git@github.com:eumel8/ZeroBin.git";
$BRANCH             = "master";

$payload_github = file_get_contents('php://input');
$data = json_decode($payload_github);

if ( $data->ref === 'refs/heads/master' ) {

  // Only respond to POST requests from Github
  echo "Payload received from GitHub".PHP_EOL;

  if( file_exists($LOCAL_REPO) ) 
  {

   // 1. generate ssh-key for httpd user
   // 2. put the public key as deploy key on github
   // 3. set up local ssh config (i.e. /var/lib/wwwrun/.ssh/config)
   // Host *
   //         StrictHostKeyChecking no
   //                 PasswordAuthentication no
   //

    $sshagent = shell_exec("cd {$LOCAL_REPO} && ssh-agent bash -c 'ssh-add; git pull;'");
    echo "sshagent: $sshagent".PHP_EOL;

    die("Sucess deployed! " . mktime());    
  } 
  else 
  {

    // If the repo does not exist, then clone it into the parent directory

    shell_exec("cd {$LOCAL_ROOT} && ssh-agent bash -c 'ssh-add; git clone {$REMOTE_REPO} {$LOCAL_REPO_NAME}'");
    echo "git clone: repo cloned successfully!".PHP_EOL;


    die("Sucessfuly cloned! " . mktime());
  }
} 

else {
    echo "Payload is not from GitHub. Nothing to see here!".PHP_EOL;
}
