
<?php
function build_conquerorspath($month,$year,$dateArray) {
       $ci =& get_instance();
  // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');
     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?


    //  $numberDays = date('t',$firstDayOfMonth);
     
    // Retrieve some information about the first day of the
     // month in question.

     $dateComponents = getdate($firstDayOfMonth);
     // What is the name of the month in question?
     $monthName = $dateComponents['month'];
     $NewMonth = $dateComponents['mon'];
     $NewYear = $dateComponents['year'];

     $numberDays = $ci->Internal_model->GetCPMonthNumberDays($NewMonth,$NewYear);
     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers
     $calendar = "<section class='conqueror' style='float:left;margin:10px;'><table  id='conqueror'>";
     $calendar .= '<caption class="display-block">
                                <figure>
                                    <div class="yearmonth">
                                        <div class="left"></div>
                                        <div class="right">
                                            <img src="'.base_url().'Images/Calendar/month_prev.gif" id="prevcalendar" class="pointer" alt="다음달">
                                            &nbsp;<span id="yearmonth">'.$NewYear.'.'.$NewMonth.'</span>&nbsp;
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

          $GuNum = $ci->Internal_model->GetCPWinner($date);
          $GBadge = $ci->Internal_model->GenerateGuildBadge($GuNum);


          

          if($GuNum != 0) {
            $calendar .= '<td class="blank"></td>';
            $calendar .= '<td style="cursor: default;" val="'.$date.'" title="'.$ci->Internal_model->GenerateGuildName($GuNum).'">';

            if(bin2hex($GBadge)!="00"){
                            $line = 0;
                            $calendar .= "<div style='margin-left: 5px;' class='rTable' border='0' cellpadding='0' cellspacing='0' width='16' height='11'>";
                            for( $m = 0 ; $m < 11 ; $m ++ )
                            {
                                $calendar .= '<div class="rTableRow">';
                                for( $n = 0 ; $n < 16 ; $n ++ )
                                {
                                    $offset = $line*8*16 + $n*8;
                                    $color = substr( bin2hex($GBadge) , $offset + 4 , 2 ) . substr( bin2hex($GBadge) , $offset + 2 , 2 ) . substr( bin2hex($GBadge) , $offset , 2 );
                                    $calendar .= "<div class='rTableCell' style='width:1px;height:1px;background-color:#$color'></div>";
                                }
                                $calendar .= "</div>";
                                $line++;
                            }
                            $calendar .= "</div>";
                            }

            $calendar .= '</td>';
          } else {
            $calendar .= '<td class="blank"></td><td val="'.$date.'" style="cursor: default;">'.$currentDay.'</td>';  
          }

          $currentDay++;
          $dayOfWeek++;
          // Increment counters

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
     $calendar .= "</table></section>";
     return $calendar;
}
?>

<?php
     $dateComponents = getdate();

    //  echo $dateComponents['mon'];


     $month = date("n", strtotime($this->Internal_model->GetFirstDayOfConquerorPath()));
     $year = date("Y", strtotime($this->Internal_model->GetFirstDayOfConquerorPath()));

     echo build_conquerorspath($month,$year,$dateComponents);
?>