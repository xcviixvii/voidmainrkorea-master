

<h2><img src="<?=base_url()?>Images/Leftnav/ranking_left_title.gif" alt="ranking"></h2>
<ul>
    <li><a href="/Ranking/Total.aspx"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Ranking/totalranking_nav_off.gif" alt="Server Ranking"></a></li>
    <li><a href="/rank/school.asp"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/Ranking/schoolranking_nav_off.gif" alt="Academy Ranking"></a></li>
    <li><a href="/rank/team.asp"><img id="sub-nav3" src="<?=base_url()?>Images/Leftnav/Ranking/classranking_nav_off.gif" alt="Department ranking"></a></li>    
  
</ul>

<aside id="nav" style="margin-top: 10px;">
                      
                        <?php $this->load->view('homepage/ServerInformation/index')?>

                        <section class="club-holder">
                            <table>
                                <caption class="display-block">
                                    <figure>
                                        <div class="title">
                                            <div class="left"><img src="<?=base_url()?>Images/Club/club_title.gif" alt="이벤트달력"></div>
                                            <div class="clear"></div>
                                        </div>
                                    </figure>
                                </caption> 
                                <thead>
                                    <tr class="blankline2">
                                    <td class="blankcell" colspan="13"></td>
                                    </tr>

                                    <tr>
                                        <td><img src="<?=base_url()?>Images/Club/club_area.gif" alt="area"></td>
                                        <td><img src="<?=base_url()?>Images/Club/club_name.gif" alt="name"></td>
                                        <td><img src="<?=base_url()?>Images/Club/club_rate.gif" alt="rate"></td>
                                    </tr>
                                </thead>
                                <tbody class="club-holder-list">
                                <?php
                            $ctr = 1;
                            $lead = "";
                            $tax = "";
                            $link = "";
                            foreach (Club() as $Club) {
                                        
                                
                                if(!$ClubLeader){
                                  
                                }else {
                                    $ClubName = $this->llllllllz_model->getClubName($ClubLeader[0]['GuNum']);
                                    //$GuNum = $this->encrypt->encode($ClubName[0]['GuNum']);
                                    $GuNum = $this->encrypt->encode($ClubName[0]['GuNum']);

                                    
                                    if($ctr == $ClubLeader[0]['RegionID']){
                                        $lead = $ClubName[0]['GuName'];
                                        $tax = $ClubLeader[0]['RegionTax'].'%';

                                        $link = '<a href="'.base_url().'/club/clubdetails/'.$GuNum.'" style="text-decoration:none;">'.$lead.'</a>';
                                    } else {
                                       
                                    }

                                }
                                
                                if($lead){
                                    echo '<tr>
                                          <td>'.$Club.'</td>
                                          <td>'.$link.'</td>
                                          <td>'.$tax.'</td>
                                          </tr>';
                                } 
                                
                            $ctr++;
                            }


                        ?> 
                            </tbody>
                            <tr class="blankline2">
                                <td class="blankcell" colspan="13"></td>
                            </tr>
                            </table>
                        </section> 
                    
                    </aside>

<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav<?=$this->uri->segment(3)?>').attr('src', $('#sub-nav<?=$this->uri->segment(3)?>').attr('src').replace('_off', '_on'));
        }

    });
</script>