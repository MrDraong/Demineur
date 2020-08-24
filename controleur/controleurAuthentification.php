<?php
require_once PATH_VUE."/vue.php";
require_once PATH_VUE."/vueConnecter.php";
require_once PATH_MODELE."/modele.php";

class ControleurAuthentification{

	private $vue;
	private $vueConnecter;
	private $modele;

	function __construct(){
		try{
			$this->vue=new Vue();
			$this->modele=new Modele();
			$this->vueConnecter = new VueConnecter();
		}
		catch(ConnexionException $e){
			echo $e->getMessage();
		}

	}

	//Affiche la page de connexion
	function accueil(){
		$this->vue->demandePseudo();
	}

	//Affiche une page d'accueil avec le top 3 des joueurs une fois qu'il est connecté
	function connecte(){
		try{
			$this->vueConnecter->connecter($_SESSION['pseudo'],$this->modele->meilleursJoueurs());
		}
		catch(TableAccesException $e){
			$e->getMessage();
		}
	}

	//vérifie que les identifiants rentrés par le joueur sont valides
	function verifIdentif($pseudo, $password){
		try{
			if($this->modele->existsId($pseudo, $password)){
				$_SESSION['pseudo'] = $pseudo;
				$_SESSION['password'] = $password;
				$this->vueConnecter->connecter($pseudo,$this->modele->meilleursJoueurs());
			}
			else{
				echo "Identifiant ou mot de passe invalide";
				$this->accueil();
			}
		}
		catch(TableAccesException $e){
			$e->getMessage();
		}
	}
}
?>
