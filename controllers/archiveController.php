<?php
/*
 * Archive controller
 */
class ArchiveController extends BaseController {
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
        $currentyear = date('Y');
        if(array_key_exists('decade', $matches)) {
            $year = $matches['decade'];
        } else if(array_key_exists('year', $matches)) {
            $year = $matches['year'];
        } else {
            $year = $currentyear;
        }

        // generate list of decades
        $start = 1949; // TODO: move into config

        // get latest issue year TODO: cache
        $sql = "SELECT 
                    MAX(YEAR(PubDate)) 
                FROM Issues";
        $end = $this->dba->get_var($sql);

        $start = 1950;
        $decades[0]['final'] = '1949';
        $currentdecade = array();
        for($i = $start; $i <= $end; $i = $i+10) {
            $final = $i + 9;
            if($final > $currentyear) {
                $final = $currentyear;
            }
            if($year >= $i && $year <= $final) {
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
        
        $this->theme->appendData(array(
            'decades' => $decades,
            'currentdecade' => $currentdecade,
            'year' => $year
        ));

        $this->theme->render('archive');
    }
}
