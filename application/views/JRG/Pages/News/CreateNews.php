<title>Dashboard</title>

<style type="text/css">
  .Thumbnailsize {
  max-width:125px;
  max-height:62px;
}
</style>
<div class="az-content-body">
      <div class="container">
        <?php renderview('JRG/Pages/News/SideMenu')?>
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <form method="POST" id="form" enctype="multipart/form-data" data-parsley-validate>

            
            <div class="d-flex flex-row mg-b-30">
              <div class="pd-10 flex-fill">
                <div class="form-group">
                  <label class="form-label">Title:</label>
                  <input type="text" name="title" id="c_title" class="form-control wd-250" placeholder="Title" required autocomplete="off">
                </div><!-- form-group -->
                <hr class="mg-y-10"> 
                <div class="form-group">
                  <label class="form-label">Thumbnail:</label>
                  <label class="btn btn-primary btn-block btn-sm" style="width: 40%;cursor: pointer;">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                  <input type="file" name="filename" id="fileupload" style="display: none;" onchange="readURL(this);"> Select Thumbnail
                </label>
                </div>
              </div>
              <div class="pd-10 flex-fill">
                <label class="form-label">Image Preview:</label> <label class="badge badge-light">125 x 62</label> <hr class="mg-y-25"> 
                <img id="blah" class="img-thumbnail wd-100p wd-sm-150 Thumbnailsize" src="<?=base_url()?>assets/notice/notice_basic.png" alt="your image" />
              </div>
            </div>

            <div class="d-flex flex-row mg-b-320">
              <div class="pd-10 flex-fill">
          
                <div class="form-group">
                  <label class="form-label">Category:</label>
                  <select class="form-control wd-200" name="type">
                    <option value="1">Notice</option>
                    <option value="2">Update</option>
                    <option value="3">Events</option>
                    <option value="4">Free Board</option>
                    <option value="5">Screen Shot</option>
                    <option value="6">Developers Note</option>
                    <option value="7">Club</option>
                  </select>
                </div><!-- form-group -->
              </div>

              <div class="pd-10 flex-fill">

                <div class="form-group">
                  <label class="form-label">Status:</label>
                  <select class="form-control wd-200" name="status">
                    <?php
                      foreach (NewsConfig() as $row) {
                        echo '<option value="'.$row['value'].'">'.$row['Name'].'</option>';
                      }
                    ?>
                  </select>
                </div><!-- form-group -->
              </div>

              <div class="pd-10 flex-fill">
                <div class="wd-200 mg-b-20">
            
                </div>
              </div>
            </div>

            

            <div class="d-flex flex-row mg-b-320">
              <div class="pd-10 flex-fill">
                <div class="wd-250 mg-b-20">
                  <label class="form-label">From:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                      </div>
                    </div>
                    <input type="text" value="<?=currentdatetime0()?>" id="datetimepicker2" class="form-control" name="eventfrom">
                  </div>
                </div>
              </div>
              <div class="pd-10 flex-fill">
                <div class="wd-250 mg-b-20">
                  <label class="form-label">To:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                      </div>
                    </div>
                    <input type="text" value="<?=currentdatetime0()?>" id="datetimepicker3" class="form-control" name="eventto">
                  </div>
                </div>
              </div>

              <div class="pd-10 flex-fill">
                <div class="wd-200 mg-b-20">

                </div>
              </div>
            </div>

              <input type="hidden" name="newsid" id="c_newsid" />
              <label class="form-label">Content:</label>
              <textarea id="editor1" class="content" name="content"></textarea>
              <hr class="mg-y-30">
              <button type="submit" class="btn btn-az-primary pd-x-20">Post</button>
              
          </form>
        </div><!-- az-content-body --> 
      </div><!-- container -->
    </div>
 




<script>
  new Picker(document.querySelector('#datetimepicker2'), {
          headers: true,
          format: 'YYYY-MM-DD HH:mm',
          text: {
            title: 'From',
            year: 'Year',
            month: 'Month',
            day: 'Day',
            hour: 'Hour',
            minute: 'Minute'
          },
        });

  new Picker(document.querySelector('#datetimepicker3'), {
          headers: true,
          format: 'YYYY-MM-DD HH:mm',
          text: {
            title: 'To',
            year: 'Year',
            month: 'Month',
            day: 'Day',
            hour: 'Hour',
            minute: 'Minute'
          },
        });

  $(function () {

    CKEDITOR.replace('editor1')
    
    //$('.textarea').wysihtml5()
  })
</script>
<script type="text/javascript">
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }

        }
</script>
 <!-- <script>  
 $(document).ready(function(){ 

      function autoSave()  
      {  
           var post_title = $('#c_title').val();  
           var post_description = CKEDITOR.instances.editor1.getData();
           var post_id = $('#c_newsid').val();  
           

           if(post_title != '' && post_description != '')  
           {  
                $.ajax({  
                     url:"<?=base_url()?>news/autosave", 
                     method:"POST",  
                     data:{title:post_title, content:post_description, newsid:post_id},  
                     dataType:"text", 
                     success:function(data)
                     {  
                          if(data != '')  
                          {  
                               $('#c_newsid').val(data);  
                          }  
                          //$('#autoSave').text("Post save as draft");  
                          setInterval(function(){  
                               $('#autoSave').text('');  
                          }, 2000);  
                     }  
                });  
           }            
      }  
      setInterval(function(){   
           autoSave();   
           }, 5000);  
 });   
 </script> -->
