<?php 

class VueConnecter{
	//vue une fois connecté
	function connecter($pseudo,$meilleursJoueurs){
		header("Content-type: text/html; charset=utf-8");
		?>
		<html>
			<body>
				<br/>
				<br/>
				<?php
				echo "Bienvenu ".$pseudo;
				?>
				</br>
				<?php
				echo "Les meilleurs joueurs sont : ";
				foreach($meilleursJoueurs as $m){
					?>
					</br>
					<?php
					echo $m['pseudo']." avec : ".$m['nbPartiesGagnees']." parties gagnées";	
				} 
				?>
				</br>
				<?php
				echo "Essayez de les battres !";
				?>
				
				<form method="post" action="index.php">
				</br>
				</br>
				<input type="submit" name="jouer" value="jouer"/>
				<input type="submit" name="deconnexion" value="deconnexion"/>
				<br/>
				<br/>
				</form>
			</body>
		</html>
	<?php
	}
	//vue une fois la partie lancée jusqu'à ce qu'elle se termine
	function partie(){
		?>
		<html>
			<head>  
				<meta charset="utf-8" />
				<link rel="stylesheet" href="vue/style.css" />
			</head>
			<body>
				<div class="grille">
				<?php	for($i = 0; $i < 8 ;$i++){
							for($j  = 0; $j < 8; $j++){
								$coord = $i.$j;
								if($_SESSION['partie']->getCellule($coord)->estVisible()){
									if($_SESSION['partie']->getCellule($coord)->bombeProche() != 0){?>
										<div class="decouvert"><span><?php echo $_SESSION['partie']->getCellule($coord)->bombeProche(); ?></span></div>
									<?php }
									else{?>
										<div class="decouvert"><span></span></div>
									<?php
									}
								}
								else{?>
								<a href="index.php?x=<?php echo $i;?>&y=<?php echo $j;?>" type="submit" name="boutonDeGrille"><div class="cellule"></div></a>
								<?php }}}?>
				</div>
			</body>
		</html>
	<?php
	}
	//vue pour la fin de la partie qui affiche les statistiques du joueur
	function finDePartie($message,$nbJouees,$nbGagnees){
		?>
		<html>
			<body>
				<br/>
				<br/>
				<?php
				echo "Vous avez ".$message." la partie";
				?>
				</br>
				</br>
				<?php
				echo "Votre score est de ".$nbJouees." partie(s) jouée(s) et ".$nbGagnees." partie(s) gagnée(s)";
				?>
				<form method="post" action="index.php">
				</br>
				</br>
				<input type="submit" name="accueil" value="accueil"/>
				<input type="submit" name="deconnexion" value="deconnexion"/>
				<br/>
				</form>
			</body>
		</html>
	<?php
	}
}
?>