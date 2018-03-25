<!-- Edit Product Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('product_edit') ?></h1>
            <small><?php echo display('edit_your_product') ?></small>
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('product') ?></a></li>
                <li class="active"><?php echo display('product_edit') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('error_message');
            }
        ?>
        <!-- Purchase report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('product_edit') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Cproduct/product_update')?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="product_name" class="col-sm-4 col-form-label"><?php echo display('product_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="product_name" type="text" id="product_name" placeholder="<?php echo display('product_name') ?>" value="{product_name}">
                                        <input type="hidden" name="product_id" value="{product_id}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="generic_name" class="col-sm-4 col-form-label"><?php echo display('generic_name') ?> </label>
                                    <div class="col-sm-8">
                                         <input class="form-control" name="generic_name" type="text" id="generic_name" placeholder="<?php echo display('generic_name') ?>" value="{generic_name}">
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="box_size" class="col-sm-4 col-form-label"><?php echo display('box_size') ?></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="box_size" type="number" id="box_size" placeholder="<?php echo display('box_size') ?>" value="{box_size}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="expire_date" class="col-sm-4 col-form-label"><?php echo display('expire_date') ?> </label>
                                    <div class="col-sm-8">
                                         <input class="form-control datepicker" name="expire_date" type="text" id="expire_date" placeholder="<?php echo display('expire_date') ?>" value="{expire_date}">
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="row">
                           <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="product_location" class="col-sm-4 col-form-label"><?php echo display('product_location') ?></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="product_location" type="text" id="product_location" placeholder="<?php echo display('product_location') ?>" value="{product_location}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="description" class="col-sm-4 col-form-label"><?php echo display('details') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" name="description" id="description" rows="3" >{product_details}</textarea>
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="description" class="col-sm-4 col-form-label"><?php echo display('category') ?></label>
                                    <div class="col-sm-8">
                                        <select name="category_id" class="form-control">
                                            {category_list}
                                                <option value="{category_id}">{category_name} </option>
                                            {/category_list}
                                            <?php
                                            if ($category_selected) {   
                                            ?>
                                            {category_selected}
                                                <option selected value="{category_id}">{category_name} </option>
                                            {/category_selected}
                                            <?php
                                                }else{
                                            ?>
                                            <option selected value="0"><?php echo display('category_not_selected')?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                               <div class="form-group row">
                                    <label for="description" class="col-sm-4 col-form-label"><?php echo display('image') ?></label>
                                    <div class="col-sm-8">
                                        <input type="file" name="image" class="form-control">
                                        <img class="img img-responsive text-center" src="{image}" height="80" width="80" style="padding: 5px;">
                                         <input type="hidden" value="{image}" name="old_image">
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sell_price') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('supplier_price') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('model') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('tax') ?> %</th>
                                        <th class="text-center"><?php echo display('supplier') ?> <i class="text-danger">*</i></th>
                                    </tr>
                                </thead>
                                <tbody id="form-actions">
                                    <tr class="">
                                        <td class="">
                                            <input class="form-control text-right" name="price" type="number" value="{price}" required="" placeholder="<?php echo display('sell_price') ?>" tabindex="3">
                                        </td>
                                        <td class="text-right">
                                            <input type="number" tabindex="4" class="form-control text-right" value="{supplier_price}" name="supplier_price" placeholder="<?php echo display('supplier_price') ?>"  required />
                                        </td>
                                        <td class="text-right">
                                            <input type="text" tabindex="5" class="form-control" value="{product_model}" name="model" placeholder="<?php echo display('model') ?>"  required />
                                        </td>
                                           <td class="text-right">
                                            <select name="tax" class="form-control">
                                            {tax_list}
                                                <option value="{tax}">{tax} % 
                                                </option>
                                            {/tax_list}
                                            </select>
                                        </td>
                                        <td class="text-right">
                                            <select name="supplier_id" class="form-control">
                                                {supplier_list}
                                                    <option value="{supplier_id}">{supplier_name} </option>
                                                {/supplier_list}
                                                <?php
                                                    if ($supplier_selected) {
                                                ?>
                                                {supplier_selected}
                                                    <option selected value="{supplier_id}">{supplier_name} </option>
                                                {/supplier_selected}
                                                <?php
                                                    }else{
                                                ?>
                                                <option selected value="0"><?php echo display('supplier_not_selected')?></option>
                                                <?php 
                                                    }
                                                ?>
                                             </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="submit" id="add-product" class="btn btn-success btn-large" name="add-product" value="<?php echo display('save_changes') ?>" />
                            </div>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit Product End -->



