
		<div class="topt">
			<div class="gradient"></div>
			<div class="white"></div>
			<div class="shadow"></div>
		</div>
		<div class="content">
			<h1>IKON | Login</h1>
			<div class="background"></div>
			<div class="wrapper">
				<div class="box beforeLoading">
					<div class="header grey">
						<img src="/assets/img/icons/packs/fugue/16x16/lock.png" width="16" height="16">
						<h3>Login</h3>
					</div>
					<?php echo Form::open(); ?>
						<div class="content" style='padding:10px 0'>
							<div class=" _100" style='position:relative;margin-bottom:10px'>
									<?php echo Form::input('username', isset($username) ? $username : '', array('class' => 'required', 'id' => 'username', 'placeholder' => 'Username', 'autofocus')); ?>
									 <i class="icon-user icon-large"></i>

							</div>
							<div class=" _100" style='position:relative;'>

									<?php echo Form::password('password', isset($password) ? $password : '', array('class' => 'required', 'id' => 'password', 'placeholder' => 'Password')); ?>
									<i class="icon-lock icon-large"></i>

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
		
