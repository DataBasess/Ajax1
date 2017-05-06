<!DOCTYPE html>
<html>
    <head>
        <title>packege</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
        <!-- CSS Libs -->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/bootstrap.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/font-awesome.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/animate.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/bootstrap-switch.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/checkbox3.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/jquery.dataTables.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/dataTables.bootstrap.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/select2.min.css');?>">
        <!-- CSS App -->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/style.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('resources/css/flat-blue.css');?>">
    </head>
    <!-- Javascript Libs -->
        <script type="text/javascript" src="<?php echo site_url('resources/js/jquery.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/bootstrap.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/Chart.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/bootstrap-switch.min.js');?>"></script>

        <script type="text/javascript" src="<?php echo site_url('resources/js/jquery.matchHeight-min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/jquery.dataTables.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/dataTables.bootstrap.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/select2.full.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/ace.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/mode-html.js');?>"></script>
        <script type="text/javascript" src="<?php echo site_url('resources/js/theme-github.js');?>"></script>
        <!-- Javascript -->
        <script type="text/javascript" src="<?php echo site_url('resources/js/app.js');?>"></script>
        <script type="text/javascript" src=" <?php echo base_url(); ?>js/jquert-3.2.1.js "></script>
        
    <body class="flat-blue">
        <div class="app-container">
            <div class="row content-container">
                <nav class="navbar navbar-default navbar-fixed-top navbar-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-expand-toggle">
                                <i class="fa fa-bars icon"></i>
                            </button>
                            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                                <i class="fa fa-th icon"></i>
                            </button>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                                <i class="fa fa-times icon"></i>
                            </button>
                            
                            <li class="dropdown profile">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Emily Hart <span class="caret"></span></a>
                                <ul class="dropdown-menu animated fadeInDown">
                                    <li class="profile-img">
                                        <img src="<?php echo site_url('resources/img/picjumbo.com_HNCK4153_resize.jpg');?>" class="profile-img">
                                    </li>
                                    <li>
                                        <div class="profile-info">
                                            <h4 class="username">Emily Hart</h4>
                                            <p>emily_hart@email.com</p>
                                            <div class="btn-group margin-bottom-2x" role="group">
                                                <button type="button" class="btn btn-default"><i class="fa fa-user"></i> Profile</button>
                                                <button type="button" class="btn btn-default"><i class="fa fa-sign-out"></i> Logout</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="side-menu sidebar-inverse">
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="side-menu-container">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#">
                                    <div class="icon fa fa-paper-plane"></div>
                                    <div class="title">packege</div>
                                </a>
                                <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                                    <i class="fa fa-times icon"></i>
                                </button>
                            </div>
                            <ul class="nav navbar-nav">
								<li>
                                    <a data-toggle="collapse" href="#dropdown_date_no_sale">
                                        <span class="icon fa fa-table"></span><span class="title">Date No Sale</span>
                                    </a>
                                    <div id="dropdown_date_no_sale" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="nav navbar-nav">
												<li>
                                                    <a href="<?php echo site_url('date_no_sale/add');?>">Add</a>
                                                </li>
												<li>
                                                    <a href="<?php echo site_url('date_no_sale/index');?>">Listing</a>
                                                </li>
											</ul>
                                        </div>
                                    </div>
                                </li>
								<li>
                                    <a data-toggle="collapse" href="#dropdown_ticket">
                                        <span class="icon fa fa-table"></span><span class="title">Ticket</span>
                                    </a>
                                    <div id="dropdown_ticket" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="nav navbar-nav">
												<li>
                                                    <a href="<?php echo site_url('ticket/add');?>">Add</a>
                                                </li>
												<li>
                                                    <a href="<?php echo site_url('ticket/index');?>">Listing</a>
                                                </li>
											</ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </nav>
                </div>
                <!-- Main Content -->
                <div class="container-fluid">
                    <div class="side-body">
                        <?php                        
                        if(isset($_view) && $_view)
                            $this->load->view($_view);
                        ?>
                    </div>
                </div>
            </div>
            <footer class="app-footer">
                <div class="wrapper">
                    <span class="pull-right">Generated By <a href="http://www.crudigniter.com/">CRUDigniter</a> 3.0 Beta <a href="#"><i class="fa fa-long-arrow-up"></i></a></span>
                </div>
            </footer>
        </div>
        
    </body>
</html>
