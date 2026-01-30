 if(jQuery(window).width() < 768){ 
       jQuery('#Layer_1 path').on('click', function () {
            
            jQuery('#Layer_1 path').removeClass("cls_active");
            jQuery(this).addClass("cls_active");
            
        	var pos = jQuery(this).index();
            
			
			jQuery(".detailCarte").fadeOut();
			jQuery(".detailCarte").eq(pos).fadeIn();
		
});
    } else 
	{
		window.pepe = new Vue({
  el: '#app',
  name: 'app',
  data: function(){
    return {
      options: {
		licenseKey: '2375FC98-5990473D-A3474621-FD11215A',
        afterLoad: this.afterLoad,
        scrollBar: false,
        menu: '#menu',
        navigation: true,
        autoScrolling: true,
        navigationTooltips: ['Accueil', 'Services', 'Actualités', 'Chiffres clés' ,'Medias', 'Contact'],
        showActiveTooltip: true,
        anchors: ['Accueil', 'Services', 'Actualite', 'Chiffres', 'Medias', 'Contact'],
        sectionsColor: ['#41b883', '#057d62', '#2693fe', '#e7e7e7', '#dbbe72', '#e7e7e7', '#018769', '#ba5be9', '#b4b8ab'],
responsiveWidth: 768,
responsiveHeight: 600,
navigation: true,
		onLeave: function(index, nextIndex, direction) {
      var leavingSection = jQuery(this);

      //after leaving section 2
      if (direction == 'down') {
        jQuery('.header-v3').stop().animate({"top":-150},400);
		
      } else if(direction == 'up') {
        
        if(jQuery('#fp-nav li').eq(1).find("a").hasClass("active")){
            
            jQuery('.header-v3').stop().animate({"top":0},400);
        }
		
      }
    }
      }
    }
  },
  methods: {
   
    addSection: function(e) {
      e.preventDefault();
      var newSectionNumber = document.querySelectorAll('.fp-section').length + 1;

      // creating the section div
      var section = document.createElement('div');
      section.className = 'section';
      section.innerHTML = '<h3>Section' + newSectionNumber + '</h3>';

      // adding section
      document.querySelector('#fullpage').appendChild(section);

      // creating the section menu element
      var sectionMenuItem = document.createElement('li');
      sectionMenuItem.setAttribute('data-menuanchor', 'page' + newSectionNumber);
      sectionMenuItem.innerHTML = '<a href="#page${newSectionNumber}">Section ' + newSectionNumber + '</a>';

      // adding it to the sections menu
      var sectionsMenuItems = document.querySelector('#menu')
      sectionsMenuItems.appendChild(sectionMenuItem)

      // adding anchor for the section
      this.options.anchors.push('page' + newSectionNumber)

      // we have to call `update` manually as DOM changes won't fire updates
      // requires the use of the attribute ref="fullpage" on the
      // component element, in this case, <full-page>
      // ideally, use an ID element for that element too
      this.$refs.fullpage.build()
    },
    removeSection: function() {
      var sections = document.querySelector('#fullpage').querySelectorAll('.fp-section')
      var lastSection = sections[sections.length - 1]

      // removing the last section
      lastSection.parentNode.removeChild(lastSection)

      // removing the last anchor
      this.options.anchors.pop()

      // removing the last item on the sections menu
      var sectionsMenuItems = document.querySelectorAll('#menu li')
      var lastItem = sectionsMenuItems[sectionsMenuItems.length - 1]
      lastItem.parentNode.removeChild(lastItem)
    },
    toggleNavigation: function() {
      this.options.navigation = !this.options.navigation
    },
    toggleScrollbar: function() {
      this.options.scrollBar = !this.options.scrollBar
    }
	
  }

})
jQuery('#fp-nav li').eq(0).on('click', function () {
        jQuery('.header-v3').stop().animate({"top":0},400);
});
	
jQuery('#Layer_1 path').on('click', function () {
            
            jQuery('#Layer_1 path').removeClass("cls_active");
            jQuery(this).addClass("cls_active");
            
        	var pos = jQuery(this).index();
            
			
			jQuery(".detailCarte").fadeOut();
			jQuery(".detailCarte").eq(pos).fadeIn();
		
});
	}


jQuery(document).ready(function() {

    if(jQuery(window).width() < 768){ 
	    var mediaWidth = jQuery(window).width();
		if(mediaWidth < 480){

            jQuery(".tt-actua2").find(".tt-actualites").empty().append("<i class='fa fa-plus' aria-hidden='true'></i>");
        }else{

            jQuery(".tt-actua2").find(".tt-actualites").empty().append("Toute l'actualités");
        }
           var h = 500; 
    //alert(h);
    
    jQuery('.gavias_sliderlayer').height(h);
    jQuery('#section0 .fp-tableCell').height(h);
    jQuery('#section0').height(h);
    jQuery('#block-gavias-castron-gaviassliderlayerslidermain').height(h);
    jQuery('#block-gavias-castron-gaviassliderlayerslidermain > div').height(h);
    jQuery('.rev_slider.fullwidthabanner').height(h);
    jQuery('.rev_slider.fullwidthabanner ul').height(h);
    jQuery('.rev_slider.fullwidthabanner li').height(h);
    


    

    
    jQuery( window ).resize(function() {
            //------------------ carte format ecran --------------------------
            var h = 500; 
            jQuery('.gavias_sliderlayer').height(h);
            jQuery('#section0 .fp-tableCell').height(h);
            jQuery('#section0').height(h);
    
    });
	 jQuery('path').hover(function(){

            var title = jQuery(this).attr('title');
            jQuery('.spanTooltips').empty().append(title).stop().fadeIn("slow");

        }, function() {
                jQuery('.spanTooltips').stop().fadeOut("slow");

        }).mousemove(function(e) {

                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY - 50; //Get Y coordinates
                jQuery('.spanTooltips').css({ top: mousey, left: mousex, opacity: 1 })

        });
    } else {
		    
    
    var h = jQuery(window).height(); 
    //alert(h);
    
    jQuery('.gavias_sliderlayer').height(h);
    jQuery('#section0 .fp-tableCell').height(h);
    jQuery('#section0').height(h);
    jQuery('#block-gavias-castron-gaviassliderlayerslidermain').height(h);
    jQuery('#block-gavias-castron-gaviassliderlayerslidermain > div').height(h);
    jQuery('.rev_slider.fullwidthabanner').height(h);
    jQuery('.rev_slider.fullwidthabanner ul').height(h);
    jQuery('.rev_slider.fullwidthabanner li').height(h);
    
    h = h - 100;
    jQuery('.imahechifre').height(h);
    //------------------ actualites format ecran --------------------------
    var hs = jQuery(".services-1").height(); 
    mrg = (h - hs)/2;
    jQuery(".services-1").css("marginTop", mrg);
    
    //----height e service
    
    var h = jQuery(window).height(); 
    h = h - 43;
    jQuery('.flex-slide').height(h);
    
    jQuery( window ).resize(function() {
            //------------------ carte format ecran --------------------------
            var h = jQuery(window).height(); 
            jQuery('.gavias_sliderlayer').height(h);
            jQuery('#section0 .fp-tableCell').height(h);
            jQuery('#section0').height(h);
    
            h = h - 100;
            jQuery('.imahechifre').height(h);
            //------------------ actualites format ecran --------------------------
            var hs = jQuery(".services-1").height(); 
            mrg = (h - hs)/2;
            jQuery(".services-1").css("marginTop", mrg);
    });
	}
    
jQuery('.commission-pp #node-single-comment').append('<iframe src="https://tequality.ma/commission/programme_pp.php" frameborder="0" width="100%"> <p>Your browser does not support iframes.</p> </iframe>');
     
    jQuery('.commission-gp #node-single-comment').append('<iframe src="https://tequality.ma/commission/programme_gp.php" frameborder="0" width="100%"> <p>Your browser does not support iframes.</p> </iframe>');
    
    jQuery('iframe').load(function(){jQuery(this).height(jQuery(this).contents().outerHeight());});

    /* jQuery('path').hover(function(){

            var title = jQuery(this).attr('title');
            jQuery('.spanTooltips').empty().append(title).stop().fadeIn("slow");

        }, function() {
                jQuery('.spanTooltips').stop().fadeOut("slow");

        }).mousemove(function(e) {

                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY - 50; //Get Y coordinates
                jQuery('.spanTooltips').css({ top: mousey, left: mousex, opacity: 1 })

        }); */ 
		function getStyle(el,styleProp)
{
	var x = document.getElementById(el) || document.body;
	if (x.currentStyle)
		var y = x.currentStyle[styleProp];
	else if (window.getComputedStyle)
		var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
	return y;
}
 var xdir = (getStyle(null, 'direction'));

		if (xdir == 'ltr') {
	jQuery('path').hover(function(){

            var title = jQuery(this).attr('title');
            jQuery('.spanTooltips').empty().append(title).stop().fadeIn("slow");

        }, function() {
                jQuery('.spanTooltips').stop().fadeOut("slow");

        }).mousemove(function(e) {

                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY - 50; //Get Y coordinates
                jQuery('.spanTooltips').css({ top: mousey, left: mousex, opacity: 1 })

        });
} else {
	jQuery('path').hover(function(){

            var title = jQuery(this).attr('title');
            jQuery('.spanTooltipsright').empty().append(title).stop().fadeIn("slow");

        }, function() {
                jQuery('.spanTooltipsright').stop().fadeOut("slow");

        }).mousemove(function(e) {

                var mousex = e.pageX - 100; //Get X coordinates
                var mousey = e.pageY - 100; //Get Y coordinates
                jQuery('.spanTooltipsright').css({ top: mousey, left: mousex, opacity: 1 }) 

        });

}
        
        
    jQuery(".footermap-item--depth-2").prepend('<i class="fa fa-angle-double-right" aria-hidden="true"></i>');
         
        });


