<?php
/*
 *
 * DATO - Dokumente Aktuelles Termine Organisation
 *
 * Modul: dato.php
 * Copyright (c) 2015 by Franz Hack
 *
 *
 * Aufgaben:
 *
 * Öffnen der Datenbank
 * Menü darstellen
 *
 * Elternobjekt jeder Seite
 *
 */

//
ini_set('default_charset', 'iso-8859-1');


function query($sql) {
	global $pdo;
	if ($pdo == null) {
		db_error('Datenbank konnte nicht initialisiert werden.');
	}

	$vars = array();
	$pattern = "/['\"](.*)['\"]/Us";
	if (preg_match_all($pattern, $sql, $vars)) {
		$sql = preg_replace($pattern, '?', $sql);
		$statement = $pdo -> prepare($sql);
		if (!$statement -> execute($vars[1])) {
			db_error($statement -> errorInfo());
		}
	} else {
		$statement = $pdo -> prepare($sql);
		if (!$statement -> execute()) {
			db_error($statement -> errorInfo());
		}
	}
	if (preg_match("/^\s*SELECT/i", $sql)) {
		return $statement -> fetchAll(PDO::FETCH_ASSOC);
	} else if (preg_match("/^\s*SHOW/i", $sql)) {
		return $statement -> fetchAll(PDO::FETCH_NUM);
	} else if (preg_match("/^\s*INSERT/i", $sql)) {
		return $pdo -> lastInsertId();
	} else if (preg_match("/^\s*UPDATE/i", $sql)) {
		return $statement -> rowCount();
	} else {
		return 1;
	}

}

function PR($arr) {
	echo "<pre class='well'>";
	print_r($arr);
	echo "</pre>";
}

function PP() {
	echo "<pre class='well'>";
	print_r($_POST);
	echo "</pre>";
}

function db_error($errInfo) {
	ob_start();
	debug_print_backtrace();
	$err_msg = ob_get_clean();
	PR($err_msg);

}

class dato {
	private $menu = array();

	function wait($sec) {
		$t = time() + $sec;
		while (time() < $t);
	}

	function openPDO($host, $db, $usr, $pass) {
		global $pdo;
		try {
			$pdo = new PDO("mysql:host=$host;dbname=$db", $usr, $pass);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e -> getMessage();
			exit ;
		}
	}

	protected function openDB($data) {		
		$this -> openPDO($data['dbh'], $data['db'], $data['dbu'], $data['dbp']);
		return TRUE;
	}

	protected function writeData() {
	}


	protected function showMenue() {
		include "head.php";

		echo "<body style='margin-top:60px; margin-bottom:60px'>";

		echo "<div class='navbar navbar-fixed-top'>";
		echo "<div class='navbar-inner'>";
		echo "<div class='container'>";

		echo "<ul class='nav'>";
		echo "<a href='#' class='brand' >Template-App </a>";
		
	  	echo "<li class='dropdown'>";
    	echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>Page 1<b class='caret'></b></a>";
	    echo "<ul class='dropdown-menu'>";
      	echo "<li><a href='index.php'>Submenü 1</a></li>";
		echo "<li><a href='page2.php'>Submenü 2</a></li>";
		echo "<li><a href='page3.php'>Submenü 3</a></li>";
   		echo "</ul>";
  		echo "</li>";

		echo "<li><a href='page2.php'>Page 2</a></li>";
		echo "<li><a href='page3.php'>Page 3</a></li>";
	
		echo "</ul></li>";

		echo "</ul>";
		// class='nav'

		echo "</div></div></div>";

		echo "<div class='container'>";

	}


	protected function displayData() {

	}

	public function __construct() {
		
		
		/*	Bitte sinnvoll ergänzen und Kommentar entfernen
		
		$dbdata = array( 'dbh' => '??host??', 'db' =>'??database??', 'dbu' => '??user??', 'dbp' => '??passwd??');
		$this -> openDB($dbdata);
		*/
		
		$this -> error = '';
		$this -> writeData();
		$this -> showMenue();
		$this -> displayData();

	}

}
?>
<script src="jquery.js"></script>
<script src="./bootstrap/js/bootstrap.min.js"></script>
