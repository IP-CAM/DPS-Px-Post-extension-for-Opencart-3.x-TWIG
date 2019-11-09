<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-paymentexpress" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-dpspxpost" class="form-horizontal">

                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="dps_pxpost_username">
                            <?php echo $entry_username;?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dps_pxpost_username" value="<?php echo $dps_pxpost_username; ?>" placeholder="<?php echo $dps_pxpost_username; ?>" id="dps_pxpost_username" class="form-control" />

                            <?php if ($error_username) { ?>
                            <div class="text-danger"><?php echo $error_username; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="dps_pxpost_pass"><span data-toggle="tooltip" title="<?php echo $help_pass; ?>"><?php echo $entry_pass;?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dps_pxpost_pass" value="<?php echo $dps_pxpost_pass; ?>" placeholder="<?php echo $dps_pxpost_pass; ?>" id="dps_pxpost_pass" class="form-control" />

                            <?php if ($error_pass) { ?>
                            <div class="text-danger"><?php echo $error_pass; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="dps_pxpost_url"><span data-toggle="tooltip" title="<?php echo $help_url; ?>"><?php echo $entry_url;?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dps_pxpost_url" value="<?php echo $dps_pxpost_url; ?>" placeholder="<?php echo $dps_pxpost_url; ?>" id="dps_pxpost_url" class="form-control" />

                            <?php if ($error_url) { ?>
                            <div class="text-danger"><?php echo $error_url; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_txn_type"><span data-toggle="tooltip" title="<?php echo $help_txn_type; ?>"><?php echo $entry_txn_type; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_txn_type" id="dps_pxpost_txn_type" class="form-control">
                                <?php if ($dps_pxpost_txn_type == "Auth") { ?>
                                <option value="Auth" selected="selected"><?php echo $text_auth; ?></option>
                                <option value="Purchase"><?php echo $text_purchase; ?></option>
                                <?php } else { ?>
                                <option value="Auth"><?php echo $text_auth; ?></option>
                                <option value="Purchase" selected="selected"><?php echo $text_purchase; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_billing_vault"><span data-toggle="tooltip" title="<?php echo $help_billing_vault; ?>"><?php echo $entry_billing_vault; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_billing_vault" id="dps_pxpost_billing_vault" class="form-control">
                                <?php if ($dps_pxpost_billing_vault) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_processed_status_id"><?php echo $entry_processed_status; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_processed_status_id" id="dps_pxpost_processed_status_id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $dps_pxpost_processed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_failed_status_id"><?php echo $entry_failed_status; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_failed_status_id" id="dps_pxpost_failed_status_id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $dps_pxpost_failed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_geo_zone_id" id="dps_pxpost_geo_zone_id" class="form-control">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $dps_pxpost_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_status" id="dps_pxpost_status" class="form-control">
                                <?php if ($dps_pxpost_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_avs"><?php echo $entry_avs; ?></label>
                        <div class="col-sm-10">
                            <select name="dps_pxpost_avs" id="dps_pxpost_avs" class="form-control">
                                <?php if ($dps_pxpost_avs) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="dps_pxpost_sort_order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dps_pxpost_sort_order" value="<?php echo $dps_pxpost_sort_order; ?>" placeholder="<?php echo $dps_pxpost_sort_order; ?>" id="input-sort-order" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>