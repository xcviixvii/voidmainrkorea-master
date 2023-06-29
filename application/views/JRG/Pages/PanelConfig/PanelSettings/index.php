<title><?=$this->session->userdata('GameName')?> | <?=$this->Internal_model->ModuleDesc($this->uri->segment(2))?>
</title>

<?php
if(count($Set) > 0){
    $WebStat = $Set[0]['WebsiteStatus'];
    $Copyright = $Set[0]['Copyright'];
    $ServerName = $Set[0]['ServerName'];
    $ServerTitle = $Set[0]['ServerTitle'];

    $ReCaptcha = $Set[0]['ReCaptcha'];
    $ReCaptchaSiteKey = $Set[0]['ReCaptchaSiteKey'];
    $ReCaptchaSecretKey = $Set[0]['ReCaptchaSecretKey'];
    $MD5 = $Set[0]['HashMD5'];
    $EmailVerification = $Set[0]['EmailVerification'];
    $Email = $Set[0]['Email'];
    $EmailPass = $Set[0]['EmailPass'];
    $EmailName = $Set[0]['EmailName'];
    $EmailSubject = $Set[0]['EmailSubject'];
    $FacebookScript = $Set[0]['FacebookScript'];
    $FacebookPage = $Set[0]['FacebookPage'];
    $FacebookGroup = $Set[0]['FacebookGroup'];
    $YoutubeChannel = $Set[0]['YoutubeChannel'];
    $MediaAds = $Set[0]['MediaAds'];
    $ServerStatus = $Set[0]['ServerStatus'];
} else {
    $WebStat = 0;
    $Copyright = '';
    $ServerName = '';
    $ServerTitle = '';

    $ReCaptcha = '';
    $ReCaptchaSiteKey = '';
    $ReCaptchaSecretKey = '';
    $MD5 = '';
    $EmailVerification = '';
    $Email = '';
    $EmailPass = '';
    $EmailName = '';
    $EmailSubject = '';
    $FacebookScript = '';
    $FacebookPage = '';
    $FacebookGroup = '';
    $YoutubeChannel = '';
    $MediaAds = '';
    $ServerStatus = 0;

}
?>
<div class="az-content-body">
      <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
        <form method="POST">
        
            <table class="table table-bordered table-hover mg-b-10">
                <tbody>
                    <tr>
                        <th width="50%">
                            <label>Server Maintenance</label> <br />
                            <small>Enable/Disable Your Website. if disabled. visitors will be redirected to the maintenance page</small>
                        </th>
                        <td style="vertical-align:middle;">
                            <div class="WebStatus az-toggle <?=(($WebStat == 1) ? "on":"")?>"><span></span></div> <!-- INSERT ON -->
                            <input type="hidden" id="WebStat" name="WebStat" value="<?=(($WebStat == 1) ? 1:0)?>">
                        </td>
                    </tr>

                    <tr>
                        <th width="50%">
                            <label>Favicon</label> <br />
                            <small>Upload your prefer Icon for your Website 16x16 /.ico Format</small>
                        </th>

                        <td align="left" width="50%">
                        <!-- <input type="file" class="form-control wd-250" name="favicon"> -->
                        </td>
                    </tr>

                    <!-- <tr>
                        <th width="50%">
                            <label>Recaptcha</label> <br />
                            <small>Enable/Disable Recaptcha on your Website <br /> 
                        </th>

                        <td style="vertical-align:middle;"> <div class="az-toggle on"><span></span></div> 
                        </td>
                    </tr> -->

                    <tr>
                        <th>
                        <label>Server Name</label><br />
                        <small>Your Ran Online Server Name</small>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="ServerName" class="form-control input-sm" value='<?=$ServerName;?>'>
                        </td>
                        </tr>

                        <tr>
                        <th>
                        <label>Server Title</label><br />
                        <small>Your Server Title</small>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="ServerTitle" class="form-control input-sm" value='<?=$ServerTitle;?>'>
                        </td>
                        </tr>

                    <tr>
                        <th>
                        <label>Copyright</label><br />
                        <small>Your Copyright at the bottom of the page</small>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="Copyright" class="form-control wd-250 input-sm" value='<?=$Copyright?>'>
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label>Facebook Page</label>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="FacebookPage" class="form-control wd-250 input-sm" value='<?=$FacebookPage?>'>
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label>Facebook Group</label>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="FacebookGroup" class="form-control wd-250 input-sm" value='<?=$FacebookGroup?>'>
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label>Youtube Channel</label>
                        </th>
                        <td style="vertical-align:middle;">
                            <input type="text" name="YoutubeChannel" class="form-control wd-250 input-sm" value='<?=$YoutubeChannel?>'>
                        </td>
                    </tr>

                    <tr>
                        <th>
                        <label>Facebook Script</label>
                        <br/>
                        <small>Setup your Messenger Platform <a target="_blank" href="https://www.facebook.com/">https://www.facebook.com/</a>
                        </th>
                        <td style="vertical-align:middle;">
                        <textarea type="text" style="width: 450px; height: 250px; resize: none;" cols="20" name="FacebookScript" class="form-control input-sm"><?=$FacebookScript?></textarea>
                        
                        </td>
                    </tr>


                    <tr>
                        <th>
                        <label>MediaAds</label>
                        <br/>
                        <small>Make Sure the video is Embeded </br> Frame Size : width="220" height="140" </small>
                        </th>
                        <td style="vertical-align:middle;">
                        <textarea type="text" style="width: 450px; height: 250px; resize: none;" cols="20" name="MediaAds" class="form-control input-sm"><?=$MediaAds?></textarea>
                        
                        </td>
                    </tr>

                    <tr>
                        <th width="50%">
                            <label>Server Status</label> <br />
                            <small>Enable/Disable to Notice the Player if Your Server is Online or Offline</small>
                        </th>
                        <td style="vertical-align:middle;">
                            <div class="ServerStatus az-toggle <?=(($ServerStatus == 1) ? "on":"")?>"><span></span></div> <!-- INSERT ON -->
                            <input type="hidden" id="ServerStatus" name="ServerStatus" value="<?=(($ServerStatus == 1) ? 1:0)?>">
                        </td>
                    </tr>

                </tbody>
            </table>
            <div class="col-sm-3 col-md-2 mg-b-10" style="float:right;">
                <button type="submit" class="btn btn-primary btn-block btn-sm">Save</button>
            </div>
        </form>
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>

    <script>
    $('.WebStatus').on('click', function () {
        $(this).toggleClass('on');
        if ($(this).hasClass('on')) {
            $("#WebStat").val('1');
        } else {
            $("#WebStat").val('0');
        }
        
    })


    $('.ServerStatus').on('click', function () {
        $(this).toggleClass('on');
        if ($(this).hasClass('on')) {
            $("#ServerStatus").val('1');
        } else {
            $("#ServerStatus").val('0');
        }
        
    })
    
    </script>