<!--<?php
	if(!isset(this::$_url[1])) redirect::to('');
	if(!isset(this::$_url[1]) && user::isLogged()) redirect::to('profile/'.auth::user()->id.'');
	else $user = User::where('id', this::$_url[1])->orWhere('id', (int) this::$_url[1])->first();
	$q = connect::$g_con->prepare('SELECT * FROM `characters` WHERE `id` = ?');
	$q->execute(array(this::$_url[1]));
    $data = $q->fetch(PDO::FETCH_OBJ);
?>-->
<div style="overflow:hidden">
<div id="wrapper">	
			<section class="elements">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-sm-30">
							<div class="tab-select">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tabs1" data-toggle="tab"><i class="fa fa-user"></i>Acasă</a></li>
								</ul>
								
								<div class="tab-content border-1 border-grey-300 padding-20">
									<div class="tab-pane fade in active" id="tabs1">
										<center>
										<img src="<?php echo this::$_PAGE_URL ?>assets/img/skins/<?php echo $data->skin; ?>.png" height="60%" width="60%" draggable="false">
										<h3><?php echo $data->name; ?></h3>
										<i class="fa fa-heartbeat"></i> <strong><?php echo $data->health;?>%</strong>  viață
										<br>
										<i class="fa fa-ambulance"></i> <strong><?php echo $data->armor;?>%</strong>  armură
										</center>
										<br>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												<div class="container">
													<h4>Informații generale</h4>
													<strong>ID de identificare:</strong> #<?php echo $data->id; ?><br>
													<strong>ID-ul master accountului:</strong> <a href="<?php echo this::$_PAGE_URL ?>characters/<?php echo $data->account;?>">[#<?php echo $data->account; ?>]</a><br>
													<br><br>
													<strong>Ore jucate:</strong> <?php echo number_format($data->hours); ?><br>
													<strong>Înălțime:</strong> <?php echo $data->height; ?>cm<br>
													<strong>Greutate:</strong> <?php echo $data->weight; ?>kg<br>
												</div>
											</div>
														
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												<div class="container">
													<h4>Informații financiare</h4>
													<strong>În mână:</strong> $<?php echo number_format($data->money); ?><br>
													<strong>În bancă:</strong> $<?php echo number_format($data->bankmoney); ?><br>
													<strong>Economisiri:</strong> N/A<br>
													<br><br>
												</div>
											</div>

											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												<div class="container">
													<h4>Informații vizibile administrației</h4>
													<strong>Ultima locație:</strong> <?php echo $data->location_area; ?><br>
													<strong>Număr maxim mașini:</strong> <?php echo number_format($data->maxvehicles); ?><br>
													<strong>Număr maxim interioare:</strong> <?php echo number_format($data->maxinteriors); ?><br>
													<br><br>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
</div>