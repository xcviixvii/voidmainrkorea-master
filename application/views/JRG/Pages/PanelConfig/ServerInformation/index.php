<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>

<div class="az-content-body">
      <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <form method="POST">
             <div class="col-sm-3 col-md-2 mg-b-10" style="float:right;">
                <button type="submit" class="btn btn-primary btn-block btn-sm">Save</button>
            </div>
            <table class="table table-bordered table-hover mg-b-10" style="font-size:12px;">
                <tbody>
                   <tr>
                        <th style="vertical-align:middle;" width="12%">
                            <small>Category</small>
                            <select class="form-control select2-no-search" name="Category" id="Category" require>
                                <option value=""></option>
                                <option value="1">Details</option>
                                <option value="2">Wars</option>
                            </select>
                        </th>

                        <th style="vertical-align:middle;" width="15%">
                            <small>Name</small>
                            <input type="text" name="Name" class="form-control" require>
                            <small></small>
                        </th>

                        <td style="vertical-align:middle;">
                            <div id="Detail" style="display:none;">
                                <small>Detail</small>
                                <input name="Detail" type="text" class="form-control" >
                            </div>
                            
                            <div id="War" style="display:none; width:100%;">
                            <small>War Time</small>
                            <select name="WarTime[]" class="select2" style="width:100%" multiple >
                            <option label="Select time">Select time</option>
                            <option value="00:00">00:00</option>
                            <option value="00:30">00:30</option>
                            <option value="01:00">01:00</option>
                            <option value="01:30">01:30</option>
                            <option value="02:00">02:00</option>
                            <option value="02:30">02:30</option>
                            <option value="03:00">03:00</option>
                            <option value="03:30">03:30</option>
                            <option value="04:00">04:00</option>
                            <option value="04:30">04:30</option>
                            <option value="05:00">05:00</option>
                            <option value="05:30">05:30</option>
                            <option value="06:00">06:00</option>
                            <option value="06:30">06:30</option>
                            <option value="07:00">07:00</option>
                            <option value="07:30">07:30</option>
                            <option value="08:00">08:00</option>
                            <option value="08:30">08:30</option>
                            <option value="09:00">09:00</option>
                            <option value="09:30">09:30</option>
                            <option value="10:00">10:00</option>
                            <option value="10:30">10:30</option>
                            <option value="11:00">11:00</option>
                            <option value="11:30">11:30</option>
                            <option value="12:00">12:00</option>
                            <option value="12:30">12:30</option>
                            <option value="13:00">13:00</option>
                            <option value="13:30">13:30</option>
                            <option value="14:00">14:00</option>
                            <option value="14:30">14:30</option>
                            <option value="15:00">15:00</option>
                            <option value="15:30">15:30</option>
                            <option value="16:00">16:00</option>
                            <option value="16:30">16:30</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                            <option value="18:00">18:00</option>
                            <option value="18:30">18:30</option>
                            <option value="19:00">19:00</option>
                            <option value="19:30">19:30</option>
                            <option value="20:00">20:00</option>
                            <option value="20:30">20:30</option>
                            <option value="21:00">21:00</option>
                            <option value="21:30">21:30</option>
                            <option value="22:00">22:00</option>
                            <option value="22:30">22:30</option>
                            <option value="23:00">23:00</option>
                            <option value="23:30">23:30</option>
                            </select>
                            </div>
                        </td>

                        <td style="vertical-align:middle;" width="30%">
                            <div id="War2" style="display:none;">
                            <small>Day<br />
                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="0">
                                <span>Sun</span> 
                            </label>
                            </div>


                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="1">
                                <span>Mon</span> 
                            </label>
                            </div>

                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="2">
                                <span>Tue</span> 
                            </label>
                            </div>

                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="3">
                                <span>Wed</span> 
                            </label>
                            </div>

                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="4">
                                <span>Thu</span> 
                            </label>
                            </div>

                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="5">
                                <span>Fri</span> 
                            </label>
                            </div>

                            <div style="float:left;margin:5px;">
                            <label class="ckbox">
                                <input type="checkbox" name="Day[]" value="6">
                                <span>Sat</span> 
                            </label>
                            </div>

                            </small>
                            </div>
                        </td>

                        <td width="20%" style="vertical-align:middle;">
                            <div id="Schedule" style="display:none;">
                                    <small>Schedule</small>
                                    <input name="Schedule" type="text" class="form-control" >
                            </div>
                        </td>
                    </tr>

                </tbody>
                <tbody>
                    <?php
                
                        foreach ($ServerInfo as $row) {
                            $Dis = explode(",",$row['WarDay']);
                            
                            $FDay = '';
                            foreach ($Dis as $Day) {
                                $FDay .= CDay($Day).' ';
                            }

                            echo '
                            <tr>
                                <td><a href="'.base_url().'adminpanel/deleteserverinfo/'.$row['ServerInfoID'].'"><i class="fas fa-trash-alt"></i></a> '.(($row['Category'] == 1) ? "Details":"Wars").'</td>
                                <td>'.$row['Name'].'</td>
                                <td>'.(($row['Category'] == 1) ? "".$row['Detail']."":"".$row['WarTime']."").'</td>
                                <td>'.(($row['Category'] == 1) ? "":"".$FDay."").'</td>
                                <td>'.$row['Schedule'].'</td>
                            </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
           
        </form>
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>


    <script>
    $(document).ready(function () {
        $('#Category').on('change', function() {
            let CatVal = this.value;

            if(CatVal == 1){
                document.getElementById("Detail").style.display = "";
                document.getElementById("War").style.display = "none";
                document.getElementById("War2").style.display = "none";
                document.getElementById("Schedule").style.display = "none";
            } else if (CatVal == 2){
                document.getElementById("Detail").style.display = "none";
                document.getElementById("War").style.display = "";
                document.getElementById("War2").style.display = "";
                document.getElementById("Schedule").style.display = "";
            } else {
                document.getElementById("Detail").style.display = "none";
                document.getElementById("War").style.display = "none";
                document.getElementById("War2").style.display = "none";
                document.getElementById("Schedule").style.display = "none";
            }
            
        });
    });
</script>

 <script>
      $(function(){
        'use strict'

        $('.select2').select2({
        });

      });

    </script>
