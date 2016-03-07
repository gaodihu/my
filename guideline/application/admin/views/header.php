<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title;?></title>
<base href="<?php echo $this->config->item('base_url');?>">
<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />

<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
<!-- 配置文件 -->
 <script type="text/javascript" src="resources/scripts/tinymce/tinymce.min.js"></script>
 <script type="text/javascript" src="resources/scripts/common.js"></script>
</head>
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">Guideline Admin</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="#"><img id="logo" src="resources/images/logo.png" alt="Guideline Admin" /></a>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links" style='font-size:14px;'>
				Hello, <a href="#" title="Edit your profile"><?php echo $user_name;?></a>!<br />
				<br />
				<a href="/guideline/" title="View the Site" target='_blank'>View the Site</a> | <a href="/guideline/admin/index.php/login/out" title="Sign Out">Sign Out</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				<li>
                    <?php if(isset($is_home)){ ?>
					<a href="index.php/home/index" class="nav-top-item no-submenu current"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
                    <?php }else{ ?>
                    <a href="index.php/home/index" class="nav-top-item no-submenu"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
                    <?php } ?>
						Dashboard
					</a>       
				</li>
				<li> 
                   <?php if(isset($is_guideline)){ ?>
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item -->
                    <?php }else{ ?>
                    <a href="#" class="nav-top-item">
                    <?php } ?>
					Guideline Category
					</a>
					<ul>
						<li>
                            <?php if(isset($is_guideline_catalog)){ ?>
                            <a href="index.php/g_catagory/" class="current">Category</a>
                            <?php }else{ ?>
                            <a href="index.php/g_catagory/" >Category</a>
                            <?php } ?>
                         </li>
						<li>
                            <?php if(isset($is_guideline_program)){ ?>
                            <a class="current" href="index.php/g_program">Application</a>
                            <?php }else{ ?>
                            <a  href="index.php/g_program">Application</a>
                            <?php } ?>
                        </li> <!-- Add class "current" to sub menu items also -->
					</ul>
				</li>
				<li>
                  <?php if(isset($is_faq)){ ?>
					<a href="#" class="nav-top-item current">
                   <?php }else{ ?>
                   <a href="#" class="nav-top-item">
                   <?php } ?>
						FAQ Category
					</a>
					<ul>
						<li>
                            <?php if(isset($is_faq_catagory)){ ?>
                            <a class="current" href="index.php/f_catagory">FAQ Category</a>
                            <?php }else{ ?>
                            <a  href="index.php/f_catagory">FAQ Category</a>
                            <?php } ?>
                        </li>
						<li><?php if(isset($is_faq_article)){ ?>
                            <a class="current" href="index.php/faq">FAQ Article</a>
                            <?php }else{ ?>
                            <a  href="index.php/faq">FAQ Article</a>
                            <?php } ?>
                          </li>
					</ul>
				</li>
                
                <li>
                    <?php if(isset($is_guideline_custom)){ ?>
                            <a href="#" class="nav-top-item current">
                        <?php }else{ ?>
                            <a href="#" class="nav-top-item">
                    <?php } ?>
						Custom Tag
					</a> 
                        
                   <ul>
						<li>
                            <?php if(isset($is_guideline_custom_lang_id) && $is_guideline_custom_lang_id == 1){ ?>
                            <a class="current" href="index.php/custom_tag/index?lang_id=1">English</a>
                            <?php }else{ ?>
                            <a  href="index.php/custom_tag/index?lang_id=1">English</a>
                            <?php } ?>
                        </li>
						<li><?php if(isset($is_guideline_custom_lang_id) && $is_guideline_custom_lang_id == 4){ ?>
                            <a class="current" href="index.php/custom_tag/index?lang_id=4">German</a>
                            <?php }else{ ?>
                            <a  href="index.php/custom_tag/index?lang_id=4">German</a>
                            <?php } ?>
                          </li>
                       
                       	<li><?php if(isset($is_guideline_custom_lang_id) && $is_guideline_custom_lang_id == 5){ ?>
                            <a class="current" href="index.php/custom_tag/index?lang_id=5">French</a>
                            <?php }else{ ?>
                            <a  href="index.php/custom_tag/index?lang_id=5">French</a>
                            <?php } ?>
                         </li>
                       
                        <li><?php if(isset($is_guideline_custom_lang_id) && $is_guideline_custom_lang_id == 6){ ?>
                            <a class="current" href="index.php/custom_tag/index?lang_id=6">Spanish</a>
                            <?php }else{ ?>
                            <a  href="index.php/custom_tag/index?lang_id=6">Spanish</a>
                            <?php } ?>
                         </li>
                       
                        <li><?php if(isset($is_guideline_custom_lang_id) && $is_guideline_custom_lang_id == 7){ ?>
                            <a class="current" href="index.php/custom_tag/index?lang_id=7">Italy</a>
                            <?php }else{ ?>
                            <a  href="index.php/custom_tag/index?lang_id=7">Italy</a>
                            <?php } ?>
                         </li>
                       
					</ul>     
                        
                        
				</li>
                
                
                
                <li> 
                   <?php if(isset($is_information)){ ?>
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item -->
                    <?php }else{ ?>
                    <a href="#" class="nav-top-item">
                    <?php } ?>
					Guideline Information
					</a>
					<ul>
                        <li>
                            <?php if(isset($is_information_list)){ ?>
                            <a href="index.php/information/" class="current">Information List</a>
                            <?php }else{ ?>
                            <a href="index.php/information/" >Information List</a>
                            <?php } ?>
                         </li>
					</ul>
				</li>
				<li>
                <?php if(isset($is_banner)){ ?>
					<a href="#" class="nav-top-item current">
                <?php }else{ ?>
                    <a href="#" class="nav-top-item">
                <?php } ?>
						Banner
					</a>
					<ul>
						<li>
                        <?php if(isset($is_banner_upload)){ ?>
                        <a href="index.php/banner/" class="current">Upload Banner</a>
                        <?php }else{ ?>
                        <a href="index.php/banner/">Upload Banner</a>
                        <?php } ?>
                        </li>
						
					</ul>
				</li>
				<li>
					<a href="#" class="nav-top-item">
						User
					</a>
					<ul>
						<li><a href="#">Mange User</a></li>
					</ul>
				</li>
				<li>
					<a href="#" class="nav-top-item">
						Settings
					</a>
					<ul>
						<li><a href="#">General</a></li>
						<li><a href="#">Design</a></li>
						<li><a href="#">Your Profile</a></li>
						<li><a href="#">Users and Permissions</a></li>
					</ul>
				</li>      
				
			</ul> <!-- End #main-nav -->
			
			
			
		</div></div> <!-- End #sidebar -->


 
<div id="main-content" style='padding:0px;'> <!-- Main Content Section with everything -->
			<!-- Page Head -->
            <div class="min-nav" style='padding-top:20px; padding-bottom:20px;font-size:16px;'>
                <a href="index.php/home">Home ></a>
                <?php if(isset($breadcrumbs)){ ?>
                <?php foreach($breadcrumbs as $breadcrumb){ ?>
                <a href="<?php echo $breadcrumb['href'];?>"><?php echo $breadcrumb['text'];?></a><?php if($breadcrumb['sep']){ ?> > <?php } ?>
                <?php } ?>      
                <?php } ?>
            </div>