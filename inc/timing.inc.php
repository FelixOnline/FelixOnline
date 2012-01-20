<?php
/*
 * Timing class
 * Measures times and logs them into log file
 *
 * Examples:
 *      // Create new Timing class with \n break
 *      $timing = new Timing("\n");
 * 
 *      // Start timing
 *      $timing->start();
 * 
 *      // Loop ten rounds and sleep one second per round
 *      for ($i=1;$i<=10;$i++) { 
 *          echo $i . "\t"; sleep(1);
 *          // Print elapsed time every 2 rounds
 *          if ($i%2==0) {
 *              $timing->printElapsedTime();
 *          }
 *      }
 * 
 *      // Stop/end timing
 *      $timing->stop();
 * 
 *      // Print only total execution time
 *      $timing->printTotalExecutionTime();
 * 
 *      // Print full stats
 *      $timing->printFullStats(); 
 *      
 *      http://www.if-not-true-then-false.com/2010/php-timing-class-class-for-measure-php-scripts-execution-time-and-php-web-page-load-time/
 */
class Timing {
    private $file; // log reference
    private $filename; // log filename
    private $directory; // directory for log file
    private $break;
    private $start_time;
    private $stop_time;
    private $prev;

    // Constructor for Timing class
    public function __construct($name, $dir = 'log/', $break = "\n") {
        $this->break = $break;
        $this->filename = $name;
        $this->directory = $dir;
        if(TIMING == true) {
            echo 'hello';
            $this->file = fopen($this->directory.$this->filename, 'a');
            $this->newRequest();
        }
        // Set timezone
        date_default_timezone_set('UTC');
    }

    /*
     * Private: Create new log request with header detailing request
     */
    private function newRequest() {
            echo 'hello';
        ob_start(); ?>

/*
 * Request Method: <?php echo $_SERVER['REQUEST_METHOD']; ?> 
 * Request Time: <?php echo $_SERVER['REQUEST_TIME']; ?> 
 * Query String: <?php echo $_SERVER['QUERY_STRING']; ?> 
 * Http Referer: <?php echo $_SERVER['HTTP_REFERER']; ?> 
 * Http User Agent: <?php echo $_SERVER['HTTP_USER_AGENT']; ?> 
 * Remote Address: <?php echo $_SERVER['REMOTE_ADDR']; ?> 
 * Server Port: <?php echo $_SERVER['SERVER_PORT']; ?> 
 * Script Name: <?php echo $_SERVER['SCRIPT_NAME']; ?> 
 * Request Uri: <?php echo $_SERVER['REQUEST_URI']; ?> 
 * <?php echo date("Y-m-d H:i:s"); ?>\n
 */
Start  Prev
<?php 
        $header = ob_get_contents();
        ob_end_clean();
        fwrite($this->file, $header);
        $this->start();
    }

    /*
     * Public: New log marker
     */
    public function log($label) {
        if(TIMING) {
            $log = number_format($this->getElapsedTime(),4,'.','').' '.number_format($this->lastLog(),4,'.','').' - '.$label."\n";
            fwrite($this->file, $log);
            $this->prev = microtime(true);
        }
    }

    /*
     * Private: Time since last log
     */
    private function lastLog() {
        return microtime(true) - $this->prev;
    }

    // Set start time
    public function start() {
        $this->start_time = microtime(true);
        $this->prev = microtime(true);
    }

    // Set stop/end time
    public function stop() {
        $this->stop_time = microtime(true);
    }

    // Returns time elapsed from start
    public function getElapsedTime() {
        return $this->getExecutionTime(microtime(true));
    }

    // Returns total execution time
    public function getTotalExecutionTime() {
        if (!$this->stop_time) {
            return false;
        }
        return $this->getExecutionTime($this->stop_time);
    }

    // Returns start time, stop time and total execution time
    public function getFullStats() {
        if (!$this->stop_time) {
            return false;
        }

        $stats = array();
        $stats['start_time'] = $this->getDateTime($this->start_time);
        $stats['stop_time'] = $this->getDateTime($this->stop_time);
        $stats['total_execution_time'] = $this->getExecutionTime($this->stop_time);

        return $stats;
    }

    // Prints time elapsed from start
    public function printElapsedTime() {
        echo $this->break . $this->break;
        echo "Elapsed time: " . $this->getExecutionTime(microtime(true));
        echo $this->break . $this->break;
    }

    // Prints total execution time
    public function printTotalExecutionTime() {
        if (!$this->stop_time) {
            return false;
        }

        echo $this->break . $this->break;
        echo "Total execution time: " . $this->getExecutionTime($this->stop_time);
        echo $this->break . $this->break;
    }

    // Prints start time, stop time and total execution time
    public function printFullStats() {
        if (!$this->stop_time) {
            return false;
        }

        echo $this->break . $this->break;
        echo "Script start date and time: " . $this->getDateTime($this->start_time);
        echo $this->break;
        echo "Script stop end date and time: " . $this->getDateTime($this->stop_time);
        echo $this->break . $this->break;
        echo "Total execution time: " . $this->getExecutionTime($this->stop_time);
        echo $this->break . $this->break;
    }

    // Format time to date and time
    private function getDateTime($time) {
        return date("Y-m-d H:i:s", $time);
    }

    // Get execution time by timestamp
    private function getExecutionTime($time) {
        return $time - $this->start_time;
    }
}
?>
