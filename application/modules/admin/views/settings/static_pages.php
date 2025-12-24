
<!-- Top Bar -->
<section class="content profile-page">
    <div class="block-header">
        
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
               <!--  <button class="btn btn-white btn-icon btn-round hidden-sm-down float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button> -->
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>                    
                    <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow parents-list">
                     <div class="header">
                          <!-- <strong><i class="fa fa-desktop"></i> View All Users</strong>  -->
                     </div>
                
                    <div class="body">
                        <div class="table-responsive">

                            <table id="pagelist" class="usersList table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl search_box_right">
                                <thead>
                                    <tr>
                                        <th class="heading_center">Line #</th>
                                        <th class="heading_center">Subject</th>
                                        <th class="heading_center">Description</th>
                                       <!--<th class="heading_center"> Status</th> -->
                                        <th class="heading_center">Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php if(!empty($static_data)){ 
                                        $i=1;
                                        foreach ($static_data as $key => $value) 
                                        {?>
                                        <tr>
                                        <td><?php echo $i;?> </td>
                                        <td><?php echo $value['title'];?> </td>
                                        <td><?php echo substr($value['discription'],0,120).'...'; ?></td> 
                                        <td>  
                                            <a href="<?php echo base_url('admin/editpage/'.encode($value['id']));?>" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a>                                                     
                                        </td>
                                       </tr>
                                        <?php 
                                        $i++;
                                         }
                                      }?>
                                       
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
  $('.settings').addClass('active');
  $('.static_pages_list').addClass('active');
    $(function () {
   $("#pagelist").DataTable({
                  aoColumnDefs: [
                    {
                       bSortable: false,
                       aTargets: [0,2,3]
                    }
                  ],
                  order: [[0, 'asc']]
              });
  })
</script>
