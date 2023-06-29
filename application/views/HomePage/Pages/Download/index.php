<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Download</title>
<body  class="sub-body">
<div id="dialog" style='display:none; text-align:center; overflow:hidden;' >
    <iframe id="myIframe" src="/Popup/popup_pw_change.html" style="width:390px; height:270px; border:0"></iframe>
</div>
    <div class="in-body7">
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
		HOME<img src="<?=base_url()?>/Images/Icon/navi_icon.gif" alt="">
		<span class="page-history-select">DOWNLOAD</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>/Images/SubTitle/Library/download_sub_title.gif" alt="Multimedia">
	</h3>
	<section class="download">
		<section class="client-download">
			<h4><img src="<?=base_url()?>/Images/Library/client_title.gif" alt="Client"></h4>

            <?php
            foreach ($dltype as $type) {
                if($type['DownloadType'] == 'Game Client'){
                    ?>
                    <div class="game-download">
                        <?php
                        $clientcount = 0;
                        foreach ($download as $client) {
                            if($client['DownloadType'] == 'Game Client'){
                                $clientcount++;
                                ?>
                                <img src="<?=base_url()?>/Images/Library/game_download_btn<?=$clientcount?>.gif" id="btnClientDown<?=$clientcount?>" class="pointer" alt="Game Client Download<?=$clientcount?>">
                                <?php
                            }
                        }
                        ?>
                        <span class="client-version">Please press the download button.</span>
                    </div>
                    <?php
                } elseif($type['DownloadType'] == 'Test Client'){
                    ?>
                    <div class="test-download">
                        <?php
                        $testcount = 0;
                        foreach ($download as $client) {
                            if($client['DownloadType'] == 'Test Client'){
                                $testcount++;
                                ?>
                                <img src="<?=base_url()?>/Images/Library/test_download_btn<?=$testcount?>.gif" id="btnClientTestDown<?=$testcount?>" class="pointer" alt="Test client download<?=$testcount?>">
                                <?php
                            }
                        }
                        ?>
                        <span class="client-version">Please press the download button.</span>
                    </div>
                    <?php
                } elseif($type['DownloadType'] == 'Manual Patch'){
                    ?>
                    <section class="focus-notice left">
                        <p>If you experience problems with client updates, <strong>download the manual patch file</strong><br>
                         Please install it.</p>
                    </section>
                    <div class="patch_download right">
                        <img src="<?=base_url()?>/Images/Library/patch_download_btn.gif" id="btnPatch" class="pointer" alt="Manual patch download">
                        <!--<p>792MB / 2011-08-29</p>-->
                    </div>
                    <?php
                } else {

                }
            }
            ?>
			

			
		
			
			<div class="clear"></div>
		
		</section>
		<section class="system">
			<h4><img src="<?=base_url()?>/Images/Library/systemcheck_title.gif" alt="PC System Checklist"></h4>
			<table class="system-info">
			<caption>PC System Checklist</caption>
			<colgroup>
				<col style="width:100px;">
				<col style="width:300px;">
				<col style="width:300px;">
			</colgroup>
			<thead>
			<tr>
				<th class="first"><img src="<?=base_url()?>/Images/Library/division_th.gif" alt="division"></th>
				<th><img src="<?=base_url()?>/Images/Library/min_setting_th.gif" alt="Minimum specification"></th>
				<th><img src="<?=base_url()?>/Images/Library/advice_setting_th.gif" alt="Recommended specification"></th>
			<tr>
			</thead>
			<tbody>
			<tr>
				<th>OS</th>
				<td>Windows XP </td>
				<td>Windows 7</td>
			</tr>
			<tr>
				<th>CPU</th>
				<td>Intel Pentium4 1GHz </td>
				<td>Intel Core2 Duo 2.0GHz </td>
			</tr>
			<tr>
				<th>RAM</th>
				<td>1GB </td>
				<td>2GB </td>
			</tr>
			<tr>
				<th>VGA</th>
				<td>
				    NVIDIA Geforce 5600 <br>
                    ATI Radeon 9500 <br>
                    Intel GMA 950 
                </td>
				<td>
				    NVIDIA GeForce 7600 <br/>
                    ATI Radeon X800 <br/>
                    Intel GMA X3000 
                </td>
			</tr>
			<tr>
				<th>HDD</th>
				<td>Free space 3GB </td>
				<td>Free space 4GB </td>
			</tr>
			<tr>
				<th>Direct X</th>
				<td>DirectX 9.0c </td>
				<td>DirectX 9.0c </td>
			</tr>
        
			</tbody>
			</table>
			<section class="focus-notice">
				<p>
					<strong>What is the recommended specification?</strong><br>
					The recommended specifications mean that the game can be played smoothly. Performance may vary slightly depending on the OS, line conditions, and PC management.
				</p>
			</section>
		</section>
	
		<section class="driver">
			<h4><img src="<?=base_url()?>/Images/Library/driver_title.gif" alt="Direct X & Graphics Card Driver"></h4>
			<div class="driver_wrapper">
				<section class="directx_download">
					<div class="driver-thumb">
						<img src="<?=base_url()?>/Images/Library/directx_icon.gif" alt="">
					</div>
					<dl>
					<dt><img src="<?=base_url()?>/Images/Library/directx_download.gif" alt="direct X 9.0 download"></dt>
					<dd class="driver-info">
						<img src="<?=base_url()?>/Images/Library/directx_info.gif" alt="Ran Online provides a game environment optimized for Direct X 9.0c. Please check the installed version of Direct X. DirectX version check-start-run-dxdiag check">
					</dd>
					<dd><a href="http://www.microsoft.com/downloads/ko-kr/details.aspx?displaylang=ko&FamilyID=2DA43D38-DB71-4C1B-BC6A-9B6652CD92A3" target="_blank"><img src="<?=base_url()?>/Images/Library/directx_download_btn.gif" alt="Download Direct X 9.0"></dd></a>
					</dl>
					<div class="clear"></div>
				</section>
				<ul class="driver-download">
				<li>
					<div class="driver-thumb">
						<img src="<?=base_url()?>/Images/Library/nvidia_icon.gif" alt="">
					</div>
					<dl>
					<dt><img src="<?=base_url()?>/Images/Library/nvidia.gif" alt="Nvidia Series"></dt>
					<dd><a href="https://www.nvidia.com/Download/index.aspx?lang=en" target="_blank"><img src="<?=base_url()?>/Images/Library/driver_download_btn.gif" alt="Download"></a></dd>
					</dl>
					<div class="clear"></div>
				</li>
				<li>
					<div class="driver-thumb">
						<img src="<?=base_url()?>/Images/Library/ati_icon.gif" alt="">
					</div>
					<dl>
					<dt><img src="<?=base_url()?>/Images/Library/ati.gif" alt="Ati Series"></dt>
					<dd><a href="https://www.amd.com/en/support" target="_blank"><img src="<?=base_url()?>/Images/Library/driver_download_btn.gif" alt="Download"></a></dd>
					</dl>
					<div class="clear"></div>
				</li>
				<li class="last">
					<div class="driver-thumb">
						<img src="<?=base_url()?>/Images/Library/intel_icon.gif" alt="">
					</div>
					<dl>
					<dt><img src="<?=base_url()?>/Images/Library/intel2.gif" alt="Intel Series"></dt>
					<dd><a href="https://downloadcenter.intel.com/en" target="_blank"><img src="<?=base_url()?>/Images/Library/driver_download_btn.gif" alt="Download"></a></dd>
					</dl>
					<div class="clear"></div>
				</li>
				
				</ul>
				<div class="clear"></div>
			</div>
		</section>
	</section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

<?php
            foreach ($dltype as $type) {
                if($type['DownloadType'] == 'Game Client'){
                    ?>
                        <?php
                        $clientcount = 0;
                        foreach ($download as $client) {
                            if($client['DownloadType'] == 'Game Client'){
                                $clientcount++;
                                ?>
                                <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#btnClientDown<?=$clientcount?>').click(function() {
                                        location.href = '<?=$client['DownloadLink']?>';
                                    });
                                });
                                </script>
                                <?php
                            }
                        }
                        ?>
                     
                    <?php
                } elseif($type['DownloadType'] == 'Test Client'){
                    ?>
                        <?php
                        $testcount = 0;
                        foreach ($download as $client) {
                            if($client['DownloadType'] == 'Test Client'){
                                $testcount++;
                                ?>
                                <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#btnClientTestDown<?=$clientcount?>').click(function() {
                                        location.href = '<?=$client['DownloadLink']?>';
                                    });
                                });
                                </script>
                                <?php
                            }
                        }
                        ?>
                
                    <?php
                } elseif($type['DownloadType'] == 'Manual Patch'){
                    foreach ($download as $client) {
                            if($client['DownloadType'] == 'Manual Patch'){
                   
                                ?>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#btnPatch').click(function() {
                                            location.href = '<?=$client['DownloadLink']?>';
                                        });
                                    });
                                </script> 
                                <?php
                            }
                        }
                } else {

                }
            }
            ?>

</body>
</html>
