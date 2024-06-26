
(function($){

	$.fn.socialTrafficPop = function(options){
	
			/* Setup the options for the tooltip that can be
			   accessed from outside the plugin              */
			var defaults = {
				
				// Configure display of popup
				title: "Social Traffic Pop",
				message: "Share Social Traffic Pop with your friends and see what happens!",
				closeable: true,
				advancedClose: true,
				opacity: '0.3',
				// Configure services
				facebook_on: true,
				google_on: true,
				twitter_on: true,
				// Confifgure Google
				google_url: "http://www.unlockriver.com/",
				google_annotation: "bubble",
				google_size: "standard",
				// Configure Facebook
				fb_url: "http://www.unlockriver.com/",
				fb_layout: "button_count",
				fb_showfaces: false,
				fb_color_scheme: 'light',
				// Configure Twitter
				twitter_user: "RiverUnlock",
				twitter_method: "tweet",
				tweet_url: null,
				tweet_text: null,
				tweet_count: 'horizontal',
				tweeted_by: false,
				// Set timers
				timeout: 25,
				wait: 0,
				delay: 0
				
			};
			
			// Extend options and apply defaults if they are not set
			var options = $.extend(defaults, options);
			
			// Format's Needed?
			defaults.delay = (defaults.delay * 1000);
						
			/* Create a function that builds the popup html
			   markup. Then, prepend the popup to the body */
			getPopHTML = function(){
				
				// Set blanks
				var spClose = '';
				var services = '';
				
				// Check if the closeable is set to true
				if(defaults.closeable == true){
								
					// If so, display a close button for the pop up
					spClose = '<a href="#" onClick="stpFlush();" id="stp-close">X<a/>';
					
				}
				
				
				
				// Changed to Youtube
				if(defaults.google_on == true){
					
					services = services  + '<iframe id="fr" src="http://www.youtube.com/subscribe_widget?p=khrisf" scrolling="no" style="display:inline; width: 200px;  margin: 0px 10px" frameborder="0"></iframe>';		
				} // end Youtube
				
				// Check facebook
				if(defaults.facebook_on == true){
					services = services + '<iframe src="https://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Funlockriver&amp;width=400&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=true" scrolling="no" frameborder="0" style="border:1px solid gray; width:220px; overflow:hidden; margin-right: 8px; display:inline;  height:102px; vertical-align:top;" allowTransparency="true"></iframe>';
				} // end facebook
				
				// Check twitter
				if(!defaults.twitter_on == true){
					
					// Check to see what Twitter method to use
					if(defaults.twitter_method == "follow"){
						
						var twitter_disp = '<div class="stp-button"><a id="spTwitter" href="http://twitter.com/'+defaults.twitter_user+'" class="twitter-follow-button" data-show-count="false">Follow @'+defaults.twitter_user+'</a></div>';
						
					} else {
												
						if(defaults.tweet_url != null){var tbtn_url = 'data-url="'+defaults.tweet_url+'"';}else{var tbtn_url = '';}
						if(defaults.tweet_text != null){var tbtn_text = 'data-text="'+defaults.tweet_text+'"';}else{var tbtn_text = '';}
						if(defaults.tweeted_by == true && defaults.twitter_user != ''){var tbtn_by = 'data-via="'+defaults.tweeted_by+'"';}else{var tbtn_by = '';}
						
						var twitter_disp = '<div class="stp-button"><a href="https://twitter.com/share" class="twitter-share-button" '+tbtn_url+' '+tbtn_text+' '+tbtn_by+' data-count="'+defaults.tweet_count+'">Tweet</a></div>';
						
					}
					
					services = services + twitter_disp;
					
				} // end twitter
				
				var sPop = '<div id="stp-bg"></div><div id="stp-main"><div id="stp-title">'+spClose+''+defaults.title+'</div><div id="stp-msg">'+defaults.message+'</div><div id="stp-buttons">'+services+'<br class="step-clear" /><img src="http://www.unlockriver.com/image/logo-small.png" style="float:left" alt="logo" /> <img style="float:right" src="http://www.unlockriver.com/image/logo-small.png" alt="logo" /> <p>Subscribe & Like above to support the future of mobile unlocking services and join an awesome community!</p> </div><div id="stp-bottom"><div id="stp-counter">Subscribe or wait <span id="stp-count"></span> seconds.</div></div></div>';
															
				// Return the pop up markup
				return sPop;
				
			}; // end popup generaotr
			
			// Create a variable to hold the markup ( Needed For I.E 8 6 + 7 )
			var markup = getPopHTML();
			
			// Prepend the popup into the body of the page
			$('body').append( markup );
			
			// Get cookie to see if they already clicked like
			var cook = readCookie('stpshow');
		
			// Get wait cookie
			var waitCook = readCookie('stpwait');
			
			// Override cookie if wait = 0
			if(defaults.wait == '0'){
				
				waitCook = false;
				
			} else {
								
				createCookie('stpwait', 'true', defaults.wait);
				
			}
			
			// Only show the pop up if the user has not clicked like already
			if(cook != 'true' && waitCook != 'true'){
				
				// Set delay if there is one
				setTimeout(function(){
										
					// Get window width and height to center the pop up
					var windowWidth = $(window).width();
					var windowHeight = $(window).height();
					var popupHeight = $("#stp-main").height();
					var popupWidth = $("#stp-main").width();
					var top = (windowHeight - 240) / 2 + 'px';
					
					// Simple division will let us make sure the box is centered on all screen resolutions
					$("#stp-main").css({"top": top,"left": windowWidth/2-popupWidth/2});
					$("#stp-bg").css({"height": windowHeight});
								
					// Set the background shadow active - higher opactity = darker background shadow
					$("#stp-bg").css({"opacity": defaults.opacity});
					
					// Fade in the background shadow
					$("#stp-bg").fadeIn("slow");
					
					// Fade in the popup box
					$("#stp-main").fadeIn("slow");
					
					// Check if timer is set to zero
					if(defaults.timeout == '0'){
						
						// Is so hide the counter
						$("#stp-counter").hide();
						
					} else { // otherwise start it...
									
						// Initiate the timer (more documentation on the countdown timer here: http://keith-wood.name/countdownRef.html)
						$('#stp-count').countdown({until: '+'+defaults.timeout+'s', format: 'S', compact: true, description: '', onExpiry: stpCont});
	
					} // end if timer = 0
				
				}, defaults.delay); // end delay
																
			} // End if
			
			// start escape key + outside click to close if true
			if(defaults.advancedClose == true){
				
				// detect key up
				$(document).keyup(function(e) {
					
					// of escape key
					if (e.keyCode == 27) {
						
						// dump the popup, but dont set the cookie!  
						stpFlush(false);
									   
					} // end if escape key
							  
				}); // end key up event
													
				// detect click in body and dump the popup
				$('body').click(function(){
					
					stpFlush(false);
					
				}); // end body detect
				
				// Detect a click in the popup area to prevent it from closing
				$('#stp-main').click(function(event){
					
					// Strop prop. of close event
					event.stopPropagation();
					
				}); // end popup click detection
				 
			} // end click to close + escape key to close
			
			return true;
	
	}; // End Main Function

})(jQuery); // End Plugin

// Google callback
function googleCB(){
	
	// Flush on Google share
	stpFlush(true);
   
}

// Facebook callback
FB.Event.subscribe('edge.create', function(href) {
	
	// Flush on FB share
	stpFlush(true);
   
});

// Twitter callback
function twitterCB(intent_event) {
	
	// Flush on Twitter share
	stpFlush(true);

}

// Bind Twitter events to callback
//twttr.events.bind('tweet',		twitterCB);
//twttr.events.bind('follow',		twitterCB);

//function to show continue 

function stpCont(action) {
	jQuery('#stp-bottom').html('<a href="#" onClick="stpFlush();" id="stp-continue">Continue<a/>');	
}

// function to remove the pop up from the screen
function stpFlush(action){
		
	// Check if the user completed the like or if the timer ran out
	if(action == true){
				
		// Create the cookie to remember the user clicked like, 30 is the number of days it will expire in.
		createCookie('stpshow', 'true', 30);
				
	} // End if
			
	// Fade out the background shadow
	jQuery("#stp-bg").fadeOut("slow");
			
	// Fade out the pop up itself
	jQuery("#stp-main").fadeOut("slow");
		
}	

// Begin counter code - for documentation visit: http://keith-wood.name/countdownRef.html
(function($){function Countdown(){this.regional=[];this.regional['']={labels:['Years','Months','Weeks','Days','Hours','Minutes','Seconds'],labels1:['Year','Month','Week','Day','Hour','Minute','Second'],compactLabels:['y','m','w','d'],whichLabels:null,timeSeparator:':',isRTL:false};this._defaults={until:null,since:null,timezone:null,serverSync:null,format:'dHMS',layout:'',compact:false,significant:0,description:'',expiryUrl:'',expiryText:'',alwaysExpire:false,onExpiry:null,onTick:null,tickInterval:1};$.extend(this._defaults,this.regional['']);this._serverSyncs=[]}var w='countdown';var Y=0;var O=1;var W=2;var D=3;var H=4;var M=5;var S=6;$.extend(Countdown.prototype,{markerClassName:'hasCountdown',_timer:setInterval(function(){$.countdown._updateTargets()},980),_timerTargets:[],setDefaults:function(a){this._resetExtraLabels(this._defaults,a);extendRemove(this._defaults,a||{})},UTCDate:function(a,b,c,e,f,g,h,i){if(typeof b=='object'&&b.constructor==Date){i=b.getMilliseconds();h=b.getSeconds();g=b.getMinutes();f=b.getHours();e=b.getDate();c=b.getMonth();b=b.getFullYear()}var d=new Date();d.setUTCFullYear(b);d.setUTCDate(1);d.setUTCMonth(c||0);d.setUTCDate(e||1);d.setUTCHours(f||0);d.setUTCMinutes((g||0)-(Math.abs(a)<30?a*60:a));d.setUTCSeconds(h||0);d.setUTCMilliseconds(i||0);return d},periodsToSeconds:function(a){return a[0]*31557600+a[1]*2629800+a[2]*604800+a[3]*86400+a[4]*3600+a[5]*60+a[6]},_settingsCountdown:function(a,b){if(!b){return $.countdown._defaults}var c=$.data(a,w);return(b=='all'?c.options:c.options[b])},_attachCountdown:function(a,b){var c=$(a);if(c.hasClass(this.markerClassName)){return}c.addClass(this.markerClassName);var d={options:$.extend({},b),_periods:[0,0,0,0,0,0,0]};$.data(a,w,d);this._changeCountdown(a)},_addTarget:function(a){if(!this._hasTarget(a)){this._timerTargets.push(a)}},_hasTarget:function(a){return($.inArray(a,this._timerTargets)>-1)},_removeTarget:function(b){this._timerTargets=$.map(this._timerTargets,function(a){return(a==b?null:a)})},_updateTargets:function(){for(var i=this._timerTargets.length-1;i>=0;i--){this._updateCountdown(this._timerTargets[i])}},_updateCountdown:function(a,b){var c=$(a);b=b||$.data(a,w);if(!b){return}c.html(this._generateHTML(b));c[(this._get(b,'isRTL')?'add':'remove')+'Class']('countdown_rtl');var d=this._get(b,'onTick');if(d){var e=b._hold!='lap'?b._periods:this._calculatePeriods(b,b._show,this._get(b,'significant'),new Date());var f=this._get(b,'tickInterval');if(f==1||this.periodsToSeconds(e)%f==0){d.apply(a,[e])}}var g=b._hold!='pause'&&(b._since?b._now.getTime()<b._since.getTime():b._now.getTime()>=b._until.getTime());if(g&&!b._expiring){b._expiring=true;if(this._hasTarget(a)||this._get(b,'alwaysExpire')){this._removeTarget(a);var h=this._get(b,'onExpiry');if(h){h.apply(a,[])}var i=this._get(b,'expiryText');if(i){var j=this._get(b,'layout');b.options.layout=i;this._updateCountdown(a,b);b.options.layout=j}var k=this._get(b,'expiryUrl');if(k){window.location=k}}b._expiring=false}else if(b._hold=='pause'){this._removeTarget(a)}$.data(a,w,b)},_changeCountdown:function(a,b,c){b=b||{};if(typeof b=='string'){var d=b;b={};b[d]=c}var e=$.data(a,w);if(e){this._resetExtraLabels(e.options,b);extendRemove(e.options,b);this._adjustSettings(a,e);$.data(a,w,e);var f=new Date();if((e._since&&e._since<f)||(e._until&&e._until>f)){this._addTarget(a)}this._updateCountdown(a,e)}},_resetExtraLabels:function(a,b){var c=false;for(var n in b){if(n!='whichLabels'&&n.match(/[Ll]abels/)){c=true;break}}if(c){for(var n in a){if(n.match(/[Ll]abels[0-9]/)){a[n]=null}}}},_adjustSettings:function(a,b){var c;var d=this._get(b,'serverSync');var e=0;var f=null;for(var i=0;i<this._serverSyncs.length;i++){if(this._serverSyncs[i][0]==d){f=this._serverSyncs[i][1];break}}if(f!=null){e=(d?f:0);c=new Date()}else{var g=(d?d.apply(a,[]):null);c=new Date();e=(g?c.getTime()-g.getTime():0);this._serverSyncs.push([d,e])}var h=this._get(b,'timezone');h=(h==null?-c.getTimezoneOffset():h);b._since=this._get(b,'since');if(b._since!=null){b._since=this.UTCDate(h,this._determineTime(b._since,null));if(b._since&&e){b._since.setMilliseconds(b._since.getMilliseconds()+e)}}b._until=this.UTCDate(h,this._determineTime(this._get(b,'until'),c));if(e){b._until.setMilliseconds(b._until.getMilliseconds()+e)}b._show=this._determineShow(b)},_destroyCountdown:function(a){var b=$(a);if(!b.hasClass(this.markerClassName)){return}this._removeTarget(a);b.removeClass(this.markerClassName).empty();$.removeData(a,w)},_pauseCountdown:function(a){this._hold(a,'pause')},_lapCountdown:function(a){this._hold(a,'lap')},_resumeCountdown:function(a){this._hold(a,null)},_hold:function(a,b){var c=$.data(a,w);if(c){if(c._hold=='pause'&&!b){c._periods=c._savePeriods;var d=(c._since?'-':'+');c[c._since?'_since':'_until']=this._determineTime(d+c._periods[0]+'y'+d+c._periods[1]+'o'+d+c._periods[2]+'w'+d+c._periods[3]+'d'+d+c._periods[4]+'h'+d+c._periods[5]+'m'+d+c._periods[6]+'s');this._addTarget(a)}c._hold=b;c._savePeriods=(b=='pause'?c._periods:null);$.data(a,w,c);this._updateCountdown(a,c)}},_getTimesCountdown:function(a){var b=$.data(a,w);return(!b?null:(!b._hold?b._periods:this._calculatePeriods(b,b._show,this._get(b,'significant'),new Date())))},_get:function(a,b){return(a.options[b]!=null?a.options[b]:$.countdown._defaults[b])},_determineTime:function(k,l){var m=function(a){var b=new Date();b.setTime(b.getTime()+a*1000);return b};var n=function(a){a=a.toLowerCase();var b=new Date();var c=b.getFullYear();var d=b.getMonth();var e=b.getDate();var f=b.getHours();var g=b.getMinutes();var h=b.getSeconds();var i=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;var j=i.exec(a);while(j){switch(j[2]||'s'){case's':h+=parseInt(j[1],10);break;case'm':g+=parseInt(j[1],10);break;case'h':f+=parseInt(j[1],10);break;case'd':e+=parseInt(j[1],10);break;case'w':e+=parseInt(j[1],10)*7;break;case'o':d+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break;case'y':c+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break}j=i.exec(a)}return new Date(c,d,e,f,g,h,0)};var o=(k==null?l:(typeof k=='string'?n(k):(typeof k=='number'?m(k):k)));if(o)o.setMilliseconds(0);return o},_getDaysInMonth:function(a,b){return 32-new Date(a,b,32).getDate()},_normalLabels:function(a){return a},_generateHTML:function(c){var d=this._get(c,'significant');c._periods=(c._hold?c._periods:this._calculatePeriods(c,c._show,d,new Date()));var e=false;var f=0;var g=d;var h=$.extend({},c._show);for(var i=Y;i<=S;i++){e|=(c._show[i]=='?'&&c._periods[i]>0);h[i]=(c._show[i]=='?'&&!e?null:c._show[i]);f+=(h[i]?1:0);g-=(c._periods[i]>0?1:0)}var j=[false,false,false,false,false,false,false];for(var i=S;i>=Y;i--){if(c._show[i]){if(c._periods[i]){j[i]=true}else{j[i]=g>0;g--}}}var k=this._get(c,'compact');var l=this._get(c,'layout');var m=(k?this._get(c,'compactLabels'):this._get(c,'labels'));var n=this._get(c,'whichLabels')||this._normalLabels;var o=this._get(c,'timeSeparator');var p=this._get(c,'description')||'';var q=function(a){var b=$.countdown._get(c,'compactLabels'+n(c._periods[a]));return(h[a]?c._periods[a]+(b?b[a]:m[a])+' ':'')};var r=function(a){var b=$.countdown._get(c,'labels'+n(c._periods[a]));return((!d&&h[a])||(d&&j[a])?'<span class="countdown_section"><span class="countdown_amount">'+c._periods[a]+'</span><br/>'+(b?b[a]:m[a])+'</span>':'')};return(l?this._buildLayout(c,h,l,k,d,j):((k?'<span class="countdown_row countdown_amount'+(c._hold?' countdown_holding':'')+'">'+q(Y)+q(O)+q(W)+q(D)+(h[H]?this._minDigits(c._periods[H],2):'')+(h[M]?(h[H]?o:'')+this._minDigits(c._periods[M],2):'')+(h[S]?(h[H]||h[M]?o:'')+this._minDigits(c._periods[S],2):''):'<span class="countdown_row countdown_show'+(d||f)+(c._hold?' countdown_holding':'')+'">'+r(Y)+r(O)+r(W)+r(D)+r(H)+r(M)+r(S))+'</span>'+(p?'<span class="countdown_row countdown_descr">'+p+'</span>':'')))},_buildLayout:function(c,d,e,f,g,h){var j=this._get(c,(f?'compactLabels':'labels'));var k=this._get(c,'whichLabels')||this._normalLabels;var l=function(a){return($.countdown._get(c,(f?'compactLabels':'labels')+k(c._periods[a]))||j)[a]};var m=function(a,b){return Math.floor(a/b)%10};var o={desc:this._get(c,'description'),sep:this._get(c,'timeSeparator'),yl:l(Y),yn:c._periods[Y],ynn:this._minDigits(c._periods[Y],2),ynnn:this._minDigits(c._periods[Y],3),y1:m(c._periods[Y],1),y10:m(c._periods[Y],10),y100:m(c._periods[Y],100),y1000:m(c._periods[Y],1000),ol:l(O),on:c._periods[O],onn:this._minDigits(c._periods[O],2),onnn:this._minDigits(c._periods[O],3),o1:m(c._periods[O],1),o10:m(c._periods[O],10),o100:m(c._periods[O],100),o1000:m(c._periods[O],1000),wl:l(W),wn:c._periods[W],wnn:this._minDigits(c._periods[W],2),wnnn:this._minDigits(c._periods[W],3),w1:m(c._periods[W],1),w10:m(c._periods[W],10),w100:m(c._periods[W],100),w1000:m(c._periods[W],1000),dl:l(D),dn:c._periods[D],dnn:this._minDigits(c._periods[D],2),dnnn:this._minDigits(c._periods[D],3),d1:m(c._periods[D],1),d10:m(c._periods[D],10),d100:m(c._periods[D],100),d1000:m(c._periods[D],1000),hl:l(H),hn:c._periods[H],hnn:this._minDigits(c._periods[H],2),hnnn:this._minDigits(c._periods[H],3),h1:m(c._periods[H],1),h10:m(c._periods[H],10),h100:m(c._periods[H],100),h1000:m(c._periods[H],1000),ml:l(M),mn:c._periods[M],mnn:this._minDigits(c._periods[M],2),mnnn:this._minDigits(c._periods[M],3),m1:m(c._periods[M],1),m10:m(c._periods[M],10),m100:m(c._periods[M],100),m1000:m(c._periods[M],1000),sl:l(S),sn:c._periods[S],snn:this._minDigits(c._periods[S],2),snnn:this._minDigits(c._periods[S],3),s1:m(c._periods[S],1),s10:m(c._periods[S],10),s100:m(c._periods[S],100),s1000:m(c._periods[S],1000)};var p=e;for(var i=Y;i<=S;i++){var q='yowdhms'.charAt(i);var r=new RegExp('\\{'+q+'<\\}(.*)\\{'+q+'>\\}','g');p=p.replace(r,((!g&&d[i])||(g&&h[i])?'$1':''))}$.each(o,function(n,v){var a=new RegExp('\\{'+n+'\\}','g');p=p.replace(a,v)});return p},_minDigits:function(a,b){a=''+a;if(a.length>=b){return a}a='0000000000'+a;return a.substr(a.length-b)},_determineShow:function(a){var b=this._get(a,'format');var c=[];c[Y]=(b.match('y')?'?':(b.match('Y')?'!':null));c[O]=(b.match('o')?'?':(b.match('O')?'!':null));c[W]=(b.match('w')?'?':(b.match('W')?'!':null));c[D]=(b.match('d')?'?':(b.match('D')?'!':null));c[H]=(b.match('h')?'?':(b.match('H')?'!':null));c[M]=(b.match('m')?'?':(b.match('M')?'!':null));c[S]=(b.match('s')?'?':(b.match('S')?'!':null));return c},_calculatePeriods:function(c,d,e,f){c._now=f;c._now.setMilliseconds(0);var g=new Date(c._now.getTime());if(c._since){if(f.getTime()<c._since.getTime()){c._now=f=g}else{f=c._since}}else{g.setTime(c._until.getTime());if(f.getTime()>c._until.getTime()){c._now=f=g}}var h=[0,0,0,0,0,0,0];if(d[Y]||d[O]){var i=$.countdown._getDaysInMonth(f.getFullYear(),f.getMonth());var j=$.countdown._getDaysInMonth(g.getFullYear(),g.getMonth());var k=(g.getDate()==f.getDate()||(g.getDate()>=Math.min(i,j)&&f.getDate()>=Math.min(i,j)));var l=function(a){return(a.getHours()*60+a.getMinutes())*60+a.getSeconds()};var m=Math.max(0,(g.getFullYear()-f.getFullYear())*12+g.getMonth()-f.getMonth()+((g.getDate()<f.getDate()&&!k)||(k&&l(g)<l(f))?-1:0));h[Y]=(d[Y]?Math.floor(m/12):0);h[O]=(d[O]?m-h[Y]*12:0);f=new Date(f.getTime());var n=(f.getDate()==i);var o=$.countdown._getDaysInMonth(f.getFullYear()+h[Y],f.getMonth()+h[O]);if(f.getDate()>o){f.setDate(o)}f.setFullYear(f.getFullYear()+h[Y]);f.setMonth(f.getMonth()+h[O]);if(n){f.setDate(o)}}var p=Math.floor((g.getTime()-f.getTime())/1000);var q=function(a,b){h[a]=(d[a]?Math.floor(p/b):0);p-=h[a]*b};q(W,604800);q(D,86400);q(H,3600);q(M,60);q(S,1);if(p>0&&!c._since){var r=[1,12,4.3482,7,24,60,60];var s=S;var t=1;for(var u=S;u>=Y;u--){if(d[u]){if(h[s]>=t){h[s]=0;p=1}if(p>0){h[u]++;p=0;s=u;t=1}}t*=r[u]}}if(e){for(var u=Y;u<=S;u++){if(e&&h[u]){e--}else if(!e){h[u]=0}}}return h}});function extendRemove(a,b){$.extend(a,b);for(var c in b){if(b[c]==null){a[c]=null}}return a}$.fn.countdown=function(a){var b=Array.prototype.slice.call(arguments,1);if(a=='getTimes'||a=='settings'){return $.countdown['_'+a+'Countdown'].apply($.countdown,[this[0]].concat(b))}return this.each(function(){if(typeof a=='string'){$.countdown['_'+a+'Countdown'].apply($.countdown,[this].concat(b))}else{$.countdown._attachCountdown(this,a)}})};$.countdown=new Countdown()})(jQuery); function createCookie(name,value,days){if(days){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));var expires="; expires="+date.toGMTString();} else var expires="";document.cookie=name+"="+value+expires+"; path=/";} function readCookie(name){var nameEQ=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length);} return null;} function createWait(name,value,mins) { if (mins) { var date = new Date(); date.setTime(date.getTime()+(mins*60*1000)); var expires = "; expires="+date.toGMTString(); } else var expires = ""; document.cookie = name+"="+value+expires+"; path=/"; }