<?php
 	//session start utilisé dans description.php

$valid = 1;
 if (isset($_GET['idM'])) {
	$idMooc = $_GET['idM'];
	//echo $idMooc;
}else{
	$valid = 0;
	echo'erreur methode GET';
}
 
function afficheClassementMooc($idMooc,$bdd)
	{
		try{
			$findUser = $bdd->prepare('SELECT suivre.id_user,pseudo FROM suivre INNER JOIN user ON user.id_user=suivre.id_user WHERE id_mooc = "'.$idMooc.'"');
            $findUser->execute();
            $resufindUser = $findUser->fetchAll();

            $classement = array();

            for($j=0;$j<sizeof($resufindUser);$j++){
            	 $scoreMooc = $bdd->query('SELECT sum(score) AS score FROM faire WHERE id_user= "'.$resufindUser[$j]["id_user"].'" AND id_exercice IN (SELECT id_exercice FROM mooc INNER JOIN chapitre ON mooc.id_mooc = chapitre.id_mooc INNER JOIN exercice ON chapitre.id_chapitre=exercice.id_chapitre WHERE mooc.id_mooc = "'.$idMooc.'")');
                $donnees4 = $scoreMooc->fetch();
                $scoreMooc->closeCursor();

                $classement[$j][0] = $donnees4["score"];
                $classement[$j][1] = $resufindUser[$j]["pseudo"];
            }

            rsort($classement);

            echo 'Classement <br> <br>';

            if(sizeof($resufindUser) <=5){

	            for($i=0;$i<sizeof($classement);$i++){

	            	echo $classement[$i][1];
	                echo '  ';
	                if($classement[$i][0]==NULL){
	                	echo'0';
	                }
	                else {
	                	echo $classement[$i][0];
	                }
	                echo '<br><br>';
	            }
        	}
        	else{

        		for($i=0;$i<5;$i++){

	            	echo $classement[$i][1];
	                echo '  ';
	                if($classement[$i][0]==NULL){
	                	echo'0';
	                }
	                else {
	                	echo $classement[$i][0];
	                }
	                echo '<br><br>';
	            }
        	}

		}
		catch (Exception $e) { 
			echo $e->errorMessage();
  			echo "->erreur afficheClassementMooc()";
		}
		
	}

function getInfos()
{
	global $idMooc;
	include 'connect.inc.php';
	try { 
		$select = $bdd->prepare("SELECT * FROM mooc WHERE id_mooc = $idMooc ");
	    $select->execute();
	    $lignes = $select->fetchAll();

		echo'<div class="content col-md-6">
				<div class="main">
					<h3 class="name"> Nom : '.$lignes[0]["nom_mooc"].' </h3>
					<h3 class="name"> Matière : '.$lignes[0]["matiere"].' </h3>
					<h3 class="name text-justify"> Description : '.$lignes[0]["description"].' </h3>
					<h3 class="name"> Prérequis : '.$lignes[0]["prerequis"].' </h3>
					<h3 class="name"> Durée estimée: '.$lignes[0]["duree"].' heures </h3>
					<h3 class="name"> Note : '.$lignes[0]["note"].' / 5 </h3>
				</div>
			</div>
			<div class="content col-md-6">
				<div class="main">
					<div class="videocontainer"> 
                         <iframe src="https://www.youtube.com/embed/lX7kYDRIZO4" frameborder="0" allowfullscreen></iframe>
                    </div>
				</div>
			</div>

			<br>

			
			';

			if ((isset($_SESSION['login'])) && (!empty($_SESSION['login']))){
				echo'
				<div class="col-sm-4 col-sm-offset-4 animated zoomIn">
					<br>
						<div class="">
							<a href="mooc.php?idM='.$idMooc.'&insert='.$idMooc.'"<button name="id" class="btn btn-block btn-md btn-warning">Accéder au cours</button> </a>
						</div>
				</div>';
			}else{
				echo '
				<div class="col-sm-4 col-sm-offset-4 animated zoomIn">
				<br>
					<div class="">
						<a href="mooc.php?idM='.$idMooc.'"<button name="id" class="btn btn-block btn-md btn-info">Accéder au cours</button> </a>
					</div>
				</div>
				';

			}

			
	} catch (Exception $e) { 
		echo $e->errorMessage();
  		echo "->erreur getInfos()";
	}

}


function getInfos2()
{
	global $idMooc;
	include 'connect.inc.php';
	try { 
		$select = $bdd->prepare("SELECT * FROM mooc WHERE id_mooc = $idMooc ");
	    $select->execute();
	    $lignes = $select->fetchAll();

		$scope_nom = $lignes[0]["nom_mooc"];
		$scope_description = $lignes[0]["description"];
		$scope_prerequis = $lignes[0]["prerequis"];
		$scope_duree = $lignes[0]["duree"];
		$scope_note = $lignes[0]["note"];
	} catch (Exception $e) { 
		echo $e->errorMessage();
  		echo "->erreur getInfos2()";
	}
	
}

function getInfo3MDL(){
	global $idMooc;
	include 'connect.inc.php';
	try { 
		$select = $bdd->prepare("SELECT * FROM mooc WHERE id_mooc = $idMooc ");
	    $select->execute();
	    $lignes = $select->fetchAll();

		$scope_nom = $lignes[0]["nom_mooc"];
		$scope_description = $lignes[0]["description"];
		$scope_prerequis = $lignes[0]["prerequis"];
		$scope_duree = $lignes[0]["duree"];
		$scope_note = $lignes[0]["note"];

		echo '
		<div class="mdl-grid portfolio-max-width">
                <div class="mdl-cell mdl-cell--12-col mdl-card mdl-shadow--4dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Description</h2>
                    </div>
                    <div class="mdl-card__media">
                        <img class="article-image" src="../../images/promo2.JPG" border="0" alt="">
                    </div>
                    <div class="mdl-card__supporting-text">
                        
                    </div>
                    <div class="mdl-grid portfolio-copy">
                        <h3 class="mdl-cell mdl-cell--12-col mdl-typography--headline">Introduction : '.$scope_nom.'</h3>
                        <div class="mdl-cell mdl-cell--6-col mdl-card__supporting-text no-padding">
                            <p>
                            '.$scope_description.' '.$scope_prerequis.' '.$scope_duree.'
                            </p>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <img class="article-image" src="../../images/mooc.png" width="400px" border="0" alt="">
                        </div>
                    </div>';

                    if ((isset($_SESSION['login'])) && (!empty($_SESSION['login']))){
				echo'
					<div class="mdl-card__actions mdl-card--border">
            			<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent"  href="../mooc.php?idM='.$idMooc.'&insert='.$idMooc.'" data-upgraded=",MaterialButton,MaterialRipple">Inscription au cours<span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 231.167px; height: 231.167px; transform: translate(-50%, -50%) translate(61px, 7px);"></span></span></a>
        			</div>
				';
			}else{
				echo '
					<div class="mdl-card__actions mdl-card--border">
                		<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="../mooc.php?idM='.$idMooc.'" data-upgraded=",MaterialButton,MaterialRipple">Accéder au cours<span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 231.167px; height: 231.167px; transform: translate(-50%, -50%) translate(61px, 7px);"></span></span></a>
            		</div>
				';

			}
               echo'
                </div>
                
            </div>
            ';
	} catch (Exception $e) { 
		echo $e->errorMessage();
  		echo "->erreur getInfos2()";
	}
	

}

if($valid==1){
	getInfo3MDL();
	//echo "okok";
}

?>