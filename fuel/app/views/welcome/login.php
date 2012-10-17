
		<div class="topt">
			<div class="gradient"></div>
			<div class="white"></div>
			<div class="shadow"></div>
		</div>
		<div class="content">
			<h1>IKON | Login</h1>
			<div class="background"></div>
			<div class="wrapper">
				<div class="box">
					<div class="header grey">
						<img src="/assets/img/icons/packs/fugue/16x16/lock.png" width="16" height="16">
						<h3>Login</h3>
					</div>
					<?php echo Form::open(); ?>
						<div class="content no-padding">
							<div class="section _100">
								<?php echo Form::label('Username', 'username'); ?>
								<div>
									<?php echo Form::input('username', isset($username) ? $username : '', array('class' => 'required', 'id' => 'username')); ?>
								</div>
							</div>
							<div class="section _100">
								<?php echo Form::label('Password', 'password'); ?>
								<div>
									<?php echo Form::password('password', isset($password) ? $password : '', array('class' => 'required', 'id' => 'password')); ?>
								</div>
							</div>
						</div>
						<div class="actions">
							<div class="actions-right">
								<input type="submit" value="Login"/>
							</div>
						</div>
				</div>
				<div class="shadow"></div>
			</div>
		</div>
		
