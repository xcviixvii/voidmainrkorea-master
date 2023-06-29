<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>



<div class="az-content-body">
      
<div class="az-dashboard-header-right" style="float:right;">
  <table>
              <tr>
              <td>
              <form method='post' action="<?=base_url()?>adminpanel/AccountList" >
              <div class="input-group">
                  <input type="text" class="form-control" name="srchusername" placeholder="">
                  <span class="input-group-btn">
                    <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
                  </span>
                </div><!-- input-group -->
               </form>
              </td>
             
                <td><a href="<?=base_url()?>adminpanel/clearsession/AccountList" class="btn btn-outline-success"><i class="fa fa-undo"></i></a></td>
              </tr>
  </table>  
</div>

<br /><br /><br />
      <div class="container">

        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
   
              <table id="example2" class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>#</th>
      <th>Username</th>
      <th>User Type</th>
      <th>Last Log IP</th>
      <th>Status</th>
    </tr>
    </thead>
    <tbody>
              <?php

              $ctr = 1;
                $getLastIP[0]['LogIpAddress'] = "";
                foreach ($records as $row) {
                 
                 $getLastIP = $this->llllllllz_model->getLastLogIP($row['UserID']);

                 if(count($getLastIP) <= 0) {
                    $putaka = "";
                 } else {
                    $putaka = $getLastIP[0]['LogIpAddress'];
                 }


                  if($row['Activation'] == 0) {
                    $status = '<span class="badge badge-danger">Not Verify</span>';
                  } else {
                    $status = '<span class="badge badge-success">Verified</span>';
                  }

                  if($row['UserBlock'] == 0) {
                      $status = '<span class="badge badge-success">Active</span>';
                  } else {
                    $status = '<span class="badge badge-danger">Blocked</span>';
                  }

                  echo '<tr class="clickable-row" data-href="'.base_url().'adminpanel/AccountList/AccountInformation/'.$row['UserNum'].'" style="cursor: pointer;">
                  <td>'.$row['UserNum'].'</td>
                  <td>'.$row['UserName'].'</td>
                  <td>'.usertype($row['UserType']).'</td>
                  <td>'.$putaka.'</td>
                  <td align="center">'.$status.'</td>
                  ';
                  $ctr++;
                }
              ?>
              </tr>
              </tbody>
              </table>
              <center><?=$pages?></center>
              

        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>

    <script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
}); 
</script>
