<?php
	if(!isset(this::$_url[1])) redirect::to('');
	if(!isset(this::$_url[1]) && user::isLogged()) redirect::to('profile/'.auth::user()->id.'');
	else $user = User::where('id', this::$_url[1])->orWhere('id', (int) this::$_url[1])->first();
	$q = connect::$g_con->prepare('SELECT * FROM `accounts` WHERE `id` = ?');
	$q->execute(array(this::$_url[1]));
    $data = $q->fetch(PDO::FETCH_OBJ);
    $caractere = user::take(6)->orderBy(\connect::raw('id'),'asc')->get();
?>

<div id="wrapper">
		<section class="bg-grey-50 border-bottom-1 border-grey-200 relative">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="title outline">
							<h4><i class="fa fa-list"></i>Caracterele deținute de <?php echo $data->username ?></h4>
						</div>
					</div>
				</div>
				
				<div class="row">
                <?php foreach($caractere as $rows=>$row): ?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="card card-hover">
							<div class="card-img">
							<a href="<?php echo this::$_PAGE_URL ?>character/<?php echo $row->id;?>"><img src="<?php echo this::$_PAGE_URL ?>assets/img/skins/<?php echo $row->skin; ?>.png" draggable="false"></a>
							</div>
							
							<div class="caption">
								<h3 class="card-title"><?php echo $row->name; ?> [#<?php echo $row->id; ?>]</h3>
								<ul><li>Bani [ÎN MÂNĂ] ($<?php echo number_format($row->money); ?>).</li></ul>
                                <ul><li>Bani [ÎN BANCĂ] ($<?php echo number_format($row->bankmoney); ?>).</li></ul>
                                <ul><li>Ore jucate (<?php echo number_format($row->hours); ?>).</li></ul>
							</div>
						</div>
					</div>
                <?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	</div>