<?php

class bd {
	private $bd;
	private $host,$login,$pass,$base;
	public $lock;

	public function __construct($host,$login,$pass,$base) {
		$this->bd=NULL;
		$this->host=$host;
		$this->login=$login;
		$this->pass=$pass;
		$this->base=$base;
	}

	public function __destruct() {
		if($this->bd) $this->close();
	}

	public function init() {
		if($this->bd) return;

		if(!$this->bd=mysql_connect($this->host,$this->login,$this->pass)) {
			$errstr='mysql_connect ('.mysql_errno().') :'."\n".mysql_error();
			trigger_error($errstr,E_USER_ERROR);
			header_html_500($errstr);
		}
		if(!mysql_select_db($this->base,$this->bd)) {
			$errstr='mysql_select_db ('.mysql_errno().') : '.$this->base."\n".mysql_error();
			trigger_error($errstr,E_USER_ERROR);
			$this->close($bd);
			header_html_500($errstr);
		}
		if(!mysql_query('SET NAMES "utf8"')) {
			$errstr='mysql_query utf8 ('.mysql_errno().') : '.$this->base."\n".mysql_error();
			trigger_error($errstr,E_USER_ERROR);
			$this->close($bd);
			header_html_500($errstr);
		}
	}

	// give bd (NULL si non connecté)
	public function bd() { return($this->bd); }

	// Closing database 
	public function close() {
		if(!mysql_close($this->bd)) {
			$errstr='mysql_close ('.mysql_errno().') :'."\n".mysql_error();
			trigger_error($errstr,E_USER_ERROR);
		}
		$this->bd=NULL;
	}

	// Secure sql request element 
	public function secure($val) { return mysql_real_escape_string($val,$this->bd); }

	public function sql($sql) { // requesting
		if($res=@mysql_query($sql,$this->bd)) return $res;

		if($errno=mysql_errno($this->bd)) {
			$errstr='mysql_query ('.mysql_errno($this->bd).') :'."\n".mysql_error($this->bd)."\n\n".$sql;
			trigger_error($errstr,E_USER_ERROR);
		}
	}

	// Nb lines affected 
	public function affected() { return mysql_affected_rows($this->bd); }

	// Nb results from a request
	public function nb($res) { return mysql_num_rows($res); }

	// obtain the next result
	public function result($result) { return mysql_fetch_assoc($result); }

	// obtain the last id auto-i with an insert 
	public function lastid() { return mysql_insert_id($this->bd); }

	// Emptying table
	public function vide($table) { return $this->sql('TRUNCATE TABLE '.$table); }

	// freeing memory mysql results once used 
	public function free(&$res) { return mysql_free_result($res); }

}

?>
