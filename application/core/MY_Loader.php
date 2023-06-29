<?php

class MY_Loader extends CI_Loader {
    public function renderhomebodyview($template_name, $vars = array(), $return = FALSE){
        if($return):
        $content  = $this->view('HomePage/Templates/tmphead', $vars, $return);
      //  $content .= $this->view('HomePage/Templates/tmpheader', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('HomePage/Templates/tmpfooter', $vars, $return);
        return $content;
    else:
        $this->view('HomePage/Templates/tmphead', $vars);
       // $this->view('HomePage/Templates/tmpheader', $vars);
        $this->view($template_name, $vars);
        $this->view('HomePage/Templates/tmpfooter', $vars);
    endif;
    }


    public function renderjrgbodyview($template_name, $vars = array(), $return = FALSE){
        if($return):
        $content  = $this->view('JRG/Templates/tmpheader', $vars, $return);
        $content  = $this->view('JRG/Templates/tmpsidebar', $vars, $return);
        $content  = $this->view('JRG/Templates/tmpnavlinks', $vars, $return);
        $content  = $this->view('JRG/Templates/tmpbreadcrumb', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('JRG/Templates/tmpfooter', $vars, $return);
        return $content;
    else:
        $this->view('JRG/Templates/tmpheader', $vars);
        $content  = $this->view('JRG/Templates/tmpsidebar', $vars, $return);
        $content  = $this->view('JRG/Templates/tmpnavlinks', $vars, $return);
        $content  = $this->view('JRG/Templates/tmpbreadcrumb', $vars, $return);
        $this->view($template_name, $vars);
        $this->view('JRG/Templates/tmpfooter', $vars);
    endif;
    }
}
 