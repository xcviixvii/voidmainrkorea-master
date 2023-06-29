<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?></title>

 <link rel="stylesheet" href="<?=base_url()?>Library/css/jquery-ui.css">
  <script src="<?=base_url()?>Library/js/jquery-ui.js"></script>
  <script>
  $( function() {
    $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
      _renderItem: function( ul, item ) {
        var li = $( "<li>" ),
          wrapper = $( "<div>", { text: item.label } );
 
        if ( item.disabled ) {
          li.addClass( "ui-state-disabled" );
        }
 
        $( "<span>", {
          style: item.element.attr( "data-style" ),
          "class": "ui-icon " + item.element.attr( "data-class" )
        })
          .appendTo( wrapper );
 
        return li.append( wrapper ).appendTo( ul );
      }
    });
 
    $( "#filesA" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons" );
 
    $( "#filesB" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
 
    $( "#people" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget")
        .addClass( "ui-menu-icons avatar" );
    $( "#classimg" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget")
        .addClass( "ui-menu-icons avatar" );
  } );
  </script>

  <style>
    
  </style>
<?php
//GET Username
$uname = $this->llllllllz_model->GetUserName($ChaInfo[0]['UserNum']);
//var_dump($uname);
?>


<div class="az-content-body">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <div class="row row-sm mg-b-20 mg-lg-b-0">
        
          <div class="col-md-6 col-xl-7">
          <form method="POST">
            <div class="card card-dashboard-six">
            <div class="card-header">
            <div>
              <h6 class="card-title" style="font-weight: 800">USERNAME : <a href="<?=base_url()?>adminpanel/AccountList/AccountInformation/<?=$ChaInfo[0]['UserNum']?>"><?=$uname[0]['UserName']?></a></h6>
              <h6 class="card-title" style="font-weight: 800">ACCOUNT STATUS : <?=(($this->Internal_model->CheckCharOnline($ChaInfo[0]['UserNum']) == 1) ? "<small><i class='fa fa-circle text-success'> </i></small><b style='color:green'> Online</b>":"<small><i class='fa fa-circle' style='color:#d2d6de;'></i></small> <b style='color:red'>Offline</b>")?></h6>
            </div>
            <div class="chart-legend">
                  <h6 class="card-title" style="font-weight: 800">CHANUM : <a style="color: #3366ff"><?=$ChaInfo[0]['ChaNum']?></a></h6>
            </div>
            </div>
              <div class="table-responsive">
                  <table class="table table-dashboard-two mg-b-0">
                    <tbody>
                      <tr>
                        <td style="vertical-align: middle;"><label>Character Name : </label></td>
                        <td class="tx-right tx-medium tx-inverse">
                        	<input type="text" name="chaname" class="form-control" value="<?=$ChaInfo[0]['ChaName']?>" maxlength="20">
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: middle;"><label>Level : </label></td>
                        <td class="tx-right tx-medium tx-inverse">
                        	<input type="text" name="chalevel" class="form-control" value="<?=$ChaInfo[0]['ChaLevel']?>">
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: middle;"><label>Inventory Line : </label></td>
                        <td class="tx-right tx-medium tx-inverse">
                        	<input type="text" name="chainvenline" class="form-control" value="<?=$ChaInfo[0]['ChaInvenLine']?>">
                        </td>
                      </tr>
                      <tr>
                        <td style="vertical-align: middle;">
                        <div class="row row-xs align-items-center mg-b-20">
			              <div class="col-md-4">
			                <label class="form-label mg-b-0">PK Win:</label>
			              </div><!-- col -->
			              <div class="col-md-8">
			                <input type="text" class="form-control wd-160" name="ChaPkWin" style="text-align: center;" value="<?=$ChaInfo[0]['ChaPkWin']?>">
			              </div><!-- col -->
			            </div>
			        	</td>
                        <td>
                        <div class="row row-xs align-items-center mg-b-20" style="font-size: 14px; font-weight: normal;">
                          <div class="col-md-4">
                            <label class="form-label mg-b-0">PK Loss:</label>
                          </div><!-- col -->
                          <div class="col-md-8">
                            <input type="text" name="ChaPkLoss" class="form-control wd-160" style="text-align: center;" value="<?=$ChaInfo[0]['ChaPkLoss']?>">
                          </div><!-- col -->
                        </div>
                        </td>
                      </tr>
                      
                      <tr>
                        <td>
                        <div class="row row-xs align-items-center mg-b-20" style="font-size: 14px; font-weight: normal;">
                          <div class="col-md-4">
                            <label>Stat Points:</label>
                          </div><!-- col -->
                          <div class="col-md-8">
                            <input type="text" name="ChaStRemain" class="form-control" value="<?=$ChaInfo[0]['ChaStRemain']?>">
                          </div><!-- col -->
                        </div>
                        </td>
                        <td>
                        <div class="row row-xs align-items-center mg-b-20" style="font-size: 14px; font-weight: normal;">
                          <div class="col-md-4">
                            <label>Skill Point:</label>
                          </div><!-- col -->
                          <div class="col-md-8">
                            <input type="text" name="ChaSkillPoint" class="form-control" value="<?=$ChaInfo[0]['ChaSkillPoint']?>">
                          </div><!-- col -->
                        </div>
                        </td>
                       
                      </tr>

                      <tr>
                        <td><label>Character Money:</label></td>
                        <td><input type="text" name="ChaMoney" class="form-control" value="<?=$ChaInfo[0]['ChaMoney']?>"></td>
                      </tr>
                      
                      <tr>
                      	<td></td>
                      	<td></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
            </div><!-- card-dashboard-five -->
          </div>


          <div class="col-md-6 col-xl-5 mg-t-20 mg-md-t-0">
            <div class="card card-dashboard-eight">
              <h6 class="card-title"><?=CharSchool($ChaInfo[0]['ChaSchool'])?></h6>
              <span class="d-block mg-b-20"></span>

              <div class="list-group">
                <div class="list-group-item">

                  
                  <label>Character School:</label>
                  <span>
                    <select  name="ChaSchool" id="people">
                      <?php
                      foreach (Schools() as $row) {
                        echo '<option value="'.$row.'" '.(($row == $ChaInfo[0]['ChaSchool']) ? "Selected":"").'  data-class="avatar" data-style="background-image: url('.getselectschool($row).');">'.Fullschool($row).'</option>';
                      }
                      ?>
                    </select>
                  </span>
                  
                </div><!-- list-group-item -->
               
               <div class="list-group-item">

                  <label><canvas id="canvas1" height="150" width="150"></canvas></label>
                  <span>
                  Pow:<input type="text" name="ChaPower" class="form-control" value="<?=$ChaInfo[0]['ChaPower']?>" width="10%">
		       			  Dex:<input type="text" name="ChaDex" class="form-control" value="<?=$ChaInfo[0]['ChaDex']?>" width="10%">
		       				Int:<input type="text" name="ChaSpirit" class="form-control" value="<?=$ChaInfo[0]['ChaSpirit']?>" width="10%">
		       				Stam:<input type="text" name="ChaStrength" class="form-control" value="<?=$ChaInfo[0]['ChaStrength']?>" width="10%">
		       				Vit:<input type="text" name="ChaStrong" class="form-control" value="<?=$ChaInfo[0]['ChaStrong']?>" width="10%">
                  </span>
                  
                </div><!-- list-group-item -->

              </div><!-- list-group -->
            
            </div><!-- card -->
          </div><!-- col -->
          <div class="col-md-12">
            <br />
          </div>

          <!-- INVENTORY WINDOW -->
          <div class="col-md-5 col-xl-4 mg-t-20 mg-md-t-0">
            <div class="card card-dashboard-eight">
            <h6 class="card-title">INVENTORY</h6>
            <span class="d-block mg-b-20"></span>
            <?php
            $this->session->set_userdata('ChaInven', bin2hex($ChaInfo[0]['ChaInven']));
            echo "<div class='locker_inventory lfloat'>";
            $strval = "";
            for ($y =0; $y < 10; $y++){
                for ($x = 0; $x < 6; $x++){
                    $val = 'x'.$x.'y'.$y;
                    echo FindItem($val);
                }
                echo '<div class="clearfix"></div>';
            }
            echo '</div>';
            ?>
            </div>
          </div>


          <div class="col-md-12">
          <br />
          <button type="submit" class="btn btn-success" style="float:right;">SAVE</button>
          </div>
        </div>
        
        </form>
              

        </div>
    </div><!-- container -->
</div><!-- az-content-body --> 



	<script>
	var radarChartData1 = {
		labels: ["Pow", "Dex", "Int", "Stm", "Vit"],
		datasets: [
			{
				label: "<?=$ChaInfo[0]['ChaName'];?>",
				fillColor: "rgba(151,187,205,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(151,187,205,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(151,187,205,1)",
				data: [<?=$ChaInfo[0]['ChaPower'];?>,<?=$ChaInfo[0]['ChaDex'];?>,<?=$ChaInfo[0]['ChaSpirit'];?>,<?=$ChaInfo[0]['ChaStrength'];?>,<?=$ChaInfo[0]['ChaStrong'];?>]
				
			}
		]
	};
	
		//Get the context of the Radar Chart canvas element we want to select
	var ctx1 = document.getElementById("canvas1").getContext("2d");

	// Create the Radar Chart
	var myradarChart2 = new Chart(ctx1).Radar(radarChartData1, { responsive: false });
	</script>

