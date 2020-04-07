<?php 
    if($this->session->userdata('video_uploaded') != '')
    {
        echo "<div class='alert alert-success text-center'>".$this->session->userdata('video_uploaded')."</div>";
        $this->session->unset_userdata('video_uploaded');
    }
    if($this->session->userdata('edit_video_info') != '')
    {
        echo "<div class='alert alert-success text-center'>".$this->session->userdata('edit_video_info')."</div>";
        $this->session->unset_userdata('edit_video_info');
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
<h1> Uploaded Video List </h1>

</section>
<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:550px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."youtube_live_event/uploaded_video_list_data"; ?>" 
            
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
                        <th field="id" checkbox="true">ID.</th>                        
                        <th field="channel_id" sortable="true">Channel ID</th>
                        <th field="video_id" formatter='video_column'>Video Link</th>
                        <th field="title" sortable="true">Title</th>
                        <th field="tags" sortable="true">Tags</th>
                        <th field="privacy_type" sortable="true">Privacy</th>
                        <th field="time_zone" sortable="true">Time Zone</th>
                        <th field="upload_time" sortable="true">Upload Time</th>
                        <th field="upload_status" formatter='status_column'>Status</th>
                        <th field="view" formatter='action_column'>Action</th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">

            <a type="button" class="btn btn-primary" href="<?php echo base_url('youtube_live_event/youtube_video_upload'); ?>"><i class="fa fa-cloud-upload"></i> Upload New Video</a>

            <form class="form-inline" style="margin-top:20px">

                <div class="form-group">
                    <input id="video_title" name="video_title" class="form-control" size="20" placeholder="Video Title">
                </div> 

                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line("search");?></button>     

                      
            </form> 

        </div>        
    </div>
  </div>   
</section>


<script type="text/javascript">       
    var base_url="<?php echo site_url(); ?>";
    
    function status_column(value,row,index)
    {               
        var status = row.upload_status;
        var str = '';
        if(status == '1')
            str = "<span class='label label-danger'>Uploaded</span>";
        else
            str = "<span class='label label-success'>Waiting for upload</span>";
        
        return str;
    }

    function video_column(value,row,index)
    {               
        var video_id = row.video_id;
        
        var str = "<a target='_blank' href='https://www.youtube.com/watch?v="+video_id+"' title='Go to youtube'>"+video_id+"</a>";
        
        return str;
    }

    function action_column(value,row,index)
    {               
        var id = row.id;
        var status = row.upload_status;
        var delete_url = base_url+"youtube_live_event/delete_uploaded_video/"+id;
        var edit_url = base_url+"youtube_live_event/edit_uploaded_video/"+id;
        var str = '';
        if(status == '0')
            str += "<a class='btn btn-primary' href='"+edit_url+"'><i class='fa fa-pencil-square-o'></i> Edit</a>&nbsp;&nbsp;";

        str += "<a class='btn btn-danger'  onclick=\"return confirm('"+'<?php echo $this->lang->line("are you sure that you want to delete this record?"); ?>'+"')\"  href='"+delete_url+"'><i class='fa fa-minus-circle'></i> Delete</a>";
        return str;
    }

    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          video_title:      $j('#video_title').val(),
          is_searched:      1
        });


    }


</script>
