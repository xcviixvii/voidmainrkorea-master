<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | <?=ucfirst($this->uri->segment(1))?></title>
<style>
.ellipsis {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    
}
</style>
<body  class="sub-body">
<div id="dialog" style='display:none; text-align:center; overflow:hidden;' >
    <iframe id="myIframe" src="/Popup/popup_pw_change.html" style="width:390px; height:270px; border:0"></iframe>
</div>
    <div class="in-body6">
        <div class="bottom-body">
            <div id="wrapper">
                <?php
                renderview('HomePage/Templates/tmpheader');
                ?>
                <div id="container">
                    <nav id="nav">                
                        <section id="left-nav">&nbsp;</section>
                    
                    </nav>
                    <section id="content">
                        
    <section class="page-history">
		HOME
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
        SUPPORT
        <img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">TICKET</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/Support/support_sub_title.gif" alt="Support" class="left">
	</h3>
	<div class="clear"></div>
    <table class="ranking" style="font-size:11px;">
    
	<caption>Ticket List</caption>
	<colgroup>
		<col style="width:80px;">
        <col style="width:170px;">
        <col style="width:50px;">
		<col style="width:60px;">
        <col style="width:80px;">
        <col style="width:40px;">
	</colgroup>
	<thead>
	<tr>
	
        <th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Ticket #</label></th>
        <th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Title</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Reply</label></th>
        <th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Status</label></th>
        <th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Date / time</label></th>
        <th scope="col" class="last" style="border-right:1px solid black;"><label style="font-size: 12px; color:white; font-weight: normal;"></label></th>
	</tr>
	</thead>
	<tbody>
    <?php
    if(count($MyTicket) <= 0){
       echo '<tr>
       <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" colspan="6">NO RECORD FOUND...</td>
       </tr>'; 
    } else {
        foreach ($MyTicket as $row) {
            echo '<tr>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;"><a href="'.base_url().'Support/Ticket/'.$row['TicketNumber'].'">'.$row['TicketNumber'].'</a></td>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">'.$row['TicketTitle'].'</td>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="reply-count">'.$row['TicketStatus'].'</td>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">'.TicketStatus($row['TicketStatus']).'</td>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">'.formatdatetime($row['TicketDateTime']).'</td>
                    <td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">
                    <a href="'.base_url().'Support/DeleteTicket/'.$row['TicketNumber'].'" class="dodgerblue"><i class="fad fa-trash" style="font-size:16px;"></i></a>
                    </td>
                </tr>';
        }
    }
    
    ?>
    </tbody>
    </tr>

    </tbody>
	</table>


                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
