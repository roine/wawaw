<?php echo Form::open(); ?>
<div class="content no-padding with-actions">

	<div class="section _100">
		<input type="text" name="groupName" class="name _50" placeholder='Name of the group'>
	</div>
	<div class="_100 acl columns">
		<div class="userControl">
			<h2>User Control</h2>
			<p>
				<label for="userList">
					<input type="checkbox" name="userList">
					users list
				</label>
			</p>
			<p>
				<label for="userEdit">
					<input type="checkbox" name="userEdit">
					edit users
				</label>
			</p>
			<p>
				<label for="userDelete">
					<input type="checkbox" name="userDelete">
					delete users
				</label>
			</p>
			<p>
				<label for="userView">
					<input type="checkbox" name="userView">
					view users
				</label>
			</p>
			<p>
				<label for="userCreate">
					<input type="checkbox" name="userCreate">
					create new users
				</label>
			</p>
			<p>
				<label for="userPassword">
					<input type="checkbox" name="userPassword">
					change password
				</label>
			</p>
			<p>
				<label for="userGroup">
					<input type="checkbox" name="userGroup">
					change Group
				</label>
			</p>
			<p>
				<label for="userBlock">
					<input type="checkbox" name="userBlock">block/unblock other users
				</label>
			</p>
		</div>
		<!-- end user acl -->
		
		<div class="groupControl">
			<h2>Group Control</h2>
			<p>
				<label for="groupList">
					<input type="checkbox" name='groupList'>
					List of group
				</label>
			</p>
			<p>
				<label for="groupEdit">
					<input type="checkbox">
					Edit Group
				</label>
			</p>
			<p>
				<label for="groupDelete">
					<input type="checkbox">
					Delete Group
				</label>
			</p>
			<p>
				<label for="groupView">
					<input type="checkbox">
					View Group
				</label>
			</p>
			<p>
				<label for="groupCreate">
					<input type="checkbox">
					Create Group
				</label>
			</p>
		</div>
		<!-- end group acl -->	
	
		<div class="customerControl">
			<h2>Customer Control</h2>
			<p>
				<label for="customerFullInfo"><input type="checkbox" name='customerFullInfo'>
					See all the columns (no filter)
				</label>
			</p>
			<p>
				<label for="customerAll"><input type="checkbox" name='customerAll'>
					See all the tables (needless to check all)
				</label>
			</p>
			<hr>
			<p>
				<label for="customerIBRead"><input type="checkbox" name='customerIBRead'>
					Read the Introducing Brokers
				</label>
			</p>
			<p>
				<label for="customerFranchiseRead"><input type="checkbox" name="customerFranchiseRead">
				Read the Franchise Scheme
				</label>
			</p>
			<p>
				<label for="customerWL"><input type="checkbox" name='customerWL'>
					Read White Label
				</label>
			</p>
			<p>
				<label for="customerSP"><input type="checkbox" name="customerSP">
					Read Senior Partners
				</label>
			</p>
			<p>
				<label for="customerCallback"><input type="checkbox" name="customerCallback">
					Read Callback
				</label>
			</p>
			<p>
				<label for="customerInquiry"><input type="checkbox" name='customerInquiry'>
					Read Inquiry
				</label>
			</p>
			<p>
				<label for="customerSM"><input type="checkbox" name='customerSM'>
					Read Small Registrations
				</label>
			</p>
			<p>
				<label for="customerForexblog"><input type="checkbox" name='customerForexblog'>
					Read Forex Blog Registrations
				</label>
			</p>
			<p>
				<label for="customerPromotion"><input type="checkbox" name='customerPromotion'>
					Read Promotions
				</label>
			</p>
			<p>
				<label for="customerVC"><input type="checkbox" name="customerVC">
					Read Video Conference
				</label>
			</p>
			<p>
				<label for="customerDM"><input type="checkbox" name="customerDM">
					Read Demo Account
				</label>
			</p>
			<p>
				<label for="customerFB"><input type="checkbox" name="customerFB">
					Read Facebook Registration
				</label>
			</p>
			<p>
				<label for="customerPayOrder"><input type="checkbox" name="customerPayOrder">
				Read Pay Order Informations
				</label>
			</p>
			<p>
				<label for="customerCMG"><input type="checkbox" name="customerCMG">
					Read CMG
				</label>
			</p>
			<hr>
			<p>
				<label for="customerIndex"><input type="checkbox">
					Can see some customers table(important)
				</label>
			</p>
			<p>
				<label for="customerDelete"><input type="checkbox" name='customerDelete'>
					Can delete customers informations
				</label>
			</p>
			<p>
				<label for="customerUpdate"><input type="checkbox" name='customerUpdate'>
					Can update the customers informations
				</label>
			</p>
			<p>
				<label for="customerFilterLang"><input type="checkbox" name="customerFilterLang">
					Can filter by language
				</label>
			</p>
			<p>
				<label for="customerFilterDate"><input type="checkbox" name="customerFilterDate">
					Can filter by date
				</label>
			</p>
			<p>
				<label for="customerFilterMulti"><input type="checkbox" name="customerFilterMulti">
					Can filter column by column
				</label>
			</p>
		</div>
		<!-- end customers control -->
	</div>
	<!-- end columns -->
</div>
<!-- end content -->
<div class="actions">
	<div class="actions-left">
		<input type="reset" class='over color red button'>
	</div>
	<div class="actions-right">
		<input type="submit" class='over color blue button'>
	</div>
</div>

<?php echo Form::close(); ?>