<title> | Edit News</title>
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
                  <input type="text" name="title" class="form-control wd-250" value="<?=$newsdata[0]['newstitle']?>" placeholder="Title" required autocomplete="off">
                </div><!-- form-group -->
                <hr class="mg-y-10"> 
                <div class="form-group">
                  <label class="form-label">Thumbnail:</label>
                  <label class="btn btn-primary btn-block btn-sm" style="width: 40%;cursor: pointer;">
                  <i class="fa fa-upload" aria-hidden="true"></i>
                  <input type="file" name="filename" style="display: none;" onchange="readURL(this);"> Select Thumbnail
                  <input type="hidden" name="filealt" id="fileupload">
                </label>
                </div>
              </div>
              <div class="pd-10 flex-fill">
                <label class="form-label">Image Preview:</label> <label class="badge badge-light">125 x 62</label> 
                <a style="cursor: pointer;" class="badge badge-light" onclick="myFunction()">Default</a>
                <hr class="mg-y-25"> 
                <img id="blah" class="img-thumbnail wd-100p wd-sm-150 Thumbnailsize" src="<?=base_url()?>Uploadfiles/News/<?=$newsdata[0]['banner']?>" alt="Banner" />
              </div>
            </div>

            <div class="d-flex flex-row mg-b-320">
              <div class="pd-10 flex-fill">
          
                <div class="form-group">
                  <label class="form-label">Category:</label>
                  <select class="form-control wd-200" name="type">
                    <option value="1" <?=(($newsdata[0]['type'] == 1) ? "Selected":"")?>>News</option>
                    <option value="2" <?=(($newsdata[0]['type'] == 2) ? "Selected":"")?>>Events</option>
                    <option value="3" <?=(($newsdata[0]['type'] == 3) ? "Selected":"")?>>Developers Note</option>
                  </select>
                </div><!-- form-group -->
              </div>

              <div class="pd-10 flex-fill">

                <div class="form-group">
                  <label class="form-label">Status:</label>
                  <select class="form-control wd-200" name="status">
                  <?php
                      foreach (NewsConfig() as $row) {
                        echo '<option value="'.$row['value'].'" '.(($newsdata[0]['highlights'] == $row['value']) ? "Selected":"").'>'.$row['Name'].'</option>';
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
                    <input type="text" value="<?=formatdate032($newsdata[0]['eventfrom'])?>" id="datetimepicker2" class="form-control" name="eventfrom">
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
                    <input type="text" value="<?=formatdate032($newsdata[0]['eventto'])?>" id="datetimepicker3" class="form-control" name="eventto">
                  </div>
                </div>
              </div>

              <div class="pd-10 flex-fill">
                <div class="wd-200 mg-b-20">

                </div>
              </div>
            </div>
            
             
              <label class="form-label">Content:</label>
              <textarea name="content" id="editor1">
                <?=$newsdata[0]['newscontent']?>
              </textarea>
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
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
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

<script>
function myFunction() {

  $("#fileupload").attr("value", "notice_basic.png");
  $("#blah").attr("src", "<?=base_url()?>uploads/thumbnail/notice_basic.png");

}
</script>

