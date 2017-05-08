<!--header start-->

<header class="header fixed-top clearfix"> 
  <!--logo start-->
  <div class="brand">
      <div class="sidebar-toggle-box">
          <div class="fa fa-bars"><img src="<?php echo base_url('assets/images/menu-icon.png') ; ?>" alt=""/>
          </div>

      </div>
      <a href="<?php echo site_url(ADMIN_DIR."dashboard");?>" class="logo"> <img src="<?php echo base_url('assets/images/logo.png') ?>" alt="<?php echo ADMIN_TITLE; ?>"></a>

  </div>
    <?php if(sessionVar('switch_customer')!=""){
        $accc_name = $this->db->query("SELECT acc_name FROM accounts WHERE acc_id = '".$this->session->userdata['user_info']->parent_child."'")->row();
        ?>
    <div style="  background: #65B045;
      color: #fff;
      padding: 2px 15px 2px 15px;
      border: 0 solid #fff;

      float: right;
      z-index: 100000;
      position: absolute;
          right: 100px;
      margin-top: 5px;
      text-align: center;"> You are currently viewing the account "<?php echo $accc_name->acc_name;?>" <br><a href="<?php echo site_url(ADMIN_DIR."my_numbers_reports/returnAccount");?>"> Click here </a>  to return your account</div>
    <?php }?>
  <!--logo end-->

  <div class="top-nav clearfix"> 
    <!--search & user info start-->
    <ul class="nav pull-right top-menu top-menu-right">
      <!-- user login dropdown start--> 
    <!-- <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                fdfd
            </a>

        </li>

        <li>

        <div class="toggle-right-box">
          <div class="fa fa-bars"> <i class="fa fa-chevron-left act" id="op-tog"></i> <img src="<?/*=base_url('assets/images/help-quiz.png') ; */?>" style="margin-top:3px;" alt=""> <i class="fa fa-chevron-right" style="display:none" id="cl-tog"></i> </div>



        </div>
      </li>-->



      <?php if(sessionVar('user_id')!=""){?>

      <!--<div class="help_section pull-right" id="mf12">

          <a href="javascript:;" class="help help_closed" id="mfa4"></a>
          
        </div>-->


         <?php }
         ?>
    </ul>

    <!--search & user info end--> 
  </div>
</header>
<!--header end-->