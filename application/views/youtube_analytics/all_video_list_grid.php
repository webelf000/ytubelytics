<?php
    $view_permission    = 1;
?>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1> Video list for all channel</h1>

</section>
<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:1100px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."youtube_analytics/get_all_video_list_data"; ?>" 
            
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
                        <!-- <th field="id" >ID.</th> -->
                        <th field="thumbnail" formatter='thumbnail_column'>Thumbnail</th>            
                        <th field="video_id" formatter='video_column'>Video Link</th>
                        <th field="title" sortable="true">Title</th>
                        <th field="likeCount" sortable="true">Likes</th>
                        <th field="dislikeCount" sortable="true">Dislikes</th>
                        <th field="commentCount" sortable="true">Comments</th>
                        <th field="favoriteCount" sortable="true">Favorite</th>
                        <th field="viewCount" sortable="true">Views</th>
                        <!-- <th field="publish_time" sortable="true">Published Time</th> -->
                        <th field="view" formatter='action_column'><?php echo $this->lang->line("actions");?></th>
                    </tr>
                </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">
       
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
    
    function action_column(value,row,index)
    {               
        var analytics_url=base_url+'youtube_analytics/get_video_details/'+row.id;
        
        var str="";        
        var more="<?php echo $this->lang->line('Analytics');?>";      
         
        str+="<a target='_blank' title='"+more+"' style='cursor:pointer' class='btn btn-warning' href='"+analytics_url+"'><i class='fa fa-bar-chart'></i> "+more+"</a>";
        
        return str;
    }


    function thumbnail_column(value,row,index)
    {               
        var image_link = row.image_link;
        
        var str = "<img style='height:80px;width:120px;' src='"+image_link+"' alt=''>";
        
        return str;
    }

    function video_column(value,row,index)
    {               
        var video_id = row.video_id;
        
        var str = "<a target='_blank' href='https://www.youtube.com/watch?v="+video_id+"' title='Go to youtube'>"+video_id+"</a>";
        
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
