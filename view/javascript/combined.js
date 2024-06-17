//IMEI JS START
$(function(){

  function isIMEIValid(imei){
  
    if (!/^[0-9]{15}$/.test(imei)) {return false;}
    
    var sum = 0, factor = 2, checkDigit, multipliedDigit;
    
    for (var i = 13, li = 0; i >= li; i--) {
      multipliedDigit = parseInt(imei.charAt(i), 10) * factor;
      sum += (multipliedDigit >= 10 ? ((multipliedDigit % 10) + 1) : multipliedDigit);
      (factor === 1 ? factor++ : factor--);
    }
    checkDigit = ((10 - (sum % 10)) % 10);
    
    return !(checkDigit !== parseInt(imei.charAt(14), 10))
  }

  var REPORTING_BODYS = [
    {
      number: 0,
      name: 'Test IMEI',
      location: 'Nations with 2-digit CCs'
    },{
      number: 1,
      name: 'PTCRB',
      location: 'United States'
    },{
      number: 2,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 3,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 4,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 5,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 6,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 7,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 8,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 9,
      name: 'Test IMEI',
      location: 'Nations with 3-digit CCs'
    },{
      number: 10,
      name: 'DECT devices',
      location: 'nobody knows'
    },{
      number: 30,
      name: 'Iridium',
      location: 'United States (satellite phones)'
    },{
      number: 33,
      name: 'DGPT',
      location: 'France'
    },{
      number: 35,
      name: 'BABT',
      location: 'United Kingdom'
    },{
      number: 44,
      name: 'BABT',
      location: 'United Kingdom'
    },{
      number: 45,
      name: 'NTA',
      location: 'Denmark'
    },{
      number: 49,
      name: 'BZT / BAPT',
      location: 'Germany'
    },{
      number: 50,
      name: 'BZT ETS',
      location: 'Germany'
    },{
      number: 51,
      name: 'Cetecom ICT',
      location: 'Germany'
    },{
      number: 52,
      name: 'Cetecom',
      location: 'Germany'
    },{
      number: 53,
      name: 'TUV',
      location: 'Germany'
    },{
      number: 54,
      name: 'Phoenix Test Lab',
      location: 'Germany'
    },{
      number: 91,
      name: 'MSAI',
      location: 'India'
    },{
      number: 98,
      name: 'BAPT',
      location: 'United Kingdom'
    }
  ];

  function getReportingBody(num){
    for (var i = 0, l = REPORTING_BODYS.length; i < l;i++){
      if (num === REPORTING_BODYS[i].number){
        return REPORTING_BODYS[i]
      }
    }
    return null;
  }
  
  function validateimei(){
    $('#controls').fadeOut(500,function (){
    
      var msg, imei = $('#imei').val(), r;
            
      if ( imei !== '' && isIMEIValid(imei) ){
        r = getReportingBody(parseInt(imei.substring(0,2)));
        msg = (r !== null) 
          ? "It's a valid one, the Reporting Body is " + r.name + " from " + r.location + "."
          : "It's a valid one, but the Reporting Body is unknown."
      }else{      
        msg = 'Sorry, this is invalid IMEI.'
      }

    });          
  }
});
//Jquery Number min JS START
(function(f){f.number=function(b,c,d,e){b=(b+"").replace(/[^0-9+\-Ee.]/g,"");b=!isFinite(+b)?0:+b;c=!isFinite(+c)?0:Math.abs(c);e=typeof e==="undefined"?",":e;d=typeof d==="undefined"?".":d;var a="";a=function(g,i){var h=Math.pow(10,i);return""+Math.round(g*h)/h};a=(c?a(b,c):""+Math.round(b)).split(".");if(a[0].length>3)a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,e);if((a[1]||"").length<c){a[1]=a[1]||"";a[1]+=Array(c-a[1].length+1).join("0")}return a.join(d)};f.fn.number=function(b,c,d,e){if(b===
true)return this.each(function(){var a=f(this),g=+a.text().replace(/[^.0-9]/,"");a.number(isNaN(g)?0:+g,c,d,e)});return this.text(f.number.apply(window,arguments))}})(jQuery);
//Jquery TABS JS START
$.fn.tabs = function() {
	var selector = this;
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('href')).fadeIn();
			
			return false;
		});
	});

	$(this).show();
	
	$(this).first().click();
};