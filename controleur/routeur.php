<?php

require_once 'controleurAuthentification.php';
require_once 'controleurPartie.php';
session_start(); // debuter une session
class Routeur {

	private $ctrlAuthentification;
	private $ctrlPartie;

	public function __construct() {
		$this->ctrlAuthentification= new ControleurAuthentification();
		$this->ctrlPartie = new ControleurPartie();
	}

  // Traite une requête entrante
	public function routerRequete() {
		//vérifie les informations rentrées si la personne n'est pas connectée
		if(!isset($_SESSION['pseudo']) and !isset($_SESSION['password'])){
			//On verifie que tous les champs sont remplis.
			//Si oui, on verifie la véracité des paramètres
			if(isset($_POST['pseudo']) and isset($_POST['password'])){
				$speudo =$_POST['pseudo'];
				$passW =$_POST['password'];
				$this->ctrlAuthentification->verifIdentif($speudo, $passW);
			}
			//Sinon, on retourne à l'écran d'accueil vierge
			else
				$this->ctrlAuthentification->accueil();

		}
		//sinon, on condisère que la personne est connectée
		else{
			//On vérifie que l'état du bouton connexion
			//si non, le bouton deconnexion n'a pas été actionné, on va "charger" une partie
			if(!isset($_POST['deconnexion'])){
				//On verifie si une partie existe et/ou a été lancée
				if(isset($_POST['jouer']) and !isset($_SESSION['partie'])){
					$this->ctrlPartie->lancerPartie();
				}
				else if(isset($_SESSION['partie']) and isset($_GET['x']) and isset($_GET['y'])){
					$this->ctrlPartie->verifCellule($_GET['x'],$_GET['y']);
				}
				else if(isset($_POST['accueil'])){
					unset($_SESSION['partie']);
					$this->ctrlAuthentification->connecte();
				}
			}
			//Sinon, le bouton deconnexion est actionné deconnecte le joueur en detruisant la session courrante
			else{
				session_destroy();
				$this->ctrlAuthentification->accueil();
			}

		}

	}


}
?>
