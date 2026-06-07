	<!DOCTYPE html>
	<html lang="fr" class="no-js">
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<!-- Author Meta -->
		<meta name="author" content="remi.cervilla">
		<!-- Meta Description -->
		<meta name="description" content="Promenades à cheval de loisir, en pleine nature camarguaise, aux Saintes-Maries-de-la-Mer. Tel un véritable gardian, découvrez la Camargue d'une autre façon">
		<!-- Meta Keyword -->
		<meta name="keywords" content="promenade,cheval,famille,nature,camargue,Saintes-Maries-de-la-Mer,gardian">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Promenades à cheval Tamaris - Saintes Maries de la Mer</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="css/linearicons.css">
			<link rel="stylesheet" href="css/font-awesome.min.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/magnific-popup.css">
			<link rel="stylesheet" href="css/nice-select.css">
			<link rel="stylesheet" href="css/animate.min.css">
			<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
			<link rel="stylesheet" href="css/owl.carousel.css">
			<link rel="stylesheet" href="css/main.css">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143522435-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-143522435-1');
		</script>

		</head>
		<body>
			  <header id="header" id="home">
			    <div class="container">
			    	<div class="row header-top align-items-center">
			    		<div class="col-lg-4 col-sm-4 menu-top-left">
			    			<a href="mailto:info@tamaris-promenades.fr"><span class="lnr lnr-location"></span></a>
			    			<a class="tel" href="mailto:info@tamaris-promenades.fr">info@tamaris-promenades.fr</a>
			    		</div>
			    		<div class="col-lg-4 menu-top-middle justify-content-center d-flex">
							<a href="index.html">
								<img class="img-fluid" src="img/logo.png" alt="">
							</a>
			    		</div>
			    		<div class="col-lg-4 col-sm-4 menu-top-right">
			    			<a class="tel" href="tel:+33 6 31 95 18 04">+33 6 31 95 18 04</a>
			    			<a href="tel:+33 6 31 95 18 04"><span class="lnr lnr-phone-handset"></span></a>
			    		</div>
			    	</div>
			    </div>
			    	<hr>
			    <div class="container">
			    	<div class="row align-items-center justify-content-center d-flex">
				      <nav id="nav-menu-container">
				        <ul class="nav-menu">
				          <li class="menu-active"><a href="index.html">Accueil</a></li>
				          <li><a href="index.html#about">Qui sommes nous ?</a></li>
				          <li><a href="index.html#service">Service</a></li>
				          <li><a href="index.html#tarifs">Tarifs</a></li>
				          <li><a href="index.html#consultancy">Réservation en ligne</a></li>
				          <li><a href="index.html#localisation">Localisez nous !</a></li>						  
				        </ul>
				      </nav><!-- #nav-menu-container -->
			    	</div>
			    </div>
			  </header><!-- #header -->
			  
			<!-- start banner Area -->
			<section class="banner-area relative" id="home">
				<div class="overlay overlay-bg"></div>
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">

		  
			  <?php
    $from = 'info@tamaris-promenades.fr';
	$to = 'info@tamaris-promenades.fr';
	//$to = 'remi.cervilla@gmail.com';
    $name = $_POST["name"];
    $email= $_POST["email"];
    $text= $_POST["message"];
    $phone= '<a href="tel:'.$_POST["phone"].'">'.$_POST["phone"].'</a>';
    $dateResa= $_POST["dateResa"];
	$circuit= $_POST["circuit"];
	$nombre= $_POST["nombre"];
	$momentito= $_POST["momentito"];

	$today = new DateTime(); // This object represents current date/time
	$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

	$match_date = DateTime::createFromFormat('d/m/Y', $dateResa);
	$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

	$diff = $today->diff( $match_date );
	$diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval

	$urgent="";
	switch( $diffDays ) {
		case 0:
			//Today
			$urgent="URGENT - IMMINENT - ";
			break;
		case +1:
			$urgent="URGENT - ";	
			//Tomorrow
			break;
		default:
			$urgent="";
	}


    $message ='<table style="width:100%">
        <tr>
            <td>'.utf8_decode($name).'</td>
        </tr>
        <tr><td>Email : '.$email.'</td></tr>
        <tr><td>T&eacute;l&eacute;phone : '.$phone.'</td></tr>
        <tr><td>Date souhait&eacute;e : '.$dateResa.'</td></tr>
		<tr><td>Moment souhait&eacute; : '.utf8_decode($momentito).'</td></tr>
		<tr><td>Circuit : '.$circuit.'</td></tr>
		<tr><td>Nombre de cavaliers : '.$nombre.'</td></tr>
        <tr><td>Message : '.utf8_decode($text).'</td></tr>
    </table>';

require_once __DIR__ . "/lib/MailingService.php";

//$from = $email;
$subject = "Nouvelle reservation - ".$urgent.$dateResa.' - '.utf8_decode($name)." - ". $circuit;
$body =$message;

try {
    $mailingService = new MailingService();
    $result = json_decode($mailingService->sendMail(array(
        'recipient' => $to,
        'subject' => $subject,
        'content' => $body,
        'sender' => array(
            'name' => 'Tamaris',
            'email' => $from
        ),
        'replyTo' => array(
            'name' => utf8_decode($name),
            'email' => $email
        )
    )), true);

    if (!is_array($result) || $result['res'] !== 'ok') {
        $error = isset($result['error']) ? $result['error'] : 'Erreur inconnue';
        throw new Exception($error);
    }

    echo '<center><h1 style="color:white">Votre message a bien été envoyé. Nous vous contactons pour vous confirmer rapidement votre réservation</h1></center>';
} catch (Exception $exception) {
    echo("<p>Error sending mail:<br/>" . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>");
}
?>
						</div>
					</div>
			</section>	
			
			<!-- start footer Area -->
			<footer class="footer-area section-gap">
				<div class="container">
					<div class="footer-bottom row">
						<p class="footer-text m-0 col-lg-6 col-md-12">
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> Tous droits réservés | Ce template est fait avec <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						</p>
						<div class="footer-social col-lg-6 col-md-12">
							<a href="https://fr-fr.facebook.com/promenadeacheval.tamaris"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-instagram"></i></a>
						</div>
					</div>
				</div>
			</footer>
			<!-- End footer Area -->

			<script src="js/vendor/jquery-2.2.4.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="js/vendor/bootstrap.min.js"></script>
			<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
  			<script src="js/easing.min.js"></script>
			<script src="js/hoverIntent.js"></script>
			<script src="js/superfish.min.js"></script>
			<script src="js/jquery.ajaxchimp.min.js"></script>
			<script src="js/jquery.magnific-popup.min.js"></script>
			<script src="js/owl.carousel.min.js"></script>
			<script src="js/jquery.sticky.js"></script>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
			<script src="js/jquery.nice-select.min.js"></script>
			<script src="js/parallax.min.js"></script>
			<script src="js/waypoints.min.js"></script>
			<script src="js/jquery.counterup.min.js"></script>
			<script src="js/mail-script.js"></script>
			<script src="js/main.js"></script>
		</body>
	</html>
