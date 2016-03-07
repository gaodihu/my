 <div class="min-nav">
    <a href="" class="home" title='Home'></a> > 
    <?php foreach($breadcrumbs as $breadcrumb){ ?>
    <a href="<?php echo $breadcrumb['href'];?>"><?php echo $breadcrumb['text'];?></a><?php if($breadcrumb['sep']){ ?> > <?php } ?>
    <?php } ?>
</div>