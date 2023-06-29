<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <div class="row row-sm">
            <div class="col-md-6">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-indigo">
                  User Information
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <table class="table table-hover" style="font-size: 12px;">
		             		<tbody>
		             			<tr>
		             				<td width="30%"><label>UserNum:</label></td>
		             				<td><?=$accinfo[0]['UserNum'];?></td>
		             			</tr>
		             			<tr>
		             				<td width="30%"><label>UserName:</label></td>
		             				<td><?=$accinfo[0]['UserName'];?></td>
		             			</tr>
		             			<tr>
		             				<td width="30%"><label>Email:</label></td>
		             				<td><?=$accinfo[0]['UserEmail'];?></td>
		             			</tr>
		             			<tr>
		             				<td width="30%"><label>EP:</label></td>
		             				<td>
		             					<form method="post" action="<?=base_url()?>adminpanel/AccountList/UpdateAccountEPoints/<?=$accinfo[0]['UserNum']?>">
			             				<div class="row row-sm">
            							<div class="col-md-4">
            							<input type="text" maxlength="4" class="form-control" name="EPoints" value="<?=((!$accpoints) ? "0" :"".$accpoints[0]['EPoint']."")?>" style="width: 90%;">
            							</div>
            							<div class="col-md-3">
            								<button class="btn btn-info"><i class="fas fa-edit"></i></button>
            							</div>
			             				</form>		     
		             				</td>
		             			</tr>
		             			
								<tr>
		             				<td width="30%"><label>VP:</label></td>
		             				<td>
		             				<form method="POST" action="<?=base_url()?>adminpanel/AccountList/UpdateAccountInformation/<?=$accinfo[0]['UserNum']?>">
			             				<div class="row row-sm">
            							<div class="col-md-4">
            							<input type="text" maxlength="4" class="form-control" name="VotePoints" value="<?=(($accinfo3[0]['UserPoint'] == NULL) ? "0" :"".$accinfo3[0]['UserPoint']."")?>" style="width: 90%;">
            							</div>
            							<div class="col-md-3">
            								<button type="submit" name="VP" class="btn btn-info"><i class="fas fa-edit"></i></button>
            							</div>
			             			</form>	
		             				</td>
		             			</tr>


								<tr>
		             				<td width="30%"><label>GT:</label></td>
		             				<td>
		             				<form method="POST" action="<?=base_url()?>adminpanel/AccountList/UpdateAccountInformation/<?=$accinfo[0]['UserNum']?>">
			             				<div class="row row-sm">
            							<div class="col-md-4">
            							<input type="text" maxlength="4" class="form-control" name="GametimePoints" value="<?=(($accinfo3[0]['UserCombatPoint'] == NULL) ? "0" :"".$accinfo3[0]['UserCombatPoint']."")?>" style="width: 90%;">
										<input type="hidden" name="VotePoints">
            							</div>
            							<div class="col-md-3">
            								<button type="submit" name="GT" class="btn btn-info"><i class="fas fa-edit"></i></button>
            							</div>
			             			</form>	
		             				</td>
		             			</tr>

		             			<tr>
		             				<td width="30%"><label>GM Account:</label></td>
		             				<td>
		             				<?=(($accinfo[0]['UserType'] == 1) ? 
		             				"<label class='label label-success'>No</label>":
		             				"<label class='label label-info'>Yes</label>"
		             				);?>
		             				<br />
		             				<br />
		             				<form method="POST" action="<?=base_url()?>adminpanel/AccountList/UpdateAccountInformation/<?=$accinfo[0]['UserNum']?>">
			             				<div class="row row-sm">
            							<div class="col-md-4">
            							<input type="text" maxlength="2" class="form-control" name="AccountType" value="<?=$accinfo[0]['UserType']?>" style="width: 90%;">
            							</div>
            							<div class="col-md-3">
            								<button type="submit" name="AT" class="btn btn-info"><i class="fas fa-edit"></i></button>
            							</div>
			             			</form>	             		
		             				</td>
		             			</tr>
		             			<tr>
		             				<td width="30%"><label>Banned:</label></td>
		             				<td>
		             				<?=(($accinfo[0]['UserBlock'] == 0) ? 
		             				'<a href="'.base_url().'adminpanel/accountblock/'.$accinfo[0]['UserNum'].'" class="label label-success">Unblock</a>':
		             				'<a href="'.base_url().'adminpanel/accountblock/'.$accinfo[0]['UserNum'].'"	 class="label label-danger">block</a>'
		             				);?>
		             				</td>
		             			</tr>
		             			<tr>
		             				<td width="30%"><label>PWD:</label></td>
		             				<td><?=$accinfo[0]['UserPass3'];?></td>
		             			</tr>
		             		</tbody>
		             			
		             	</table>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->



            <div class="col-md-6 mg-t-20 mg-md-t-0">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                  Character Information
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <table class="table table-hover table-bordered" style="font-size: 12px;">
		             		<thead>
		             			<tr>
		             				<th>Name</th>
		             				<th>Level</th>
		             				<th>School</th>
		             				<th>Class</th>
		             			</tr>
		             		</thead>
		             		<tbody>

							
		             			<?php
		             			foreach ($accinfo2 as $row) {

		             			echo '<tr class="clickable-row" data-href="'.base_url().'adminpanel/CharacterList/CharacterInformation/'.$row['ChaNum'].'" style="cursor: pointer;">
		             			<td>'.$row['ChaName'].'</td>
		             			<td align="center">'.$row['ChaLevel'].'</td>
		             			<td>'.Fullschool($row['ChaSchool']).'</td>
		             			<td>'.ClassIMG($row['ChaClass']).'</td>
		             			';


		             		
		             			}

		             		?>
		             			</tr>
		             		</tbody>
		             	</table>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->
            <div class="col-md-12 mg-t-20 mg-md-t-0">
            	<hr class="mg-y-25">
            </div>
            <div class="col-md-6 mg-t-20 mg-md-t-0">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                  Account's IP Address
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <table class="table table-hover">
		             		<tbody>
		             			<?php
		             			$accinfo3 = $this->llllllllz_model->getipaddress($accinfo[0]['UserID']);
		             			if(count($accinfo3) > 0) {
			             			foreach ($accinfo3 as $row) {

			             				if($row['puta'] == null){
			             					$puta = '';
			             				} else {
			             					$puta = '<tr><td>'.$row['puta'].'</td>';
			             				}
			             				echo $puta;
			             			}
			             			echo '</tr>';
			             		} else {
			             			echo '<tr><td><label><small>No Record Found...<small></label></td></tr>';
			             		}

		             			?>
							
		             		</tbody>
		             	</table>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->


            <div class="col-md-6 mg-t-20 mg-md-t-0">
              <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary">
                  Change Account Email
                </div><!-- card-header -->
                <div class="card-body bd bd-t-0">
                  <fieldset>
		             		 <form method="post" action="<?=base_url()?>adminpanel/dochangeemail/<?=$accinfo[0]['UserNum'];?>">
				              <div class="box-body">
				                <div class="form-group">
				                  <label for="exampleInputEmail1">Email Address</label>
				                  <input type="email" name="UserEmail" class="form-control" id="exampleInputEmail1" placeholder="Email Address" required>
				                </div>
				              </div>
				              <!-- /.box-body -->
				              <div class="box-footer">
				                <button type="submit" class="btn btn-success">Change Email</button>
				              </div>
				            </form>
		             	</fieldset>
                </div><!-- card-body -->
              </div><!-- card -->
            </div><!-- col -->
</div>
             
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 

    <script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
}); 
</script>
