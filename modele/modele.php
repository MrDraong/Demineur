<?php
// Classe generale de definition d'exception
class MonException extends Exception{
	private $chaine;
	public function __construct($chaine){
		$this->chaine=$chaine;
	}

	public function afficher(){
		return $this->chaine;
	}

}


// Exception relative à un probleme de connexion
class ConnexionException extends MonException{
}

// Exception relative à un probleme d'accès à une table
class TableAccesException extends MonException{
}


// Classe qui gère les accès à la base de données
class Modele{
	private $connexion;
// Constructeur de la classe
	public function __construct(){
		try{  
			$chaine="mysql:host=".HOST.";dbname=".BD;
			$this->connexion = new PDO($chaine,LOGIN,PASSWORD);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			$exception=new ConnexionException("problème de connexion à la base");
			throw $exception;
		}
	}


// méthode qui permet de se deconnecter de la base
	public function deconnexion(){
		$this->connexion=null;
	}


// utiliser une requête classique
// méthode qui permet de récupérer les pseudos dans la table pseudo
// post-condition:
// retourne un tableau à une dimension qui contient les pseudos.
// si un problème est rencontré, une exception de type TableAccesException est levée
	public function getPseudos(){
		try{  
			$statement=$this->connexion->query("SELECT pseudo from joueurs;");

			while($ligne=$statement->fetch()){
				$result[]=$ligne['pseudo'];
			}
			return($result);
		}
		catch(PDOException $e){
			throw new TableAccesException("problème avec la table joueur");
		}  
	}



// vérifie qu'un pseudo existe dans la table pseudonyme
//pré-condition les paramètres ne sont pas vides
// post-condition retourne vrai si le pseudo existe sinon faux
// si un problème est rencontré, une exception de type TableAccesException est levée
	public function existsId($pseudo,$password){
		try{  
			//récupération des pseudos dans la base de données
			$statement = $this->connexion->query("select pseudo,motDePasse from joueurs");
			$result=$statement->fetchAll(PDO::FETCH_ASSOC);
			
			//pour chaque résultat on vérifie si le pseudo existe, si c'est le cas on vérifie que le mdp est correcte
			foreach($result as $resId){
				if ($resId['pseudo'] == $pseudo){
					if (password_verify($password,$resId['motDePasse'])){
						return true;
					}
				}
			}
			return false;
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table joueur");
		}
	}
	
	//valeur = 1 si le joueur à gagné la partie ou 0 si il a perdu
	//Dans les deux cas on incrémente le nombre de partie jouées par le joueur
	public function enregistrerPartie($valeur){
		try{
			//on test si le joueur est présent dans la table partie ou si il n'a jamais joué on le créer
			 $statement =$this->connexion->prepare("select count(*) as nb from parties where pseudo = ?"); 
			 $statement->bindParam(1, $_SESSION['pseudo']);
			 $statement->execute();
			 $result = $statement->fetch(PDO::FETCH_ASSOC);
			 
			if($result['nb'] == 0){
				$statement = $this->connexion->prepare("INSERT into parties values(?,0,0)");
				$statement->bindParam(1, $_SESSION['pseudo']);
				$statement->execute();
			}
			
			//on incrémente le nombre de partie que le joueur a jouée
			$statement = $this->connexion->prepare("UPDATE parties SET nbPartiesJouees = nbPartiesJouees + 1 WHERE pseudo=?;");
			$statement->bindParam(1, $_SESSION['pseudo']);
			$statement->execute();
			
			//si le joueur a gagné on incrémente le nombre de partie qu'il a gagnée
			if($valeur == 1){
			    $statement = $this->connexion->prepare("UPDATE parties SET nbPartiesGagnees = nbPartiesGagnees + 1 WHERE pseudo=?;");
			    $statement->bindParam(1, $_SESSION['pseudo']);
			    $statement->execute();
			}
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}
	
	//retourne le nombre de partie jouées par le joueur
	public function nbPartiesJouees(){
		try{
			$statement =$this->connexion->prepare("select nbPartiesJouees from parties where pseudo = ?"); 
			$statement->bindParam(1, $_SESSION['pseudo']);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result['nbPartiesJouees'];
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}
	
	//retourne le nombre de parties gagnées par le joueur
	public function nbPartiesGagnees(){
		try{
			$statement = $this->connexion->prepare("select nbPartiesGagnees from parties where pseudo = ?"); 
			$statement->bindParam(1, $_SESSION['pseudo']);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result['nbPartiesGagnees'];
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}
	
	//retourne les 3 meilleurs joueurs
	public function meilleursJoueurs(){
		try{
			$statement = $this->connexion->query("SELECT pseudo,nbPartiesGagnees FROM parties ORDER BY nbPartiesGagnees ASC limit 0, 3");

			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}
}

?>