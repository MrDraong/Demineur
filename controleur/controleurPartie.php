<?php
require_once PATH_VUE."/vueConnecter.php";
require_once PATH_MODELE."/modele.php";
require_once PATH_METIER."/Partie.php";

class ControleurPartie{

	private $vueConnecter;
	private $modele;

	function __construct(){
		try{
			$this->modele = new Modele();
			$this->vueConnecter = new VueConnecter();
		}
		catch(ConnexionException $e){
			echo $e->getMessage();
		}

	}

	//créer une partie qui est stockée dans une variable de session
	function lancerPartie(){
		if(!isset($_SESSION["partie"])){
			$_SESSION["partie"] = new Partie();
			$_SESSION["partie"]->creation();
		}
		$this->vueConnecter->partie();
	}
	
	//fonction qui vérifie que les coordonnées de la cellule sur laquelle le joueur a cliquée sont valides
	function verifCellule($x,$y){
		$coord = $x.$y; //les cellules sont stockées dans un tableau associatif donc la clé est la concaténation de X et Y
		//Si le joueur a perdu affiche un écran de défaite et actualise la table partie
		try{
			if($_SESSION["partie"]->actualisePartie($coord)){
				$this->modele->enregistrerPartie(0);
				$this->vueConnecter->finDePartie("perdu",$this->modele->nbPartiesJouees(),$this->modele->nbPartiesGagnees());
			}
			else{
				//si le joueur a gagné actualise la table partie et affiche un ecran de victoire
				if($_SESSION['partie']->estVictoire()){
					$this->modele->enregistrerPartie(1);
					$this->vueConnecter->finDePartie("gagné",$this->modele->nbPartiesJouees(),$this->modele->nbPartiesGagnees());
				}
				else{
					$this->vueConnecter->partie();//sinon on affiche la partie qui a été actualisée
				}
			}
		}
		catch(TableAccesException $e){
			$e->getMessage();
		}
	}

}
?>
