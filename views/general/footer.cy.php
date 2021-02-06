<?php 
$navigateur = $_SERVER["HTTP_USER_AGENT"];
$bannav = Array('HTTrack','httrack','WebCopier','HTTPClient','websitecopier','webcopier');

foreach ($bannav as $banni) {
	$comparaison = strstr($navigateur, $banni);
	if($comparaison!==false) {
		echo '<center>ok brother</center>';
		exit;
	}
}
?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	
	<footer>
		<div class="container">
			<div class="widget row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
					<h4 class="title">Informații</h4>
					<p>
						 Acesta este un prototip al versiunii finale a interfaței noastre de control. Toate încercările de a te autentifica de pe o adresă IP vor fi monitorizate, evitând spargerile de conturi.
						<br><br>În caz că exista nelămuriri, intră în contact cu echipa Advanced Roleplay și/sau consty, creatorul acestui prototip.
					</p>
				</div>
					
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<h4 class="title">Acces rapid</h4>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
							<ul class="nav">
								<li><a href="#" target="_blank">Discord</a></li>
								<li><a href="#" target="_blank">Autentificare</a></li>
								<li><a href="#" target="_blank">Donează</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				&copy; 2021 Advanced Roleplay. All rights reserved.<br>
				<i>Design and coding by consty.</i>
			</div>
		</div>
	</footer>
	
	<!-- JAVASCRIPT -->
	<script src="assets/plugins/jquery/jquery-3.1.0.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/core.min.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function()
	{
		setInterval(function(){
			$("#message").slideUp("slow");
		}, 6000);
	});
	</script>
	
	<!-- AJAX -->
	<script src="assets/ajax/login.js" type="text/javascript"></script>
	
</body>
</html>