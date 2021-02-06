<!--<div id="wrapper">
		<section class="hero hero-panel" style="background-image: url(assets/img/cover/cover-login.png);">
			<div class="container relative">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pull-none margin-auto">
						<div class="panel panel-default panel-login">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-user"></i>Autentificare</h3>
							</div>
							
							<div class="animated fadeIn">
								<div class="panel-body">							
									<form name="login">
										<div id="ajax"></div>
											<div class='form-group input-icon-left'>
												<i class='fa fa-user'></i>
												<input type='email' class='form-control' name='email' id='email' placeholder='Nume de cont' data-toggle='tooltip' data-placement='right' title='Introdu numele contului tău.' autofocus>
											</div>
											
											<div class='form-group input-icon-left'>
												<i class='fa fa-lock'></i> 
												<input type='password' class='form-control' name='password' id='password' placeholder='Parolă' data-toggle='tooltip' data-placement='right' title='Introdu parola ta.'>
											</div>
											
											<button type='submit' class='btn btn-primary btn-block' onclick='return loginUser()'>Autentificare</button>
											
											<div class='form-actions'>
												<div class='checkbox checkbox-primary'>
													<input type='checkbox' id='checkbox'> 
													<label for='checkbox'>Păstrează datele</label>
												</div>
											</div>
									</form>
</div> -->
<?php
$accounts = connect::rows('accounts');
$characters = connect::rows('characters');
$topthead = user::take(1)->orderBy(\connect::raw('money'),'desc')->get();
$topbank = user::take(1)->orderBy(\connect::raw('bankmoney'),'desc')->get();
$tophours = user::take(1)->orderBy(\connect::raw('hours'),'desc')->get();
$lesshealth = user::take(1)->orderBy(\connect::raw('health'),'asc')->get();
$topjailtime = user::take(1)->orderBy(\connect::raw('arrestedtime'),'desc')->get();
$lastid = user::take(1)->orderBy(\connect::raw('id'),'desc')->get();
?>
<div id="wrapper">
		<section class="bg-grey-50 border-bottom-1 border-grey-200 relative">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="title outline">
							<h4><i class="fa fa-sitemap" style="animation: fa-spin 3s infinite linear !important;"></i>Acasă</h4>
							<p>Bine ai venit, consty. În acest moment există <?php echo $accounts;?> conturi înregistrate și <?php echo $characters;?> caractere create.</p>
						</div>
					</div>
				</div>
				
				<div class="row">
                <?php foreach($topthead as $rows=>$row): ?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Cel mai bogat jucător [ÎN MÂNĂ] ($<?php echo number_format($row->money); ?>).</li></ul>
							</div>
						</div>
					</div>
                <?php endforeach; ?>
				<?php foreach($tophours as $rows=>$row): ?>			
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Cel mai activ jucător (<?php echo number_format($row->hours); ?> TLS).</li></ul>
							</div>
						</div>
					</div>
                <?php endforeach; ?>	
                <?php foreach($topbank as $rows=>$row): ?>			
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Cel mai bogat jucător [ÎN BANCĂ] ($<?php echo number_format($row->bankmoney); ?>).</li></ul>
							</div>
						</div>
					</div>	
				<?php endforeach; ?>
                <?php foreach($lesshealth as $rows=>$row): ?>		
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Jucătorul cu cea mai puțină viață (<?php echo $row->health; ?>%).</li></ul>
							</div>
						</div>
					</div>
                <?php endforeach; ?>	
                <?php foreach($topjailtime as $rows=>$row): ?>	
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Jucătorul cu cel mai mult timp de pușcărie (<?php echo $row->arrestedtime; ?>s).</li></ul>
							</div>
						</div>
					</div>
                <?php endforeach; ?>
                <?php foreach($lastid as $rows=>$row): ?>			
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
								<img src="assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false">
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?></h3>
								<ul><li>Ultimul jucător (ID <?php echo $row->id; ?>).</li></ul>
							</div>
						</div>
                <?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	</div>