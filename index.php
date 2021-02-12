<?php

class MultiThreadExample extends Thread {

  private $threadId;
  public $data;

  public function __construct(int $id) {

    $this->threadId = $id;
    $this->data = $id . ":" . date('H:i:s');
  }

  public function run() {

    //thread started
    echo 'thread ' . $this->threadId . "  started.\n";

    //sleep for <threadId> seconds
    sleep($this->threadId);

    //thread ended with timestamp
    echo 'thread ' . $this->threadId . " ended at " . date('H:i:s') . "\n";
  }
}
 
$threads = [];
$i = 0;
do {

  $i++;

  $threads[$i] = new MultiThreadExample($i);

  //start thread job
  $threads[$i]->start();

  //echo thread data
  echo $threads[$i]->data . "\n";

} while($i < 5);

echo "sleeping for 5 seconds... \n";

sleep(5);

echo "now attempting to join...\n";

$i = 0;
do {

  $i++;

  //join thread
  $threads[$i]->join();

} while($i < 5);

echo "threads already finished executing. Nothing to join now.\n";