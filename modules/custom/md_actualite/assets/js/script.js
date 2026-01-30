var mediaWidth = $(window).width();
       var txt = Drupal.t('+ d’actualités');
		if(mediaWidth < 480){
            $(".tp-cc").find(".cr-btn-small").empty().append("<h5><i class='fas fa-plus'></i></h5>");
        }else{
            $(".tp-cc").find(".cr-btn-small").empty().append("<h5> " +  txt +  "</h5>");
        }