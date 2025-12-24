<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<section class="content profile-page">

    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title; ?></h2>
            </div>

            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url('admin/'); ?>">
                            <i class="zmdi zmdi-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo $title; ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card box-shadow">
        <div class="row">
            <div class="col-md-10 m-t-10">

                <form method="POST" action="<?php echo base_url('admin/agent_search_transaction'); ?>">

                    <div class="row body">

                        <div class="col-lg-4 col-md-12">
                            <label class="col-md-12 update_padding">
                                <strong>Search Transaction By ID</strong>
                            </label>
                            <div class="form-group">
                                <input type="text"
                                    name="transaction_id"
                                    class="form-control"
                                    placeholder="Enter Transaction Number"
                                    autocomplete="off"
                                    required>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4 col-md-12">
                        <button class="btn btn-primary btn-round color-purple">Search</button>
                        <a class="btn btn-primary btn-round color-purple" href="<?php echo base_url('admin/agent_search_transaction'); ?>">Reset</a>
                    </div>

                </form>

            </div>

            <!-- <div class="col-md-2 text-right">
                <a href="<?php echo base_url('admin/home/exportTransactioncsv/csv'); ?>"
                   class="btn btn-primary btn-round text-right m-r-30">Export CSV</a>
            </div> -->

        </div>
    </div>

    <!-- Result Section -->
        <?php if ($this->input->post('transaction_id')) : ?>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow parents-list">

                    <?php if (!empty($transaction)) : ?>

                        <div class="header">
                            <h4>Transaction Detail</h4>
                        </div>

                        <div class="body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Sender Name</th>
                                    <td><?= $transaction->sender_first_name . ' ' . $transaction->sender_last_name ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Name</th>
                                    <td><?= $transaction->receiver_name ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Mobile</th>
                                    <td><?= $transaction->receiver_mobile ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Email</th>
                                    <td><?= $transaction->receiver_email ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Account Number</th>
                                    <td><?= $transaction->receiver_account_number ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver City</th>
                                    <td><?= $transaction->receiver_city ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Country</th>
                                    <td><?= $transaction->receiver_country ?></td>
                                </tr>
                                <tr>
                                    <th>Receiver Address</th>
                                    <td><?= $transaction->receiver_address ?></td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td><?= '$ ' . $transaction->amount ?></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td><?= $transaction->created_at ?></td>
                                </tr>
                                <tr>
                                    <th>Transaction No</th>
                                    <td><?= $transaction->transaction_id ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?= $transaction->status ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-4 col-md-12 mx-auto">

                            <form id="paidToRecipientForm">

                                <input type="hidden"
                                    name="transaction_id"
                                    value="<?= $transaction->transaction_id ?>"
                                    required>

                                <input type="hidden" name="status" value="PAID TO RECEIVER">

                                <?php if($transaction->status == "SUCCESS") { ?>
                                <button type="submit"
                                    class="btn btn-primary btn-round btn-lg color-purple" <?= ($transaction->status == "PAID TO RECEIVER" ? "Disabled" : "") ?>>
                                    Paid to Recipient
                                </button>
                                <?php } ?>

                            </form>

                            <div id="paidMsg" class="mt-2"></div>

                        </div>


                    <?php else : ?>
                        <div class="body">
                            <p style="color:red;">No transaction found!</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function () {

    $('#paidToRecipientForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();

        Swal.fire({
            title: 'Are you sure?',
            text: 'This transaction will be marked as Paid',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, mark as Paid',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#6f42c1',
            cancelButtonColor: '#d33'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "<?= base_url('admin/transaction_status_change'); ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {

                        if (response.status === true) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonColor: '#6f42c1'
                            });

                            setTimeout(function () {
                                location.reload();
                            }, 3000);

                            // Disable button after success
                            $('#paidToRecipientForm button')
                                .prop('disabled', true)
                                .text('Paid');

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });

});
</script>



</section>
