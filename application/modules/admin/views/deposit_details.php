
<!-- Top Bar -->
<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                 <h2><?php echo $title;?>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12"> 
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
                    </div>
                    <div class="body tablewidth">
                        <div class="table-responsive">
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="wd_details" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                              
                             <tbody>
                                        <tr class="bold">
                                                <td> Deposit By </td>
                                                <td>
                                                <?php echo isset($userdata) ? ucwords(strtolower($userdata[0]['FirstName'].' '.$userdata[0]['LastName'])) : '';?>
                                                 <i></i> 
                                                </td>
                                            </tr>

                                            <tr class="bold">
                                                <td> Deposit On </td>
                                                <td><?php echo isset($transdata) ? dateFormat($transdata[0]['Creation_Date_Time']) : '';?> </td>
                                            </tr>

                                            <tr class="bold">
                                                <td> Transaction  </td>
                                                <td><?php echo isset($transdata) ? $transdata[0]['Third_Party_Tran_Id'] : '';?> </td>
                                            </tr>

                                            <tr class="bold">
                                                <td> Method </td>
                                                <td>
                                                   <span class="label label-info"> <b><?php echo get_payment_method_name($depositdata[0]['Id'],$depositdata[0]['User_Id']);?> </b></span>                   
                                                </td>
                                            </tr>
                                        <?php if(@!empty($depositdata[0]['Card_Bank_No'])){?>
                                        <tr class="bold">
                                            <td> Card Number </td>
                                            <td><?php echo isset($depositdata) ? $depositdata[0]['Card_Bank_No'] : '';?></td>
                                        </tr>
                                        <tr class="bold">
                                            <td> Expiry (M/Y) </td>
                                            <td><?php echo isset($depositdata) ? $depositdata[0]['Expiry_Month_Year'] : '-';?></td>
                                        </tr>
                                      <?php } ?>
                                                
                                            <tr class="bold">
                                                <td> Amount </td>
                                                <td><?php echo isset($transdata) ? $currency.''.$transdata[0]['Amount'] : '';?> </td>
                                            </tr>

                                            <tr class="bold">
                                                <td> Charge </td>
                                                <td><?php echo isset($transdata) ? $currency.''.$transdata[0]['Charge'] : '';?> </td>
                                            </tr>

                                            <tr class="bold">
                                                <td> Status </td>
                                                <td><?php echo isset($transdata) ? getPaymentStatusText($transdata[0]['Tran_Status_Id']) : '';?>
                                                </td>
                                            </tr>
                                 </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="text-center">
                        <a href="JavaScript:Void(0)" class="btn btn-primary print_button color-purple deactivate_br"> <i class="fa fa-print" style="padding-right: 8px;"></i>Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $('.print_button').on('click', function(){
        var html_data = '<h3 style="text-align:center;">Deposit Details </h3>' ;
        setTimeout(function(){  }, 2000)
        divToPrint=document.getElementById("wd_details");
        html_data+=divToPrint;
        newWin = window.open("");  
        newWin.document.write(divToPrint.outerHTML); 
        newWin.print();
        newWin.close();
    });

</script>