<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/colorbox/jquery.colorbox.js"></script>

<script>
    	var $colorbox = $.noConflict();
		$colorbox(".image_preview_colorbox").colorbox();
		/* $colorbox(".youtube").colorbox({iframe:true, innerWidth:300, innerHeight:400});*/
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!-- jEasy Grid -->
<!-- ================ -->
<script type="text/javascript" src="<?php echo base_url();?>plugins/grid/jquery.easyui.min.js"></script>
<!-- Load Language -->
<?php $jui_language_name=$this->language;?>
<script type="text/javascript" src="<?php echo base_url();?>plugins/grid/locale/<?php echo $jui_language_name;?>.js"></script>


<!--Multiselect plugin-->
<script type="text/javascript" src="<?php echo base_url();?>plugins/multiselect/multiple-select.js"></script>



<!--Jquery Date Time Picker -->

<script type="text/javascript" src="<?php echo base_url();?>plugins/datetimepickerjquery/jquery.datetimepicker.js"></script>





<!-- RTL Support -->
<?php 
// if($this->config->item('language')=="arabic") 
if($this->is_rtl) 
  { ?>    
    <link href="<?php echo base_url();?>plugins/grid/easyui-rtl.css" rel="stylesheet" type="text/css" /> 
    <script type="text/javascript" src="<?php echo base_url();?>plugins/grid/easyui-rtl.js"></script>
  <?php
  } ?>
<!-- ================ -->
<!-- jEasy Grid -->


<script>
    	var $j= jQuery.noConflict();
</script> 

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo base_url();?>plugins/morris/morris.min.js" type="text/javascript"></script>

<!-- Sparkline -->
<script src="<?php echo base_url();?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?php echo base_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>plugins/knob/jquery.knob.js" type="text/javascript"></script>

<!-- char.js -->
<script src="<?php echo base_url();?>plugins/chartjs/Chart.js" type="text/javascript"></script>

<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?php echo base_url();?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>js/app.min.js" type="text/javascript"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url();?>js/pages/dashboard.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>js/demo.js" type="text/javascript"></script>
<!-- added 20/9/2015 -->
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>plugins/xregexp/xregexp.js" type="text/javascript"></script>

<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->

<!-- for tab -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>-->



<script>
// grid formatter
function status(value,row,index)
{   
    if(value=="1") return "<label class='label label-success'>" + "<?php echo $this->lang->line('Active');?>" + "</label>";            
    else return "<label class='label label-warning'>" + "<?php echo $this->lang->line('Inactive');?>"  + "</label>";            
}   
function yes_no(value,row,index)
{   
    if(value=="1" || value=="yes") return "<label class='label label-success'>" + "<?php echo $this->lang->line('Yes');?>" + "</label>";           
    else return "<label class='label label-warning'>" + "<?php echo $this->lang->line('No');?>" + "</label>";            
}   

function yes_no_email(value,row,index)
{   
    if(value=="1") return "<label class='label label-success'>" + "<?php echo $this->lang->line('Successful');?>" + "</label>";            
    else return "<label class='label label-warning'>" + "<?php echo $this->lang->line('Failed');?>" + "</label>";            
}   
function ucwords_js (str) 
{
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}
function yes_no_sms(value,row,index)
{   
    if(value=="1" || value=='Sent') return "<label class='label label-success'>" + "<?php echo $this->lang->line('Successful');?>" + "</label>";            
    else 
    {
      if(value=="" || value=="0") value="Unknown Cause";
      value=ucwords_js(value);
      return "<label class='label label-warning' title='"+value+"' style='cursor:pointer;'>" + "<?php echo $this->lang->line('Failed');?>" + "</label>";   
    }        
}  

function attachment_download(value,row,index)
{   
    if (typeof(row)==='undefined') row = "";
    if (typeof(index)==='undefined') index = "";

    if(value && value!="0") return "<a class='label label-success' href='<?php echo base_url();?>home/attachment_downloader/"+value+"' target='_BLANK' title='Download'>"+value+"</a>";
    else return  "<label class='label label-warning' title='No attachment'>" + "<?php echo $this->lang->line('No attachment');?>" + "</label>";      
}  


function message_formatter(value,row,index)
{
    var newval;
    var recval=String(row.message);
    if(recval.length>=33) 
    {   
        newval=recval.substring(0, 30);
        newval=newval+"...";
    }
    else newval=recval;
    return newval;
}
//  grid formatter 


// function copyToClipboard(elem) {
//    // create hidden text element, if it doesn't already exist
//     var targetId = "_hiddenCopyText_";
//     var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
//     var origSelectionStart, origSelectionEnd;
//     if (isInput) {
//         // can just use the original source element for the selection and copy
//         target = elem;
//         origSelectionStart = elem.selectionStart;
//         origSelectionEnd = elem.selectionEnd;
//     } else {
//         // must use a temporary form element for the selection and copy
//         target = document.getElementById(targetId);
//         if (!target) {
//             var target = document.createElement("textarea");
//             target.style.position = "absolute";
//             target.style.left = "-9999px";
//             target.style.top = "0";
//             target.id = targetId;
//             document.body.appendChild(target);
//         }
//         target.textContent = elem.textContent;
//     }
//     // select the content
//     var currentFocus = document.activeElement;
//     target.focus();
//     target.setSelectionRange(0, target.value.length);
    
//     // copy the selection
//     var succeed;
//     try {
//        succeed = document.execCommand("copy");
//     } catch(e) {
//         succeed = false;
//     }
//     // restore original focus
//     if (currentFocus && typeof currentFocus.focus === "function") {
//         currentFocus.focus();
//     }
    
//     if (isInput) {
//         // restore prior selection
//         elem.setSelectionRange(origSelectionStart, origSelectionEnd);
//     } else {
//         // clear temporary content
//         target.textContent = "";
//     }
//     return succeed;
// }



// Code that uses other library's $ can follow here.
$j("document").ready(function(){

 // $(document.body).on('click','.copy_web',function(){
 //    var id2=$(this).attr('id'); 
 //    var copy_id= "val_"+id2;
 //    alert(copy_id);
 //    copyToClipboard(document.getElementById(copy_id));
 //  });

  //crud birthday schedule
  $("#message_template_birthday").change(function(){
    var template=$(this).val(); 
    CKEDITOR.instances['field-message'].setData(template)
  });

     $("#message").keyup(function(){
        var content=$("#message").val();
        var length= content.length;
        var no_sms= parseInt(length)/160;
        no_sms=Math.ceil(no_sms); 
        $("#text_count").addClass("alert alert-warning text-center");
        $("#text_count").html("<b><?php echo $this->lang->line('character count');?> : "+length+'/'+no_sms+"</b>");
      });
  
  //crud birthday schedule
});

function goBack(link,insert_or_update) //used to go back to list as crud
{
  if (typeof(insert_or_update)==='undefined') insert_or_update = 0;

    var mes='';
  if(insert_or_update==0)
  mes="<?php echo $this->lang->line('the data you had insert may not be saved.\\nare you sure you want to go back to list?') ?>";
    else
    mes="<?php echo $this->lang->line('the data you had change may not be saved.\\nare you sure you want to go back to list?') ?>";
  var ans=confirm(mes); 
  link="<?php echo site_url();?>"+link;
  if(ans) window.location.assign(link);
}
// Code that uses other library's $ can follow here.


$j('document').ready(function() {
 // replace admin and member string to 
 var replace_dropdown='<select class="chosen-select" name="user_type" id="field-user_type"><option value=""></option><option value="Member">'+'<?php echo $this->lang->line("member user"); ?>'+'</option><option value="Admin">'+'<?php echo $this->lang->line("admin user"); ?>'+'</option></select>';  
 $("#user_type_input_box").html(replace_dropdown);
 
});

</script>


<script type="text/javascript">
  $j(document).ready(function() {
    $("#language_change").change(function(){
      var language=$(this).val();
      $("#language_label").html("Loading Language...");
      $.ajax({
        url: '<?php echo site_url("home/language_changer");?>',
        type: 'POST',
        data: {language:language},
        success:function(response){
            $("#language_label").html("Language");
            location.reload(); 
        }
      })
      
    });
  });
</script>


<?php if($this->is_rtl){ ?>

<script type="text/javascript">

$j('document').ready(function() {
  $('*').each(function() {  
      if(!isRTL($(this).text())){
       $(this).addClass('ltr');
    }
  });
});
  
  
  function isInt(value) {

      var er = /^-?[0-9]+$/;
  
      return er.test(value);
  }

  
  function isRTL(str) {
  
      var isArabic = XRegExp('[\\p{Arabic}]');
      var partArabic = 0;
      var rtlIndex = 0;
    
    /**This for check if any of the text is numberic then don't make it RTL***/
    var is_int=0;
    
      var isRTL = false;
  
      for(i=0;i<str.length;i++){
          if(isArabic.test(str[i]))
              partArabic++;
        
      if(isInt(str[i])){
        is_int=1;
      }
        
      }
    
    /**if any character is arabic and also check if no integer there , then it is RTL**/
      if(partArabic > 0 && is_int==0) {
          isRTL = true;
      }
      return isRTL;
  }
  
</script>

<?php  } ?>
