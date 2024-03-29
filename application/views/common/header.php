    <!-- Navigation
    ==========================================-->
    <nav id="hk-menu" class="navbar navbar-default navbar-top">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only"><?php echo $this->lang->line("Toggle navigation"); ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url(); ?>"><?php echo $this->lang->line("DoctApp"); ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#tf-contact" class="page-scroll"><?php echo $this->lang->line("Contact No"); ?></a></li>
            <?php if(_is_frontend_user_login($this)){
                ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="glyphicon glyphicon-user"></i> <span class="hidden-xs"><?php echo _get_current_user_name($this); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- Menu Footer-->
                      <li>
                        <a href="<?php echo site_url("users/profile"); ?>"><?php echo $this->lang->line("Profile"); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo site_url("users/appointments"); ?>"><?php echo $this->lang->line("My Appointments"); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo site_url("login/signout?callbackurl=".current_url()); ?>" ><?php echo $this->lang->line("Sign out"); ?></a>
                      </li>
                    </ul>
                  </li>
                <?php
            }else{ ?>
            <li><a href="<?php echo site_url("login/?callbackurl=".current_url()); ?>" class="btn btn-default" ><?php echo $this->lang->line("Login User"); ?></a></li>
            <?php } ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>