<?php
	Class Cellule{
		public $coord;
		public $estBombe;
		public $visible;
		public $bombeP;
		public $x;
		public $y;
		
		function __construct($x,$y){
			$coord = $x.$y;
			$this->coord = $coord;
			$this->x = $x;
			$this->y = $y;
			$this->estBombe = 0;
			$this->visible = 0;
			$this->bombeP = 0;
		}
		
		//met la variable est bombe a 1 ce qui veut dire que la cellule est une bombe
		function setBombe(){
			$this->estBombe = 1;
		}
		
		//return true si la cellule est une bombe false sinon
		function estBombe(){
			if($this->estBombe == 1){
				return true;
			}
			return false;
		}
		
		//rend la cellule visible
		function setVisible(){
			$this->visible = 1;
		}
		
		//return true si la cellule est visible false sinon
		function estVisible(){
			if($this->visible == 1){
				return true;
			}
			return false;
		}
		
		//stock dans la variable bombeP le nombre de bombes autour de la cellule
		function setBombeProche($nb){
			$this->bombeP = $nb;
		}
		
		//retourne le nombre de bombes autour
		function bombeProche(){
			return $this->bombeP;
		}
		
		//retournent les coordonnées X et Y de la cellule
		function getX(){
			return $this->x;
		}
		
		function getY(){
			return $this->y;
		}
		
	}
?>