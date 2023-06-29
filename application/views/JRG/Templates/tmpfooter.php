      <div class="az-footer ht-40">
        <div class="container-fluid pd-t-0-f ht-100p">
          <span>&copy; <?=date('Y');?> Void Main Development</i></span>
        </div><!-- container -->
      </div>
      <!-- az-footer -->

    </div><!-- az-content -->

  </body>
</html>


<script>
      $(function(){
        'use strict'

        $('.az-sidebar .with-sub').on('click', function(e){
          e.preventDefault();
          $(this).parent().toggleClass('show');
          $(this).parent().siblings().removeClass('show');
        })

        $(document).on('click touchstart', function(e){
          e.stopPropagation();

          // closing of sidebar menu when clicking outside of it
          if(!$(e.target).closest('.az-header-menu-icon').length) {
            var sidebarTarg = $(e.target).closest('.az-sidebar').length;
            if(!sidebarTarg) {
              $('body').removeClass('az-sidebar-show');
            }
          }
        });


        $('#azSidebarToggle').on('click', function(e){
          e.preventDefault();

          if(window.matchMedia('(min-width: 992px)').matches) {
            $('body').toggleClass('az-sidebar-hide');
          } else {
            $('body').toggleClass('az-sidebar-show');
          }
        })

        /* ----------------------------------- */
        /* Dashboard content */

      });
    </script>


    <script>
      $(function(){
        'use strict'


        $('.az-form-group .form-control').on('focusin focusout', function(){
          $(this).parent().toggleClass('focus');
        });

        $(document).ready(function(){
          $('.select2').select2({
            placeholder: 'Choose one'
          });

          $('.select2-no-search').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Choose one'
          });
        });

      });
    </script>


      <script>
      $(function(){
        'use strict'

        $('[data-toggle="tooltip"]').tooltip();

        // colored tooltip
        $('[data-toggle="tooltip-primary"]').tooltip({
          template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

        $('[data-toggle="tooltip-secondary"]').tooltip({
          template: '<div class="tooltip tooltip-secondary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

      });
    </script>


<script type="text/javascript">
  function EditFunc(e) {
      $.ajax({
        url:'<?=base_url()?>adminpanel/Edit_Func/'+ e,
        method:"POST",
        data: {Fields:Fields,Table:Table,Flds:Flds,Msg:Msg,Rd:Rd,opt:opt},
        success:function(data)
        {
         $('#replace').html(data);
        }
       });
    
  }
</script>