<?php

require_once PATH_METIER.'/Cellule.php';

class Partie{
	public $tableauCellules;
	public $cellulesVictoire;

	function __construct(){
		$this->tableauCellules = array();
		$this->cellulesVictoire = 0;
	}

	//initialise la partie
	public function creation(){
		$tableauBombes = array();
		//creation de 64 cellules
		for($i = 0; $i < 8; $i++){
			for($j = 0; $j < 8; $j++){
				$coord = $i.$j;
				$this->tableauCellules[$coord] = new Cellule($i,$j);
			}
		}
		//création de 10 bombes à des positions différentes
		for($i = 0; $i < 10; $i++){
			$tempX = rand(0,7);
			$tempY = rand(0,7);
			$tempCoord = $tempX.$tempY;

			while(in_array($tempCoord,$tableauBombes)){
				$tempX = rand(0,7);
				$tempY = rand(0,7);
				$tempCoord = $tempX.$tempY;
			}
			array_push($tableauBombes,$tempCoord);
			$this->tableauCellules[$tempCoord]->setBombe();
		}
	}

	//fonction pour tester si les coordonnées de la cellule cliquée sont celles d'une bombe
	//post-condition retourne vrai si les coordonnées correspondent à une bombe sinon faux
	public function actualisePartie($coord){
		if($this->tableauCellules[$coord]->estBombe()){
			return true;
		}
		else{
			$this->devoileCellules($coord);
			return false;
		}

	}

	//devoile la cellule cliquée ou les cellules si celle-ci n'a pas de bombes à côté d'elle
	public function devoileCellules($coord){
		$celluleTemp = $this->tableauCellules[$coord];
		$celluleTemp->setVisible();

		$tempX = $celluleTemp->getX();
		$tempY = $celluleTemp->getY();

		$tempNbBombes = 0;

		$this->cellulesVictoire += 1;

		//On calcule le nombre de bobmes autour de la cellule
		if($tempX > 0 and $tempX < 7){
			if($tempY != 0){
				if($this->tableauCellules[($tempX-1).($tempY-1)]->estBombe()){
				$tempNbBombes += 1;
				}
				if($this->tableauCellules[$tempX.($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX+1).($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($tempY != 7){
				if($this->tableauCellules[($tempX-1).($tempY+1)]->estBombe()){
				$tempNbBombes += 1;
				}
				if($this->tableauCellules[$tempX.($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX+1).($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($this->tableauCellules[($tempX-1).$tempY]->estBombe()){
				$tempNbBombes += 1;
			}
			if($this->tableauCellules[($tempX+1).$tempY]->estBombe()){
					$tempNbBombes += 1;
			}
		}
		else if($tempX == 0){
			if($tempY != 0){
				if($this->tableauCellules[$tempX.($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX+1).($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($tempY != 7){
				if($this->tableauCellules[$tempX.($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX+1).($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($this->tableauCellules[($tempX+1).$tempY]->estBombe()){
					$tempNbBombes += 1;
			}
		}
		else if($tempX == 7){
			if($tempY != 0){
				if($this->tableauCellules[$tempX.($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX-1).($tempY-1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($tempY != 7){
				if($this->tableauCellules[$tempX.($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
				if($this->tableauCellules[($tempX-1).($tempY+1)]->estBombe()){
					$tempNbBombes += 1;
				}
			}
			if($this->tableauCellules[($tempX-1).$tempY]->estBombe()){
					$tempNbBombes += 1;
			}
		}
		//On dévoile les cellules autour d'une case si celle ci n'a pas de bombes autour
		if($tempNbBombes != 0){
			$celluleTemp->setBombeProche($tempNbBombes);
		}
		else{
			if($tempX - 1 >= 0){
				if(!$this->tableauCellules[($tempX-1).$tempY]->estVisible()){
					$this->devoileCellules(($tempX-1).$tempY);
				}

				if($tempY-1 >= 0){
					if(!$this->tableauCellules[($tempX-1).($tempY-1)]->estVisible()){
						$this->devoileCellules(($tempX-1).($tempY-1));
					}
					if(!$this->tableauCellules[($tempX).($tempY-1)]->estVisible()){
						$this->devoileCellules(($tempX).($tempY-1));
					}
				}
				if($tempY+1 <= 7){
					if(!$this->tableauCellules[($tempX-1).($tempY+1)]->estVisible()){
						$this->devoileCellules(($tempX-1).($tempY+1));
					}
				}
			}
			if($tempX + 1 <= 7){
				if(!$this->tableauCellules[($tempX+1).$tempY]->estVisible()){
					$this->devoileCellules(($tempX+1).$tempY);
				}
				if($tempY-1 >= 0){
					if(!$this->tableauCellules[($tempX+1).($tempY-1)]->estVisible()){
						$this->devoileCellules(($tempX+1).($tempY-1));
					}
				}
				if($tempY+1 <= 7){
					if(!$this->tableauCellules[($tempX+1).($tempY+1)]->estVisible()){
						$this->devoileCellules(($tempX+1).($tempY+1));
					}
					if(!$this->tableauCellules[$tempX.($tempY+1)]->estVisible()){
						$this->devoileCellules($tempX.($tempY+1));
					}
				}
			}
		}
	}

	//Si le joueur a decouvert 54 case alors il ne reste que les 10 bombes donc le joueur a gagné
	public function estVictoire(){
		if($this->cellulesVictoire == 54){
			return true;
		}
		return false;
	}

	//permet de récupérer une cellule dans le tableau
	public function getCellule($coord){
		return $this->tableauCellules[$coord];
	}
}
?>
