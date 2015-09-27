<?php
/*
 * Archive controller
 */

use FelixOnline\Exceptions;
use FelixOnline\Core\ArchivePublication;
use FelixOnline\Core\ArchiveIssue;
use FelixOnline\Core\ArchiveFile;
use FelixOnline\Core\BaseManager;

class ArchiveController extends BaseController {
	private $currentyear;
	private $year;

	function GET($matches) {
		if(array_key_exists('id', $matches) && array_key_exists('download', $matches) && array_key_exists('part', $matches)) { // viewing a specific issue part
			try {
				$issue = new ArchiveIssue($matches['id']);
			} catch(Exceptions\ModelNotFoundException $e) {
				throw new NotFoundException(
					"Issue ".$matches['id']." not found",
					$matches,
					'ArchiveController'
				);
			}

			if($issue->getInactive() || $issue->getPublication()->getInactive()) {
				throw new NotFoundException(
					"Issue ".$matches['id']." inactive",
					$matches,
					'ArchiveController'
				);
			}

			$issueFile = $issue->getSpecificFile($matches['part']);

			if(!$issueFile) {
				throw new NotFoundException(
					"Issue ".$matches['id']." part ".$matches['part']." not found",
					$matches,
					'ArchiveController'
				);
			}

			$file = BASE_DIRECTORY.'/archive/'.$issueFile->getFilename();
			$filename = $issueFile->getOnlyFilename();
			
			// Make sure the files exists, otherwise we are wasting our time
			if (!file_exists($file)) {
				throw new NotFoundException(
					"Issue ".$matches['id']." file ".$file." not found",
					$matches,
					'ArchiveController'
				);
			}

			$this->serveFile($file, $filename, 'application/pdf');
		} elseif(array_key_exists('id', $matches) && array_key_exists('download', $matches)) { // viewing a specific issue main part
			try {
				$issue = new ArchiveIssue($matches['id']);
			} catch(Exceptions\ModelNotFoundException $e) {
				throw new NotFoundException(
					"Issue ".$matches['id']." not found",
					$matches,
					'ArchiveController'
				);
			}

			if($issue->getInactive() || $issue->getPublication()->getInactive()) {
				throw new NotFoundException(
					"Issue ".$matches['id']." inactive",
					$matches,
					'ArchiveController'
				);
			}

			$issueFile = $issue->getPrimaryFile();

			if(!$issueFile) {
				throw new NotFoundException(
					"Issue ".$matches['id']." primary part not found",
					$matches,
					'ArchiveController'
				);
			}

			$file = BASE_DIRECTORY.'/archive/'.$issueFile->getFilename();
			$filename = $issueFile->getOnlyFilename();
			
			// Make sure the files exists, otherwise we are wasting our time
			if (!file_exists($file)) {
				throw new NotFoundException(
					"Issue ".$matches['id']." file ".$file." not found",
					$matches,
					'ArchiveController'
				);
			}

			$this->serveFile($file, $filename, 'application/pdf');
		} else if(array_key_exists('id', $matches)) {
			try {
				$issue = new ArchiveIssue($matches['id']);

				if($issue->getInactive() || $issue->getPublication()->getInactive()) {
					throw new NotFoundException(
						"Issue ".$matches['id']." inactive",
						$matches,
						'ArchiveController'
					);
				}

				echo 'Issue page';
			} catch(Exceptions\ModelNotFoundException $e) {
				throw new NotFoundException(
					"Issue ".$matches['id']." not found",
					$matches,
					'ArchiveController'
				);
			}
		} else {
			// If a search
			if (array_key_exists('q', $_GET)) {
				$query = trim($_GET['q']);

				$issues = $this->searchContent($query);

				$this->theme->setSite('archive');

				$this->theme->render('archive-search', array(
					'search_results' => $issues,
					'query' => $query,
				));
				return false;
			}

			$issueManager = BaseManager::build('FelixOnline\Core\ArchiveIssue', 'archive_issue');
			$pubManager = BaseManager::build('FelixOnline\Core\ArchivePublication', 'archive_publication');
			$pubManager->filter('inactive = 0');

			$this->currentyear = date('Y');
			if(array_key_exists('decade', $matches)) {
				$this->year = $matches['decade'];
			} else if(array_key_exists('year', $matches)) {
				$this->year = $matches['year'];
			} else {
				$this->year = $this->currentyear;
			}

			// get latest issue year TODO: cache
			$end = $issueManager->filter('inactive = 0')->order('date', 'DESC')->limit(0, 1)->values();
			$end = date('Y', $end[0]->getDate());

			$start = 1950;
			$currentdecade = array();
			$decades = $this->getDecades($start, $end, $currentdecade);

			// 1949 edge case
			array_unshift($decades, array('begin' => '1949', 'final' => '1949', 'selected' => false));
			if($this->year == '1949') { // if selected year is 1949
				$decades[0]['selected'] = true;
				$currentdecade = $decades[0];
			}
			
			// get issues
			$issues = array();
			foreach($pubManager->all() as $pub) {
				$specificIssueManager = BaseManager::build('FelixOnline\Core\ArchiveIssue', 'archive_issue');

				$specificIssueManager = $specificIssueManager
					->filter('inactive = 0')
					->filter('date LIKE "%i-%"', array($this->year))
					->filter('publication = %i', array($pub->getId()))
					->order('date', 'ASC')
					->values();

				$issues[$pub->getId()] = array($pub->getName(), $specificIssueManager);
			}

			$this->theme->appendData(array(
				'decades' => $decades,
				'currentdecade' => $currentdecade,
				'year' => $this->year,
				'issues' => $issues
			));

			$this->theme->setSite('archive');

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
	 * Private: Serve files in
	 *
	 * Credit: http://stackoverflow.com/a/4451376/1165117
	 */
	private function serveFile($file, $filename, $contenttype = 'application/octet-stream') {
		// Avoid sending unexpected errors to the client - we should be serving a file,
		// we don't want to corrupt the data we send
		@error_reporting(0);

		$filesize = filesize($file);

		// Send standard headers
		header("Content-Type: $contenttype");
		header("Content-Length: $filesize");
		header('Content-Disposition: inline; filename="'.$filename.'"');

		// if requested, send extra headers and part of file...
		readfile($file);

		// Exit here to avoid accidentally sending extra content on the end of the file
		exit;
	}

	private function searchContent($query) {
		$app = \FelixOnline\Core\App::getInstance();
		$sql = $app['safesql']->query("
			SELECT
				archive_issue.id AS id,
				MATCH(archive_file.content) AGAINST ('%s') AS Relevance
			FROM archive_file, archive_issue
			WHERE archive_file.issue_id = archive_issue.id
			AND archive_file.issue_id IS NOT NULL
			AND archive_issue.inactive = 0
			HAVING Relevance > 0
			ORDER BY Relevance DESC",
			array(
				$query	
			));

		$results = $app['db']->get_results($sql);
		$issues = array();

		// no results
		if (is_null($results)) {
			return $issues;
		}

		$maxr = 0; // max relevance
		foreach($results as $obj) {
			$issue = new ArchiveIssue($obj->id);

			if($issue->getPublication()->getInactive()) {
				continue; // Inactive publication
			}

			$issues[] = $issue;
			if ($maxr == 0) {
				$maxr = $obj->Relevance;
			}
			$relevance = ($obj->Relevance * (min(100, $maxr * 200))) / $maxr;
			$issue->setRelevance($relevance);
		}
		return $issues;
	}
}
