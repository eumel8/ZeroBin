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

    $whoami = shell_exec("whoami");
    echo "whoami: $whoami".PHP_EOL;

    $sshagent = shell_exec("ssh-agent bash -c 'ssh-add; ssh-add -l;'");
    echo "sshagent: $sshagent".PHP_EOL;

    // If there is already a repo, just run a git pull to grab the latest changes       
    $git_pull = shell_exec("git pull 2>&1");
    echo "Git Pull: $git_pull".PHP_EOL;

    die("The End! " . mktime());    
  } 
  else 
  {

    // If the repo does not exist, then clone it into the parent directory

    shell_exec("cd {$LOCAL_ROOT} && git clone {$REMOTE_REPO} {$LOCAL_REPO_NAME}");
    echo "git clone: repo cloned successfully!".PHP_EOL;


    die("The End! " . mktime());
  }
} 

else {
    echo "Payload is not from GitHub. Nothing to see here!".PHP_EOL;
}
