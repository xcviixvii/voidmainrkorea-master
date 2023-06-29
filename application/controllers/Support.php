<?php

class Support extends CI_Controller {

    function __construct() {
    parent::__construct();
    $this->ci_minifier->init(2);
    if(!$this->session->userdata('UserID')){
            NotifyError('please try again after logging in..');
            ?>
		 	<script> 
		 		self.close();
				location.href = '<?=base_url()?>/Login';
		 	</script>
		 	<?php
		}
    }
    
	function index() {
        if($_POST){
            $data = array(
                'TicketNumber'=> GenTicketNum(),
                'TicketTitle'=> $_POST['TicketTitle'],
                'TicketContent'=> $_POST['content'],
                'TicketStatus'=>0,
                'TicketDateTime'=>currentdatetime(),
                'UserNum'=>$this->session->userdata('UserID')
            );

            $this->Internal_model->InsertTicket($data);
            ANotifySuccess('Successfully Submit a Ticket');
            RDirect('Support');
        }
        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/Support/index',$data);
    }
    

    function ticket($TicketNum=""){
        $UserNum = $this->session->userdata('UserID');

        if($TicketNum){
            if($_POST){
                $data = array(
                    'TicketNumber'  => $_POST['TicketNumber'],  
                    'Value'         => $_POST['TicketReply'],
                    'UserID'        => $UserNum,
                    'DateTimeStamp' => currentdatetime()
                );

                $this->Internal_model->InsertReplyTicket($data);
                RDirect('Support/Ticket/'.$_POST['TicketNumber'].'');
            }


            $data['Tickets'] = $this->Internal_model->GetTicket($TicketNum,$UserNum);

            $data['GSet'] = $this->Internal_model->GetPanelSettings();
            $data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/Support/Ticket',$data);
        } else {
            $data['MyTicket'] = $this->Internal_model->GetAllMyTicket($UserNum);
            $data['GSet'] = $this->Internal_model->GetPanelSettings();
            $data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/Support/MyTicket',$data);
        }
        
    }


    function DeleteTicket($TicketNum){
        ANotifySuccess("Ticket Successfully Deleted");
        $this->Internal_model->DeleteTicket($TicketNum);
        RDirect('Support/Ticket');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */