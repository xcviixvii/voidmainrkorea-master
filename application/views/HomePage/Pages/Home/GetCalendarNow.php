<?php
function build_calendar($month,$year,$dateArray) {
       $ci =& get_instance();
  // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');
     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);
     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);
     // What is the name of the month in question?
     $monthName = $dateComponents['month'];
     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];
     // Create the table tag opener and day headers
     $calendar = "<table id='calendar'>";
     $calendar .= '<caption class="display-block">
                                <figure>
                                    <div class="yearmonth">
                                        <div class="left"><img src="'.base_url().'Images/Calendar/calendar_title.gif" alt="이벤트달력"></div>
                                        <div class="right">
                                            <img src="'.base_url().'Images/Calendar/month_prev.gif" id="prevcalendar" class="pointer" alt="다음달">
                                            &nbsp;<span id="yearmonth">'. date('Y').'.'.date('n').'</span>&nbsp;
                                            <img src="'.base_url().'Images/Calendar/month_next.gif" id="nextcalendar" class="pointer" alt="이전달">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </figure>
                            </caption>';
     $calendar .= '<thead><tr class="blankline2">
                                <td class="blankcell" colspan="13"></td>
                            </tr><tr>';
     // Create the calendar headers
     foreach($daysOfWeek as $day) {
          $calendar .= '<th class="blank"></th><th>'.$day.'</th>';
     } 
     // Create the rest of the calendar
     // Initiate the day counter, starting with the 1st.
     $currentDay = 1;
     $calendar .= '</tr></thead><tbody><tr><tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr>';
     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.
     if ($dayOfWeek > 0) { 
          $asd = ($dayOfWeek * 2) - 1;
          $calendar .= '<td class="blank" colspan="'.$asd.'"></td><td class="">&nbsp;</td>'; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);

     
     //echo $noeve;
     

     while ($currentDay <= $numberDays) {
          // Seventh column (Saturday) reached. Start a new row.
          if ($dayOfWeek == 7) {
               $dayOfWeek = 0;
               $calendar .= '<tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr></tr><tr>';
          }
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          $date = "$year-$month-$currentDayRel";
          $data = $ci->llllllllz_model->getevents($date);
          $datelist = "";//datebetween('2019-07-03','2019-08-31');
          foreach ($data as $row) {
              $datelist = datebetween(''.$row['evefrom'].'',''.$row['eveto'].'');

            }  

          //$datelist = datebetween(''.formatdate4($row['evefrom']).'',''.formatdate4($row['eveto']).'');
          
           if($datelist){
                $noeve = array();
                foreach ($datelist as $dtelist) {
                     $noeve[] .= formatdate4($dtelist);
                }
           } else {
                $noeve = array();
           }
          

          //in_array($currentDay,$noeve))
          if(in_array($date, $noeve)){
            if(date('m') == $month && date('Y') == $year){
                    if(date('d') == $currentDay) {
                        $calendar .= '<td class="blank"></td><td class="Event-Now" val="'.$date.'" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
                      } else {
                        $calendar .= '<td class="blank"></td><td class="Event" val="'.$date.'" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
                      }
               } else {
                   $calendar .= '<td class="blank"></td><td class="Event" val="'.$date.'" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
               }
          } else {
            $calendar .= '<td class="blank"></td><td val="'.$date.'" style="cursor: default;">'.$currentDay.'</td>';  
          }
        //$calendar .= '<td class="blank"></td><td class="Event-now" val="'.$date.'" isevent="True" style="cursor: pointer;">'.$currentDay.'</td>';
          
          // Increment counters

          $currentDay++;
          $dayOfWeek++;
     }
     
     
     // Complete the row of the last week in month, if necessary
     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= '<td class="blank"><td class="">&nbsp;</td>'; 
     }
     
     $calendar .= '</tr><tr class="blankline">
                                <td class="blankcell" colspan="13"></td>
                                </tr></tbody>';
     $calendar .= '<tfoot>
                            <tr class="blankline2">
                                <td class="blankcell" colspan="13"></td>
                            </tr>
                            </tfoot>';
     $calendar .= "</table>";
     return $calendar;
}
?>

<?php
     $dateComponents = getdate();
     $month = $dateComponents['mon'];                  
     $year = $dateComponents['year'];
     echo build_calendar($month,$year,$dateComponents);
?>