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

	public function init() {
		if($this->bd) return;
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		);
		if(DEBUG)
			$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$dsn = "mysql:host=".$this->host.";dbname=".$this->base;
		try{
		$this->bd = new PDO($dsn,$this->login,$this->pass,$options);
		}catch(PDOException $e){
			echo 'Connexion échouée : ' . $e->getMessage();
		}
	}

	// Donne bd (NULL si non connecté)
	public function bd() { return($this->bd); }

	// ferme le lien avec la bd
	public function close() {
		$this->bd=NULL;
	}


	//Fait une requete sql et retourne les resultat sous forme de tableau
	public function select($sql) { // soumission d'une requete
		$req = $this->bd->query($sql);
		$res = $req->fetchAll();
		$req->closeCursor();
		return $res;
	}
	
	//Fait une requete select securisée
	public function prepareSelect($sql) { // soumission d'une requete
		$req = $this->bd->prepare($sql);
		$res = $req->fetchAll();
		$req->closeCursor();
		return $res;
	}
	
		//Fait une requete select securisée retourne false si la req echoue
	public function prepareExec($sql,$array) { // soumission d'une requete
		$req = $this->bd->prepare($sql);
		$req->execute($array);
		return $req;
	}
	
	//faire des requetes qui ne retourne pas de ligne (UPDATE / INSERT / DELETE )
	public function exec($sql) { // soumission d'une requete
		$req = $this->bd->exec($sql);
		return $req;
	}
}
