<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <div class="row">
                <div class="col-md-8">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover mg-b-10">
                      <thead>
                        <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Name</th>
                        <th>User Type</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody style="font-size: 11px;">
                        <?php
                            foreach ($Accounts as $row) {
                                echo '<tr>
                                <td>'.$row['UserName'].'</td>
                                <td>'.$row['UserPass'].'</td>
                                <td>'.$row['UserFName'].' '.$row['UserLName'].'</td>
                                <td>'.$row['UserTypeID'].'</td>
                                <td></td>
                                </tr>';
                            }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="col-md-4">
                   <form method="POST" data-parsley-validate>
                    <div class="pd-30 pd-sm-40 bg-gray-200 bd bd-2">

                        <div class="form-group">
                          <label class="form-label">Username:</label>
                          <input type="text" name="Username" class="form-control wd-250" placeholder="Username Link" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">Password:</label>
                          <input type="password" name="Password" class="form-control wd-250" placeholder="password Link" required>
                        </div><!-- form-group -->


                        <div class="form-group">
                          <label class="form-label">First Name:</label>
                          <input type="text" name="FName" class="form-control wd-250" placeholder="First Name" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">Last Name:</label>
                          <input type="text" name="LName" class="form-control wd-250" placeholder="Last Name" required>
                        </div><!-- form-group -->

                        <div class="form-group">
                          <label class="form-label">User Type:</label>
                          <div id="slWrapper" class="parsley-select wd-sm-250">
                            <select class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" name="UserType" required>
                              <option label="User Type"></option>
                            </select>
                            <div id="slErrorContainer"></div>
                          </div>
                        </div><!-- form-group -->

                        <br />
                      <button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
                    </div>
                  </form>

                </div>
              </div>

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 
