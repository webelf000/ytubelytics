<?php 
    if($this->session->userdata('uploaded_event_status') != '')
    {
        echo "<div class='alert alert-success text-center'>".$this->session->userdata('uploaded_event_status')."</div>";
        $this->session->unset_userdata('uploaded_event_status');
    }

    if($this->session->userdata('update_success') != '')
    {
        echo "<div class='alert alert-success text-center'>Data has been updated successfuly</div>";
        $this->session->unset_userdata('update_success');
    }
    if($this->session->userdata('delete_success') != '')
    {
        echo "<div class='alert alert-success text-center'>".$this->session->userdata('delete_success')."</div>";
        $this->session->unset_userdata('delete_success');
    }
    if($this->session->userdata('delete_error') != '')
    {
        echo "<div class='alert alert-danger text-center'>".$this->session->userdata('delete_error')."</div>";
        $this->session->unset_userdata('delete_error');
    }
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1> Live Event List </h1>

</section>
<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:550px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."youtube_live_event/live_event_list_data"; ?>" 
            
            pagination="true" 
            rownumbers="true" 
            toolbar="#tb" 
            pageSize="10" 
            pageList="[5,10,20,50,100]"  
            fit= "true" 
            fitColumns= "true" 
            nowrap= "true" 
            view= "detailview"
            idField="id"
            >
            
                <thead>
                    <tr>
                        <!-- <th field="id" checkbox="true">ID.</th>                         -->
                        <th field="title" sortable="true">Event Title</th>
                        <th field="tags" sortable="true">Keywords</th>
                        <th field="view" formatter='action_column'>Action</th>
                        <th field="channel_id" sortable="true">Channel ID</th>
                        <th field="Broadcast_id" sortable="true">Broadcast ID</th>
                        <th field="Stream_id" sortable="true">Stream ID</th>
                        <th field="privacy_type" sortable="true">Privacy</th>
                        <th field="time_zone" sortable="true">Time Zone</th>
                        <th field="start_time" sortable="true">Start Time</th>
                        <th field="end_time" sortable="true">End Time</th>
                        <th field="last_updated" sortable="true">Last Update Time</th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">

            <a type="button" class="btn btn-primary" href="<?php echo base_url('youtube_live_event/create_new_event'); ?>"><i class="fa fa-plus-square"></i> Create New Event</a>

            <form class="form-inline" style="margin-top:20px">

                <div class="form-group">
                    <input id="event_title" name="event_title" class="form-control" size="20" placeholder="Event Title">
                </div> 

                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line("search");?></button>     

                      
            </form> 

        </div>        
    </div>
  </div>   
</section>


<script type="text/javascript">       
    var base_url="<?php echo site_url(); ?>";
    

    function action_column(value,row,index)
    {               
        var id = row.id;
        var delete_url = base_url+"youtube_live_event/delete_uploaded_event/"+id;
        var str = ''; 
        str += "<a class='btn btn-danger'  onclick=\"return confirm('"+'<?php echo $this->lang->line("are you sure that you want to delete this record?"); ?>'+"')\" href='"+delete_url+"'><i class='fa fa-minus-circle'></i> Delete</a>";
        return str;
    }

    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          event_title:      $j('#event_title').val(),
          is_searched:      1
        });


    }


</script>


<div class="modal fade" id="tags_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Event Details</h4>
        </div>
        <div class="modal-body">
            <div id="tags_info"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>