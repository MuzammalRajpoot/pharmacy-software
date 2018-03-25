<?php
    $CI =& get_instance();
    $CI->load->model('Web_settings');
    $Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<!-- Admin header end -->
<header class="main-header"> 
    <a href="<?php echo base_url()?>" class="logo"> <!-- Logo -->
        <span class="logo-mini">
            <!--<b>A</b>BD-->
            <img src="<?php if (isset($Web_settings[0]['favicon'])) {
               echo $Web_settings[0]['favicon']; }?>" alt="">
        </span>
        <span class="logo-lg">
            <!--<b>Admin</b>BD-->
            <img src="<?php if (isset($Web_settings[0]['logo'])) {
               echo $Web_settings[0]['logo']; }?>" alt="">
        </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
            <span class="sr-only">Toggle navigation</span>
            <span class="pe-7s-keypad"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- settings -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('Admin_dashboard/edit_profile')?>"><i class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                        <li><a href="<?php echo base_url('Admin_dashboard/change_password_form')?>"><i class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                        <li><a href="<?php echo base_url('Admin_dashboard/logout')?>"><i class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<aside class="main-sidebar">
	<!-- sidebar -->
	<div class="sidebar">
	    <!-- Sidebar user panel -->
	    <div class="user-panel text-center">
	        <div class="image">
	            <img src="<?php echo base_url()?>assets/dist/img/user2-160x160.png" class="img-circle" alt="User Image">
	        </div>
	        <div class="info">
	            <p><?php echo $this->session->userdata('user_name')?></p>
	            <a href="#"><i class="fa fa-circle text-success"></i> <?php echo display('online') ?></a>
	        </div>
	    </div>
	    <!-- sidebar menu -->
	    <ul class="sidebar-menu">

	        <li class="<?php if ($this->uri->segment('1') == ("")) { echo "active";}else{ echo " ";}?>">
	            <a href="<?php echo base_url()?>"><i class="ti-dashboard"></i> <span><?php echo display('dashboard') ?></span>
	                <span class="pull-right-container">
	                    <span class="label label-success pull-right"></span>
	                </span>
	            </a>
	        </li>

            <!-- Invoice menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Cinvoice")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-layout-accordion-list"></i><span><?php echo display('invoice') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Cinvoice')?>"><?php echo display('new_invoice') ?></a></li>
                    <li><a href="<?php echo base_url('Cinvoice/manage_invoice')?>"><?php echo display('manage_invoice') ?></a></li>
                    <li><a href="<?php echo base_url('Cinvoice/pos_invoice')?>"><?php echo display('pos_invoice') ?></a></li>
                </ul>
            </li>
            <!-- Invoice menu end -->

            <!-- Product menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Cproduct") || $this->uri->segment('1') == ("Cqrcode") || $this->uri->segment('1') == ("Cbarcode")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-bag"></i><span><?php echo display('product') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Cproduct')?>"><?php echo display('add_product') ?></a></li>
                    <li><a href="<?php echo base_url('Cproduct/manage_product')?>"><?php echo display('manage_product') ?></a></li>
                    <?php 
                    if ($this->uri->segment(2) == "product_details") {
                    ?>
                    <li><a href="<?php echo base_url($product_statement)?>"><?php echo display('product_statement') ?></a></li>
                    <?php
                    }?>
                </ul>
            </li>
            <!-- Product menu end -->

            <!-- Customer menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Ccustomer")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="fa fa-handshake-o"></i><span><?php echo display('customer') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Ccustomer')?>"><?php echo display('add_customer') ?></a></li>
                    <li><a href="<?php echo base_url('Ccustomer/manage_customer')?>"><?php echo display('manage_customer') ?></a></li>
                    <li><a href="<?php echo base_url('Ccustomer/credit_customer')?>"><?php echo display('credit_customer') ?></a></li>
                    <li><a href="<?php echo base_url('Ccustomer/paid_customer')?>"><?php echo display('paid_customer') ?></a></li>
                </ul>
            </li>
            <!-- Customer menu end -->

            <!-- Category menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Ccategory")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-tag"></i><span><?php echo display('category') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Ccategory')?>"><?php echo display('add_category') ?></a></li>
                    <li><a href="<?php echo base_url('Ccategory/manage_category')?>"><?php echo display('manage_category') ?></a></li>
                </ul>
            </li>
            <!-- Category menu end -->

	        <!-- Supplier menu start -->
	        <li class="treeview <?php if ($this->uri->segment('1') == ("Csupplier")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-user"></i><span><?php echo display('supplier') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Csupplier')?>"><?php echo display('add_supplier') ?></a></li>
                    <li><a href="<?php echo base_url('Csupplier/manage_supplier')?>"><?php echo display('manage_supplier') ?></a></li>
                    <?php 
                    if (isset($supplier_ledger)) {
                    ?>
                    <li><a href="<?php echo base_url($supplier_ledger)?>"><?php echo display('supplier_ledger') ?></a></li>
                    <li><a href="<?php echo base_url($supplier_sales_details)?>"><?php echo display('supplier_sales_details') ?></a></li>
                    <li><a href="<?php echo base_url($supplier_sales_summary)?>"><?php echo display('supplier_sales_summary') ?></a></li>
                    <li><a href="<?php echo base_url($sales_payment_actual)?>"><?php echo display('supplier_payment_actual') ?></a></li>
                    <?php
                    }?>
                </ul>
            </li>
            <!-- Supplier menu end -->

            <!-- Purchase menu start -->
	        <li class="treeview <?php if ($this->uri->segment('1') == ("Cpurchase")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-shopping-cart"></i><span><?php echo display('purchase') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Cpurchase')?>"><?php echo display('add_purchase') ?></a></li>
                    <li><a href="<?php echo base_url('Cpurchase/manage_purchase')?>"><?php echo display('manage_purchase') ?></a></li>
                </ul>
            </li>
            <!-- Purchase menu end -->

            <!-- Stock menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Creport")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-bar-chart"></i><span><?php echo display('stock') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Creport')?>"><?php echo display('stock_report') ?></a></li>
                </ul>
            </li>
            <!-- Stock menu end -->

            
            <!-- Search menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Csearch")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-search"></i><span><?php echo display('search') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Csearch/medicine')?>"><?php echo display('medicine') ?></a></li>
                    <li><a href="<?php echo base_url('Csearch/customer')?>"><?php echo display('customer') ?> </a></li>
                    <li><a href="<?php echo base_url('Csearch/invoice')?>"><?php echo display('invoice') ?> </a></li>
                    <li><a href="<?php echo base_url('Csearch/purchase')?>"><?php echo display('purchase') ?> </a></li>
                </ul>
            </li>
            <!-- Search menu end -->

            <?php
                if ($this->session->userdata('user_type') == '1') {
            ?>

            <!-- Accounts menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Caccounts") || $this->uri->segment('2') == ("table_create") || $this->uri->segment('2') == ("table_list") || $this->uri->segment('2') == ("table_edit")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-pencil-alt"></i><span><?php echo display('accounts') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Csettings/table_create/')?>"><?php echo display('create_accounts') ?> </a></li>
                    <li><a href="<?php echo base_url('Csettings/table_list/')?>"><?php echo display('manage_accounts') ?> </a></li>
                    <li><a href="<?php echo base_url('Caccounts')?>"><?php echo display('income') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/outflow/')?>"><?php echo display('expense') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/add_tax/')?>"><?php echo display('add_tax') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/manage_tax/')?>"><?php echo display('manage_tax') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/summary/')?>"><?php echo display('accounts_summary') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/cheque_manager/')?>"><?php echo display('cheque_manager') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/closing/')?>"><?php echo display('closing') ?></a></li>
                    <li><a href="<?php echo base_url('Caccounts/closing_report/')?>"><?php echo display('closing_report') ?></a></li>
                </ul>
            </li>
            <!-- Accounts menu end -->

            <!-- Report menu start -->
            <li class="treeview <?php if ($this->uri->segment('2') == ("all_report") || $this->uri->segment('2') == ("todays_sales_report") || $this->uri->segment('2') == ("todays_purchase_report") || $this->uri->segment('2') == ("product_sales_reports_date_wise") || $this->uri->segment('2') == ("total_profit_report") ) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-book"></i><span><?php echo display('report') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Admin_dashboard/all_report')?>"><?php echo display('todays_report') ?></a></li>
                    <li><a href="<?php echo base_url('Admin_dashboard/todays_sales_report')?>"><?php echo display('sales_report') ?></a></li>
                    <li><a href="<?php echo base_url('Admin_dashboard/todays_purchase_report')?>"><?php echo display('purchase_report') ?></a></li>
                    <li><a href="<?php echo base_url('Admin_dashboard/product_sales_reports_date_wise')?>"><?php echo display('sales_report_product_wise') ?></a></li>
                    <li><a href="<?php echo base_url('Admin_dashboard/total_profit_report')?>"><?php echo display('profit_report') ?></a></li>
                </ul>
            </li>
            <!-- Report menu end -->

            <!-- Bank menu start -->
            <li class="treeview <?php if ($this->uri->segment('2') == ("index") || $this->uri->segment('2') == ("bank_list")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-briefcase"></i><span><?php echo display('settings') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Csettings/index')?>"><?php echo display('add_new_bank') ?></a></li>
                    <li><a href="<?php echo base_url('Csettings/bank_list/')?>"><?php echo display('manage_bank') ?></a></li>
                </ul>
            </li>
            <!-- Bank menu end -->

            <!-- Software Settings menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Language") ) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-settings"></i><span><?php echo display('web_settings') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('Company_setup/manage_company')?>"><?php echo display('manage_company') ?></a></li>
                    <li><a href="<?php echo base_url('User')?>"><?php echo display('add_user') ?></a></li>
                    <li><a href="<?php echo base_url('User/manage_user')?>"><?php echo display('manage_users') ?> </a></li>
                    <li><a href="<?php echo base_url('Language')?>"><?php echo display('language') ?> </a></li>
                    <li><a href="<?php echo base_url('Cweb_setting')?>"><?php echo display('setting') ?> </a></li>
                </ul>
            </li>
            <!-- Software Settings menu end -->
            <?php
                }
            ?>
	    </ul>
	</div> <!-- /.sidebar -->
</aside>