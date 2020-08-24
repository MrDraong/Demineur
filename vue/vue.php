<?php

class Vue{

	function demandePseudo(){

		?>
		<html>
		<body>
			<br/>
			<br/>
			<form method="post" action="index.php">
				<fieldset>
	 				<legend>Authentification :</legend>
				Entrer votre pseudo : <input type="text" name="pseudo" placeholder="pseudo"/>
			  </br>

				Entrez votre mot de passe : <input type="password" name="password" placeholder="password"/>
			</br>
		</br>
		<input type="submit" name="soumettre" value="envoyer"/>
		</fieldset>
	</form>
	<br/>
	<br/>
	</body>
	</html>
	<?php
	}

}
?>
