<?php
/*
 * Archive controller
 */
class ArchiveController extends BaseController {
    private $currentyear;
    private $year;

    function __construct() {
        parent::__construct();
        //global $dbaname;
		$dbaname = 'media_felix_archive';

		$dba = new ezSQL_mysqli();
        $dba->quick_connect(
            $this->db->dbuser,
            $this->db->dbpassword,
            $dbaname,
			$this->db->dbhost,
			'utf8'
        );
		$safesql = new SafeSQL_MySQLi($dba->dbh);
		$dba->cache_timeout = 24; // Note: this is hours
		$dba->use_disk_cache = true;
		$dba->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
		$dba->show_errors();

		$this->dba = $dba;
    }

    function GET($matches) {
        global $timing;
        if(array_key_exists('id', $matches) && array_key_exists('download', $matches)) { // viewing a specific issue
            //$file = 'http://felixonline.co.uk/archive/IC_1963/1963_0184_A.pdf';
            $file = BASE_DIRECTORY.'/archive/1963_0184_A.pdf';
            $filename = '1963_184.pdf'; /* Note: Always use .pdf at the end. */
            $this->serveFileResumable($file, $filename, 'application/pdf');
        } else if(array_key_exists('id', $matches)) {
            echo 'Issue page';     
        } else {
            $this->currentyear = date('Y');
            if(array_key_exists('decade', $matches)) {
                $this->year = $matches['decade'];
            } else if(array_key_exists('year', $matches)) {
                $this->year = $matches['year'];
            } else {
                $this->year = $this->currentyear;
            }

            // get latest issue year TODO: cache
            $sql = "SELECT 
                        MAX(YEAR(PubDate)) 
                    FROM Issues";
            $end = $this->dba->get_var($sql);

            $start = 1950;
            $currentdecade = array();
            $decades = $this->getDecades($start, $end, $currentdecade);

            // 91949 edge case
            array_unshift($decades, array('final' => '1949'));
            if($this->year == '1949') { // if selected year
                $decades[0]['selected'] = true;
                $currentdecade = $decades[0];
            }
            
            $this->theme->appendData(array(
                'decades' => $decades,
                'currentdecade' => $currentdecade,
                'year' => $this->year
            ));

            $this->theme->render('archive');
        }
    }
    
    /*
     * Private: Return list of decades from a start and end year
     *
     * Returns array
     */
    private function getDecades($start, $end, &$currentdecade) {
        $decades = array();
        for($i = $start; $i <= $end; $i = $i+10) {
            $final = $i + 9;
            if($final > $this->currentyear) {
                $final = $this->currentyear;
            }
            if($this->year >= $i && $this->year <= $final) {
                $selected = true;
            } else {
                $selected = false;
            }
            $info = array(
                'begin' => $i,
                'final' => $final,
                'selected' => $selected
            );
            array_push($decades, $info);

            if($selected) $currentdecade = $info;
        }
        return $decades;
    }

    /*
     * Private: Serve files in a resumable way
     *
     * Credit: http://stackoverflow.com/a/4451376/1165117
     */
    private function serveFileResumable($file, $filename, $contenttype = 'application/octet-stream') {
        // Avoid sending unexpected errors to the client - we should be serving a file,
        // we don't want to corrupt the data we send
        @error_reporting(0);

        // Make sure the files exists, otherwise we are wasting our time
        if (!file_exists($file)) {
            throw new NotFoundException("Issue doesn't exists on server");
        }

        // Get the 'Range' header if one was sent
        if (isset($_SERVER['HTTP_RANGE'])) $range = $_SERVER['HTTP_RANGE']; // IIS/Some Apache versions
        else if ($apache = apache_request_headers()) { // Try Apache again
            $headers = array();
            foreach ($apache as $header => $val) $headers[strtolower($header)] = $val;
            if (isset($headers['range'])) $range = $headers['range'];
            else $range = FALSE; // We can't get the header/there isn't one set
        } else $range = FALSE; // We can't get the header/there isn't one set

        // Get the data range requested (if any)
        $filesize = filesize($file);
        if($range) {
            $partial = true;
            list($param,$range) = explode('=',$range);
            if (strtolower(trim($param)) != 'bytes') { // Bad request - range unit is not 'bytes'
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            $range = explode(',',$range);
            $range = explode('-',$range[0]); // We only deal with the first requested range
            if (count($range) != 2) { // Bad request - 'bytes' parameter is not valid
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            if ($range[0] === '') { // First number missing, return last $range[1] bytes
                $end = $filesize - 1;
                $start = $end - intval($range[0]);
            } else if ($range[1] === '') { // Second number missing, return from byte $range[0] to end
                $start = intval($range[0]);
                $end = $filesize - 1;
            } else { // Both numbers present, return specific range
                $start = intval($range[0]);
                $end = intval($range[1]);
                if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
            }      
            $length = $end - $start;
        } else $partial = false; // No range requested

        // Send standard headers
        header("Content-Type: $contenttype");
        header("Content-Length: $filesize");
        header('Content-Disposition: inline; filename="'.$filename.'"');
        header('Accept-Ranges: bytes');

        // if requested, send extra headers and part of file...
        if ($partial) {
            header('HTTP/1.1 206 Partial Content'); 
            header("Content-Range: bytes $start-$end/$filesize"); 
            if (!$fp = fopen($file, 'r')) { // Error out if we can't read the file
                header("HTTP/1.1 500 Internal Server Error");
                exit;
            }
            if ($start) fseek($fp,$start);
            while ($length) { // Read in blocks of 8KB so we don't chew up memory on the server
                $read = ($length > 8192) ? 8192 : $length;
                $length -= $read;
                print(fread($fp,$read));
            }
            fclose($fp);
        } else readfile($file); // ...otherwise just send the whole file

        // Exit here to avoid accidentally sending extra content on the end of the file
        exit;
    }
}
