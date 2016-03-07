<ul  class="tab-chagne a-block">
    <?php foreach($account_menus as $menu){ ?>
    <li><a href="<?php echo $menu['link'];?>"><i class="icon-caret-right"></i><?php echo $menu['text'];?></a></li>
    <?php } ?>
</ul>