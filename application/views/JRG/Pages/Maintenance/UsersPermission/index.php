<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>



<div class="az-content-body">
  <div class="container maxheight" >
    <div class="az-content-body pd-lg-l-40 d-flex flex-column">
      <div class="table-responsive">
        <table class="table az-table-reference mg-t-0">
          <thead>
            <tr>
              <th width="10%">#</th>
              <th width="80%">UserType</th>
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
            $ctr = 1;
            foreach ($UserType as $row) {
              
              echo '<tr>
              <td>'.$ctr.'</td>
              <td>'.$row['UserTypeDesc'].'</td>
              <td><a href="'.base_url().'adminpanel/'.$this->uri->segment(2).'/'.$row['ID'].'"><i class="fas fa-folder-open"></i></a></td>
              </tr>';

              $ctr++;
            }
          ?>
          </tbody>
        </table>
      </div><!-- table-responsive -->

    </div><!-- az-content-body -->
  </div><!-- container -->
</div>
