<?php
class TestWork extends Threaded {
    
    protected $complete;
    public function __construct($pData) {
        
        $this->complete = false;
        $this->testData = $pData;
    }
    
    public function run() {
        usleep(2000000); //sleep 2 seconds to simulate a large job
        $this->complete = true;
    }
    public function isDone() {
        return $this->complete;
    }
}
class RunPool extends Pool {
    public $data = array(); 
    private $numTasks = 0; 
    /**
     * override the submit function from the parent
     * to keep track of our jobs
     */
    public function submit(Threaded $task) {
        $this->numTasks++;
        parent::submit($task);
    }
    /**
     * used to wait until all workers are done
     */
    public function process() {
        // Run this loop as long as we have
        // jobs in the pool
        while (count($this->data) < $this->numTasks) {
            $this->collect(function (TestWork $task) {
                // If a task was marked as done
                // collect its results
                if ($task->isDone()) {
                    $tmpObj = new stdclass();
                    $tmpObj->complete = $task->complete;
                    //this is how you get your completed data back out [accessed by $pool->process()]
                    $this->data[] = $tmpObj;
                }
                return $task->isDone();
            });
        }
        // All jobs are done
        // we can shutdown the pool
        $this->shutdown();
        return $this->data;
    }
}
$pool = new RunPool(100);
$testData = 'asdf';
for($i=0;$i<5;$i++) {
    $pool->submit(new TestWork($testData));
}
$retArr = $pool->process(); //get all of the results
echo '<pre>';
print_r($retArr); //return the array of results (and maybe errors)
echo '</pre>';
?>