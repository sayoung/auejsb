if(jQuery(window).width() > 719){ 
    var myFullpage = new fullpage('#fullpage', {
        sectionsColor: ['#1bbc9b', '#4BBFC3', '#7BAABE', '#4a52a1', '#ccddff', '#7BAABE'],
        anchors: ['Accueil', 'E-services', 'Actualités', 'Publications','Media', 'Contact'],
        navigationTooltips: ['Accueil', 'E-services', 'Actualités', 'Publications', 'Media' , 'Contact'],
        showActiveTooltip: false,
        licenseKey: '2375FC98-5990473D-A3474621-FD11215A',
        menu: '#menu',
       navigation: true,
        //equivalent to jQuery `easeOutBack` extracted from http://matthewlein.com/ceaser/
        easingcss3: 'cubic-bezier(0.175, 0.885, 0.320, 1.275)',
onLeave: function(origin, destination, direction){
		//it won't scroll if the destination is the 3rd section

		if(origin.index == 0 && direction =='down'){
		jQuery("#wrapper").addClass("hidewrapper");
		}
		if(destination.index == 0){
			 jQuery("#wrapper").removeClass("hidewrapper");
		}
	}
    });
     }
	 



  	
var $ = jQuery.noConflict();
 
$(document).ajaxComplete(function(){
 

$('.feild-produit-vent [id^="edit-submit"]').on('click', function(e){
       e.preventDefault();
       
     //  var a_qnt_max = $('[id^="edit-purchased-entity-0-attributes-attribute-la-quantite-maximale"]');
     
	   var qnt_max =   $('[id^="edit-purchased-entity-0-attributes-attribute-la-quantite-maximale"] option:selected').text();
	  var  qnt_achete =  document.getElementsByName("quantity[0][value]")[0].value;
	//   var qnt_achete = document.getElementById(x).value; 
       var a_form = $('[id^="commerce-order-item-add-to-cart-form-commerce-product"]');
var a_btn = $('[id^="edit-submit"]');

       
     if ( qnt_achete > qnt_max) {
           
            var para = document.createElement("P");                       // Create a <p> node
var t = document.createTextNode("La quantité maximale est :"  + qnt_max );      // Create a text node
para.appendChild(t);     
document.getElementById('msg_qnt').innerHTML = "";
// Append the text to <p>
document.getElementById("msg_qnt").appendChild(para);     
           //   var element = document.getElementById("edit-field-avis-pref-target-id-shs-0-0"); 
          
              
 // element.classList.add("error");
          
     
}  else {

          $('[id^="commerce-order-item-add-to-cart-form-commerce-product"]').submit();
       }

	  
   
    })
});
$('.feild-produit-vent [id^="edit-submit"]').on('click', function(e){
       e.preventDefault();
       
     //  var a_qnt_max = $('[id^="edit-purchased-entity-0-attributes-attribute-la-quantite-maximale"]');
     
	   var qnt_max =   $('[id^="edit-purchased-entity-0-attributes-attribute-la-quantite-maximale"] option:selected').text();
	  var  qnt_achete =  document.getElementsByName("quantity[0][value]")[0].value;
	//   var qnt_achete = document.getElementById(x).value; 
       var a_form = $('[id^="commerce-order-item-add-to-cart-form-commerce-product"]');
var a_btn = $('[id^="edit-submit"]');

       
     if ( qnt_achete > qnt_max) {
           
            var para = document.createElement("P");                       // Create a <p> node
var t = document.createTextNode("La quantité maximale est :"  + qnt_max );      // Create a text node
para.appendChild(t);     
document.getElementById('msg_qnt').innerHTML = "";
// Append the text to <p>
document.getElementById("msg_qnt").appendChild(para);     
           //   var element = document.getElementById("edit-field-avis-pref-target-id-shs-0-0"); 
          
              
 // element.classList.add("error");
          
     
}  else {

          $('[id^="commerce-order-item-add-to-cart-form-commerce-product"]').submit();
       }

	  
   
    })

    
