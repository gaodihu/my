(function ($){
  var product = { __version: '131218' };
	
  // product image gallery
  var _gallery = function () {
    // body...
    $('.thumbnails li a').each(function (){
      var $this = $(this),
        src = $this.attr('data-image').replace('_small.jpg', '.jpg');
      $this.attr('data-image', src);
    });
	alert("aa");
    // create the image swap from the gallery 
    $('#thumbnails').on('mouseenter', 'a', function (e) {
      var $this = $(this),
        $easyzoom = $('.easyzoom'),
        $zoomIcon = $easyzoom.find('.zoom-icon'),
        $img = $easyzoom.find('img'),
        url = $this.attr('data-image');

      // set a class on the currently active gallery image
      if ($this.hasClass('active')){
        return;
      } else {
        $('#thumbnails').find('.active').removeClass('active');
        $this.addClass('active');
        $easyzoom.find('.loading').removeClass('hidden');
        // clear easyzoom effect
        product.image.easyzoom().teardown();
        $img.parent().attr('href', url);
        $img.attr('src', url);

        var img = new Image();
        img.src = url;
        img.onload = function () {
          if (img.width < 400 || img.height < 400) {
            $img.get(0).style.cursor = 'default';
            $zoomIcon.addClass('hidden');
          } else if ($zoomIcon.hasClass('hidden')){
            $img.get(0).style.cursor = '';
            $zoomIcon.removeClass('hidden');
          }
          $easyzoom.find('.loading').addClass('hidden');
          product.image.easyzoom();
        }
      }
    });
  }
  product.image = {
    init: function () {
      
      var $img = $('.easyzoom').find('img');
      var url = $img.attr('src');
      var img = new Image();
      img.src = url;

      if (!img.complete || !img.width) {
        $img.prev('.loading').removeClass('hidden');
        img.onload = function () {
          $img.prev('.loading').addClass('hidden');
          if (img.width < 400 || img.height < 400) {
            $img.get(0).style.cursor = 'default';
            $('.zoom-icon').addClass('hidden');
          }
        }
      }

      this.thumbnails();
      this.easyzoom();
      this.gallery();
      this.colorful();
    },
    colorful: function () {
      $('.colorbox').bxSlider({
        infiniteLoop: false,
        hideControlOnEnd: true,
        slideWidth: 360,
        pager: false,
      }).parents('.bx-wrapper').addClass('colorful-slide');

      // bind click event
      $('.colorbox').on('click', 'a', function (e) {

        var $this = $(this),
          sku = $this.data('sku');

        if ($this.hasClass('active')) {
          return;
        } else {
          $('.colorbox').find('a').removeClass('active');
          $this.addClass('active');

          // get image
          $.ajax({
            url: common.settings.dual + '/product/get_image/' + sku,
            type: 'GET',
            cache: true,
            dataType: 'json'
          }).done(function (data) {
            if (data.code === 0) {
              $('.thumbnails-slide').remove();
              $('#thumbnails-tmpl').tmpl(data.result).insertBefore('.colorful-slide');
              product.image.thumbnails();
              product.image.gallery();
              $('#thumbnails').find('a').eq(0).trigger('mouseenter');
            }
          });
        }
      });
    },
    easyzoom: function () {
      // Instantiate EasyZoom plugin and return the instance API
      return $('.easyzoom').easyZoom().data('easyZoom');
    },
    gallery: _gallery,
    thumbnails: function (){
      $('.thumbnails').bxSlider({
        infiniteLoop: false,
        hideControlOnEnd: true,
        mode: 'vertical',
        slideWidth: 100,
        pager: false
      }).parents('.bx-wrapper').addClass('thumbnails-slide');
    }
  };

  //var _init = function () {
//    product.image.init();
//    //product.tabs.init();
//    product.rating.init();
//   // product.countdown.init();
//   // product.comment.init();
//    //product.loginDialog.init();
//  }
//  product.init = _init;

  //var exports = this;
  //exports.product = product;
})(jQuery);