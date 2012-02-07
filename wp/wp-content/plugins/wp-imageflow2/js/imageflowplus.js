/**
 *	ImageFlowPlus 1.3
 *
 *    This provides an ImageFlow style gallery plus the following great features:
 *    - Lightbox pop-ups when linking to an image
 *    - Optional linking to an external url rather than an image
 *	- Supports multiple instances and avoid collisions with other scripts by using object-oriented code
 *
 *    Copyright Bev Stofko http://www.stofko.ca
 *
 *	Version 1.1 adds auto-rotation option (May 3, 2010)
 *	Version 1.2 adds startimg option, longdesc may be link or text description (May 13, 2010)
 *	Version 1.3 fixes bug when gallery has only one image
 *	Version 1.4 don't display top box caption if it is the same as the top box title
 *
 *    Resources ----------------------------------------------------
 *	[1] http://www.adventuresinsoftware.com/blog/?p=104#comment-1981, Michael L. Perry's Cover Flow
 *	[2] http://www.finnrudolph.de/, Finn Rudolph's imageflow, version 0.9 
 *	[3] http://reflection.corephp.co.uk/v2.php, Richard Daveys easyreflections in PHP
 *	[4] http://adomas.org/javascript-mouse-wheel, Adomas Paltanavicius JavaScript mouse wheel code
 *    --------------------------------------------------------------
 */

function imageflowplus(instance) {

/* Options */
this.defaults =
{
conf_autorotate:		'off',	// Sets auto-rotate option 'on' or 'off', default is 'off'
conf_autorotatepause: 	3000,		// Set the pause delay in the auto-rotation
conf_startimg:		1		// Starting focused image
};

/* Possible future options */
this.conf_reflection_p =	0.5;		// Sets the height of the reflection in % of the source image 
this.conf_focus =			4;		// Sets the numbers of images on each side of the focused one
this.conf_ifp_slider_width =	14;         // Sets the px width of the slider div
this.conf_ifp_images_cursor =	'pointer';  // Sets the cursor type for all images default is 'default'
this.conf_ifp_slider_cursor =	'default';  // Sets the slider cursor type: try "e-resize" default is 'default'

/* HTML div ids that we manipulate here */
this.ifp_imageflow2div =	'wpif2_imageflow_' + instance;
this.ifp_loadingdiv =		'wpif2_loading_' + instance;
this.ifp_imagesdiv =		'wpif2_images_' + instance;
this.ifp_captionsdiv =		'wpif2_captions_' + instance;
this.ifp_sliderdiv =		'wpif2_slider_' + instance;
this.ifp_scrollbardiv =		'wpif2_scrollbar_' + instance;
/* The overlay is shared among all instances */
this.ifp_overlaydiv =		'wpif2_overlay';
this.ifp_overlayclosediv =	'wpif2_overlayclose';
this.ifp_topboxdiv =		'wpif2_topbox';
this.ifp_topboximgdiv =		'wpif2_topboximg';
this.ifp_topboxcaptiondiv =	'wpif2_topboxcaption';
this.ifp_topboxprevdiv =	'wpif2_topboxprev';
this.ifp_topboxclosediv =	'wpif2_topboxclose';
this.ifp_topboxnextdiv =	'wpif2_topboxnext';

/* Define global variables */
this.image_id =		0;
this.new_image_id =	0;
this.current =		0;
this.target =		0;
this.mem_target =		0;
this.timer =		0;
this.array_images =	[];
this.new_slider_pos =	0;
this.dragging =		false;
this.dragobject =		null;
this.dragx =		0;
this.posx =			0;
this.new_posx =		0;
this.xstep =		150;
this.autorotate = 	'off';
this.rotatestarted = 	'false';

var thisObject = this;

/* initialize */
this.init = function(options)
{
	/* Evaluate options */
	var optionsArray = new Array('conf_autorotate', 'conf_autorotatepause', 'conf_startimg');
	var max = optionsArray.length;
	for (var i = 0; i < max; i++)
	{
		var name = optionsArray[i];
		this[name] = (options !== undefined && options[name] !== undefined) ? options[name] : thisObject.defaults[name];
	}
};

/* show/hide element functions */
this.show = function(id)
{
	var element = document.getElementById(id);
	element.style.visibility = 'visible';
};
this.hide = function(id)
{
	var element = document.getElementById(id);
	element.style.visibility = 'hidden';
	element.style.display = 'none';
};

this.step = function() {
	if (thisObject.target < thisObject.current-1 || thisObject.target > thisObject.current+1) 
	{
		thisObject.moveTo(thisObject.current + (thisObject.target-thisObject.current)/3);
		setTimeout(thisObject.step, 50);
		thisObject.timer = 1;
	} else {
		thisObject.timer = 0;
	}
};

this.glideTo = function(new_image_id) {
	if (this.max == 1) return;
	var x = (-new_image_id * this.xstep);
	/* Animate gliding to new image */
	this.target = x;
	this.mem_target = x;
	if (this.timer == 0)
	{
		window.setTimeout(thisObject.step, 50);
		this.timer = 1;
	}
	
	/* Display new caption */
	this.image_id = new_image_id;
	var caption = this.img_div.childNodes.item(this.array_images[this.image_id]).getAttribute('alt').replace(/\+\+.*/,'');
	if (caption == '') { caption = '&nbsp;'; }
	this.caption_div.innerHTML = caption;

	/* Set scrollbar slider to new position */
	if (this.dragging === false)
	{
		this.new_slider_pos = (this.scrollbar_width * (-(x*100/((this.max-1)*this.xstep))) / 100) - this.new_posx;
		this.slider_div.style.marginLeft = (this.new_slider_pos - this.conf_ifp_slider_width) + 'px';
	}
};

this.rotate = function()
{
	/* Do nothing if autorotate has been turned off */
	if (thisObject.autorotate == "on") {
		if (thisObject.image_id >= thisObject.max-1) {
			thisObject.glideTo(0);
		} else {
			thisObject.glideTo(thisObject.image_id+1);
		}
	}

	if (thisObject.conf_autorotate == 'on') {
		/* Set up next auto-glide */
		window.setTimeout (thisObject.rotate, thisObject.conf_autorotatepause);
	}
}

this.moveTo = function(x)
{
	this.current = x;
	var zIndex = this.max;
	
	/* Main loop */
	for (var index = 0; index < this.max; index++)
	{
		var image = this.img_div.childNodes.item(this.array_images[index]);
		var current_image = index * -this.xstep;

		/* Don't display images that are not focused */
		if ((current_image+this.max_conf_focus) < this.mem_target || (current_image-this.max_conf_focus) > this.mem_target)
		{
			image.style.visibility = 'hidden';
			image.style.display = 'none';
		}
		else 
		{
			var z = Math.sqrt(10000 + x * x) + 100;
			var xs = x / z * this.size + this.size;

			/* Still hide images until they are processed, but set display style to block */
			image.style.display = 'block';
		
			/* Process new image height and image width */
			var new_img_h = Math.round((image.h / image.w * image.pc) / z * this.size);
			var new_img_w;
			if ( new_img_h <= this.max_height )
			{
				new_img_w = Math.round(image.pc / z * this.size);
			} else {
				new_img_h = this.max_height;
				new_img_w = Math.round(image.w * new_img_h / image.h);
			}

			var new_img_top = Math.round((this.images_width * 0.34 - new_img_h) + ((new_img_h / (this.conf_reflection_p + 1)) * this.conf_reflection_p));

			/* Set new image properties */
			image.style.left = Math.round(xs - (image.pc / 2) / z * this.size) + 'px';
			if(new_img_w && new_img_h)
			{ 
				image.style.height = new_img_h + 'px'; 
				image.style.width = new_img_w + 'px'; 
				image.style.top = new_img_top + 'px';
			}
			image.style.visibility = 'visible';

			/* Set image layer through zIndex */
			if ( x < 0 )
			{
				zIndex++;
			} else {
				zIndex = zIndex - 1;
			}
			
			/* Change zIndex and onclick function of the focused image */
			switch ( image.i == thisObject.image_id )
			{
				case false:
					image.onclick = function() { thisObject.autorotate = "off"; thisObject.glideTo(this.i); return false; };
					break;

				default:
					zIndex = zIndex + 1;
  					if (image.getAttribute("rel") && (image.getAttribute("rel") == 'wpif2_lightbox')) {
						image.setAttribute("title",image.getAttribute('alt').replace(/\+\+.*/,''));
						image.onclick = function () { thisObject.conf_autorotate = "off"; thisObject.showTop(this); return false; };
					} else {
						image.onclick = function() { window.open (this.url); return false; };
					}
					break;
			}
			image.style.zIndex = zIndex;
		}
		x += this.xstep;
	}
};

/* Main function */
this.refresh = function(onload)
{
	/* Cache document objects in global variables */
	this.imageflow2_div = document.getElementById(this.ifp_imageflow2div);
	this.img_div = document.getElementById(this.ifp_imagesdiv);
	this.scrollbar_div = document.getElementById(this.ifp_scrollbardiv);
	this.slider_div = document.getElementById(this.ifp_sliderdiv);
	this.caption_div = document.getElementById(this.ifp_captionsdiv);

	/* Cache global variables, that only change on refresh */
	this.images_width = this.img_div.offsetWidth;
	this.images_top = this.imageflow2_div.offsetTop;
	this.images_left = this.imageflow2_div.offsetLeft;

	this.max_conf_focus = this.conf_focus * this.xstep;
	this.size = this.images_width * 0.5;
	this.scrollbar_width = Math.round(this.images_width * 0.6);
	this.conf_ifp_slider_width = this.conf_ifp_slider_width * 0.5;
	this.max_height = Math.round(this.images_width * 0.51);

	/* Change imageflow2 div properties */
	this.imageflow2_div.onmouseover = function () { thisObject.autorotate = 'off'; return false; };
	this.imageflow2_div.onmouseout = function () { thisObject.autorotate = thisObject.conf_autorotate; return false; };
	this.imageflow2_div.style.height = this.max_height + 'px';

	/* Change images div properties */
	this.img_div.style.height = this.images_width * 0.338 + 'px';

	/* Change captions div properties */
	this.caption_div.style.width = this.images_width + 'px';
	this.caption_div.style.marginTop = this.images_width * 0.03 + 'px';

	/* Change scrollbar div properties */
	this.scrollbar_div.style.marginTop = this.images_width * 0.02 + 'px';
	this.scrollbar_div.style.marginLeft = this.images_width * 0.2 + 'px';
	this.scrollbar_div.style.width = this.scrollbar_width + 'px';
	
	/* Set slider attributes */
	this.slider_div.onmousedown = function () { thisObject.dragstart(this); return false; };
	this.slider_div.style.cursor = this.conf_ifp_slider_cursor;

	/* Cache EVERYTHING! */
	this.max = this.img_div.childNodes.length;
	var i = 0;
	for (var index = 0; index < this.max; index++)
	{ 
		var image = this.img_div.childNodes.item(index);
		if ((image.nodeType == 1) && (image.nodeName != "NOSCRIPT"))
		{
			this.array_images[i] = index;
			
			/* Set image onclick to glide to this image */
			image.onclick = function() { thisObject.conf_autorotate = "off"; thisObject.glideTo(this.i); return false; };
			image.x_pos = (-i * this.xstep);
			image.i = i;
			
			/* Add width and height as attributes ONLY once onload */
			if (onload === true)
			{
				image.w = image.width;
				image.h = image.height;
			}

			/* Check source image format. Get image height minus reflection height! */
			if ((image.w + 1) > (image.h / (this.conf_reflection_p + 1))) 
			{
				/* Landscape format */
				image.pc = 118;
			} else {
				/* Portrait and square format */
				image.pc = 100;
			}

			/* Set ondblclick event */
			image.url = image.getAttribute('longdesc');
			if (image.getAttribute("rel") && (image.getAttribute("rel") == 'wpif2_lightbox')) {
				image.setAttribute("title",image.getAttribute('alt').replace(/\+\+.*/,''));

				image.ondblclick = function () { thisObject.conf_autorotate = 'off'; thisObject.showTop(this);return false; }
			} else {
				image.ondblclick = function() { window.open (this.url); }
			}
			/* Set image cursor type */
			image.style.cursor = this.conf_ifp_images_cursor;

			i++;
		}
	}
	this.max = this.array_images.length;

	/* Display images in current order */
	if ((this.conf_startimg > 0) && (this.conf_startimg <= this.max))	{
		this.image_id = this.conf_startimg - 1;
		this.mem_target = (-this.image_id * this.xstep);
		this.current = this.mem_target;
	}
	this.moveTo(this.current);
	this.glideTo(this.image_id);

	/* If autorotate on, set up next glide */
	this.autorotate = this.conf_autorotate;
	if ((this.autorotate == "on") && (this.rotatestarted == "false")) {
		window.setTimeout (thisObject.rotate, thisObject.conf_autorotatepause);
		this.rotatestarted = 'true';
	}
};

this.loaded = function()
{
	if(document.getElementById(thisObject.ifp_imageflow2div))
	{
		if (document.getElementById(thisObject.ifp_overlaydiv) === null) {
			/* Append overlay divs to the page - the overlay is shared by all instances */
			var objBody = document.getElementsByTagName("body").item(0);

			/* -- overlay div */
			var objOverlay = document.createElement('div');
			objOverlay.setAttribute('id',thisObject.ifp_overlaydiv);
			objOverlay.onclick = function() { thisObject.closeTop(); return false; };
			objBody.appendChild(objOverlay);
			jQuery("#"+thisObject.ifp_overlaydiv).fadeTo("fast", .7);
	
			/* -- top box div */
			var objLightbox = document.createElement('div');
			objLightbox.setAttribute('id',thisObject.ifp_topboxdiv);
			objBody.appendChild(objLightbox);

			/* ---- image div */
			var objLightboxImage = document.createElement("img");
			//objLightboxImage.onclick = function() { thisObject.closeTop(); return false; };
			objLightboxImage.setAttribute('id',thisObject.ifp_topboximgdiv);
			objLightbox.appendChild(objLightboxImage);

			/* ---- prev link */
			var objPrev = document.createElement("a");
			objPrev.setAttribute('id',thisObject.ifp_topboxprevdiv);
			objPrev.setAttribute('href','#');
			objLightbox.appendChild(objPrev);

			/* ---- next link */
			var objNext = document.createElement("a");
			objNext.setAttribute('id',thisObject.ifp_topboxnextdiv);
			objNext.setAttribute('href','#');
			objLightbox.appendChild(objNext);

			/* ---- caption div */
			var objCaption = document.createElement("div");
			objCaption.setAttribute('id',thisObject.ifp_topboxcaptiondiv);
			objLightbox.appendChild(objCaption);

			/* ---- close link */
			var objClose = document.createElement("a");
			objClose.setAttribute('id',thisObject.ifp_topboxclosediv);
			objClose.setAttribute('href','#');
			objLightbox.appendChild(objClose);

			objClose.onclick = function () { thisObject.closeTop(); return false; };
			objClose.innerHTML = "Close";
		}

		/* hide loading bar, show content and initialize mouse event listening after loading */
		thisObject.hide(thisObject.ifp_loadingdiv);
		thisObject.refresh(true);
		thisObject.show(thisObject.ifp_imagesdiv);
		thisObject.show(thisObject.ifp_scrollbardiv);
		thisObject.initMouseWheel();
		thisObject.initMouseDrag();
	}
};

this.unloaded = function()
{
	/* Fixes the back button issue */
	document = null;
};

/* Handle the wheel angle change (delta) of the mouse wheel */
this.handle = function(delta)
{
	var change = false;
	if (delta > 0)
	{
		if(this.image_id >= 1)
		{
			this.target = this.target + this.xstep;
			this.new_image_id = this.image_id - 1;
			change = true;
		}
	} else {
		if(this.image_id < (this.max-1))
		{
			this.target = this.target - this.xstep;
			this.new_image_id = this.image_id + 1;
			change = true;
		}
	}

	/* Glide to next (mouse wheel down) / previous (mouse wheel up) image */
	if (change === true)
	{
		this.glideTo(this.new_image_id);
		this.autorotate = "off";
	}
};

/* Event handler for mouse wheel event */
this.wheel = function(event)
{
	var delta = 0;
	if (!event) event = window.event;
	if (event.wheelDelta)
	{
		delta = event.wheelDelta / 120;
	}
	else if (event.detail)
	{
		delta = -event.detail / 3;
	}
	if (delta) thisObject.handle(delta);
	if (event.preventDefault) event.preventDefault();
	event.returnValue = false;
};

/* Initialize mouse wheel event listener */
this.initMouseWheel = function()
{
	if(window.addEventListener) {
		this.imageflow2_div.addEventListener('DOMMouseScroll', this.wheel, false);
	}
	this.imageflow2_div.onmousewheel = this.wheel;
};

/* This function is called to drag an object (= slider div) */
this.dragstart = function(element)
{
	thisObject.dragobject = element;
	thisObject.dragx = thisObject.posx - thisObject.dragobject.offsetLeft + thisObject.new_slider_pos;

	thisObject.autorotate = "off";
};

/* This function is called to stop dragging an object */
this.dragstop = function()
{
	thisObject.dragobject = null;
	thisObject.dragging = false;
};

/* This function is called on mouse movement and moves an object (= slider div) on user action */
this.drag = function(e)
{
	thisObject.posx = document.all ? window.event.clientX : e.pageX;
	if(thisObject.dragobject != null)
	{
		thisObject.dragging = true;
		thisObject.new_posx = (thisObject.posx - thisObject.dragx) + thisObject.conf_ifp_slider_width;

		/* Make sure, that the slider is moved in proper relation to previous movements by the glideTo function */
		if(thisObject.new_posx < ( - thisObject.new_slider_pos)) thisObject.new_posx = - thisObject.new_slider_pos;
		if(thisObject.new_posx > (thisObject.scrollbar_width - thisObject.new_slider_pos)) thisObject.new_posx = thisObject.scrollbar_width - thisObject.new_slider_pos;
		
		var slider_pos = (thisObject.new_posx + thisObject.new_slider_pos);
		var step_width = slider_pos / ((thisObject.scrollbar_width) / (thisObject.max-1));
		var image_number = Math.round(step_width);
		var new_target = (image_number) * -thisObject.xstep;
		var new_image_id = image_number;

		thisObject.dragobject.style.left = thisObject.new_posx + 'px';
		thisObject.glideTo(new_image_id);
	}
};

/* Initialize mouse event listener */
this.initMouseDrag = function()
{
	thisObject.imageflow2_div.onmousemove = thisObject.drag;
	thisObject.imageflow2_div.onmouseup = thisObject.dragstop;

	/* Avoid text and image selection while this.dragging  */
	document.onselectstart = function () 
	{
		if (thisObject.dragging === true)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
};

this.getKeyCode = function(event)
{
	event = event || window.event;
	return event.keyCode;
};


this.getPageScroll = function(){
	var xScroll, yScroll;
	if (self.pageYOffset) {
		yScroll = self.pageYOffset;
		xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop){	 // Explorer 6 Strict
		yScroll = document.documentElement.scrollTop;
		xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
		yScroll = document.body.scrollTop;
		xScroll = document.body.scrollLeft;	
	}
	arrayPageScroll = new Array(xScroll,yScroll) 
	return arrayPageScroll;
};

this.getPageSize = function(){
	var xScroll, yScroll;
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = window.innerWidth + window.scrollMaxX;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else if (document.documentElement.scrollHeight > document.body.offsetHeight){ // IE7, 6 standards compliant mode
		xScroll = document.documentElement.scrollWidth;
		yScroll = document.documentElement.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		if(document.documentElement.clientWidth){
			windowWidth = document.documentElement.clientWidth; 
		} else {
			windowWidth = self.innerWidth;
		}
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}
	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = xScroll;		
	} else {
		pageWidth = windowWidth;
	}
	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
};

this.showFlash = function(){
	var flashObjects = document.getElementsByTagName("object");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "visible";
	}
	var flashEmbeds = document.getElementsByTagName("embed");
	for (i = 0; i < flashEmbeds.length; i++) {
		flashEmbeds[i].style.visibility = "visible";
	}
};

this.hideFlash = function(){
	var flashObjects = document.getElementsByTagName("object");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "hidden";
	}
	var flashEmbeds = document.getElementsByTagName("embed");
	for (i = 0; i < flashEmbeds.length; i++) {
		flashEmbeds[i].style.visibility = "hidden";
	}
};

this.showTop = function(image)
{
	var topbox_div = document.getElementById(this.ifp_topboxdiv);
	var overlay_div = document.getElementById(this.ifp_overlaydiv);
	var topboximg_div = document.getElementById(this.ifp_topboximgdiv);

	// this.hide flash objects
	this.hideFlash();

	// this.show the background overlay ...
	var arrayPageSize = this.getPageSize();
	overlay_div.style.width = arrayPageSize[0] + "px";
	overlay_div.style.height = arrayPageSize[1] + "px";
	overlay_div.style.display = 'none';
	overlay_div.style.visibility = 'visible';
	jQuery("#"+this.ifp_overlaydiv).show();

	// Get the top box data set up first
	topboximg_div.src = image.url;
	document.getElementById(this.ifp_topboxcaptiondiv).innerHTML = image.getAttribute('title');

	// get the image actual size by preloading into 't'
	var t = new Image();
      t.onload = (function(){	thisObject.showImg(image, t.width, t.height); });
	t.src = image.url;

	// Now wait until 't' is loaded
};


this.showImg = function(image, img_width, img_height) 
{	
	// Do nothing if the overlay was closed in the meantime
	if (document.getElementById(this.ifp_overlaydiv).style.visibility == 'hidden') return;

	var topbox_div = document.getElementById(this.ifp_topboxdiv);
	var overlay_div = document.getElementById(this.ifp_overlaydiv);
	var topboximg_div = document.getElementById(this.ifp_topboximgdiv);
	var prev_div = document.getElementById(this.ifp_topboxprevdiv);
	var next_div = document.getElementById(this.ifp_topboxnextdiv);
	var caption_div = document.getElementById(this.ifp_topboxcaptiondiv);

	// The image should be preloaded at this point
	topboximg_div.src = image.url;

	// Find previous image that doesn't link to an url
	prev_div.style.visibility = 'hidden';
	if (image.i > 0) {
		for (index = image.i-1; index >= 0; index--) {
			prev_image = this.img_div.childNodes.item(this.array_images[index]);
			if (prev_image.getAttribute("rel") && (prev_image.getAttribute("rel") == 'wpif2_lightbox')) {
				// Found one - preload and set the previous link
				var p = new Image();
				p.src = prev_image.url;
				prev_div.onclick = (function(){ thisObject.showImg(prev_image, p.width, p.height); return false;});
				prev_div.style.visibility = 'visible';
				break;
			}
		} 
	}

	// Find next image that doesn't link to an url
	next_div.style.visibility = 'hidden';
	if (image.i < this.max-1) {
		for (index = image.i+1; index < this.max; index++) {
			next_image = this.img_div.childNodes.item(this.array_images[index]);
			if (next_image.getAttribute("rel") && (next_image.getAttribute("rel") == 'wpif2_lightbox')) {
				// Found one - preload and set the next link
				var n = new Image();
				n.src = next_image.url;
				next_div.onclick = (function(){ thisObject.showImg(next_image, n.width, n.height); return false;});
				next_div.style.visibility = 'visible';
				break;
			} 
		}
	}

	// Size the box to fit the image plus estimate caption height plus some space
	var boxWidth = img_width;
	var boxHeight = img_height + 30;

	topboximg_div.width = boxWidth;	

	// Add description and include its height in the calculations
	var description = image.getAttribute('alt').replace(/.*\+\+/,'');
	if (description == image.getAttribute('title')) description = '';
	if (description != '') { description = '<p>' + description + '</p>'; }
	caption_div.innerHTML = image.getAttribute('title') + description;
	if (description != '') {
		jQuery('#'+this.ifp_topboxcaptiondiv).width(boxWidth);	// do this now to estimate the description height
		boxHeight += jQuery('#'+this.ifp_topboxcaptiondiv).height();
	}

	// scale the box if the image is larger than the screen
	var arrayPageSize = this.getPageSize();
	var screenWidth = arrayPageSize[2];
	var screenHeight = arrayPageSize[3];

	var arrayPageScroll = this.getPageScroll();

	if (boxWidth > screenWidth) {
		boxHeight = Math.floor(boxHeight * (screenWidth-100) / boxWidth);
		boxWidth = screenWidth - 100;
		topboximg_div.width = boxWidth;
	}
	if (boxHeight > screenHeight) {
		boxWidth = Math.floor(boxWidth * (screenHeight-100) / boxHeight);
		boxHeight = screenHeight - 100;
		topboximg_div.width = boxWidth;
	}
	jQuery('#'+this.ifp_topboxcaptiondiv).width(boxWidth);

	var xPos = Math.floor((screenWidth - boxWidth) * 0.5) + arrayPageScroll[0];
	var yPos = Math.floor((screenHeight - boxHeight) * 0.5) + arrayPageScroll[1];

	topbox_div.style.left = xPos + 'px';
	topbox_div.style.top = yPos + 'px';
	topbox_div.style.width = boxWidth + 'px';

	prev_div.style.height = boxHeight + 'px';
	next_div.style.height = boxHeight + 'px';


	// Finally show the topbox...
	topbox_div.style.display = 'none';
	topbox_div.style.visibility = 'visible';
	jQuery("#"+this.ifp_topboxdiv).fadeIn("slow");

};

this.closeTop = function()
{
	//hide the overlay and topbox...
	document.getElementById(this.ifp_overlaydiv).style.visibility='hidden';
	document.getElementById(this.ifp_topboxdiv).style.visibility='hidden';
	document.getElementById(this.ifp_topboxnextdiv).style.visibility='hidden';
	document.getElementById(this.ifp_topboxprevdiv).style.visibility='hidden';

	// this.show hidden objects
	this.showFlash();
};

// Setup
	if(document.getElementById(thisObject.ifp_imageflow2div) === null) { return; }

	/* show loading bar while page is loading */
	thisObject.show(thisObject.ifp_loadingdiv);

	if(typeof window.onunload === "function")
	  {
		var oldonunload = window.onunload;
		window.onunload = function()
		{
			thisObject.unloaded();
			oldonunload();
		};
	} else { 
		window.onunload = this.unloaded; 
	}

	if(typeof window.onload === "function")
	  {
		var oldonload = window.onload;
		window.onload = function()
		{
			thisObject.loaded();
			oldonload();
		};
	} else {
		window.onload = thisObject.loaded;
	}

	/* refresh on window resize */
	window.onresize = function()
	{
		if(document.getElementById(thisObject.ifp_imageflow2div)) { thisObject.refresh(false); }
	};

	document.onkeydown = function(event)
	{
		var charCode  = thisObject.getKeyCode(event);
		switch (charCode)
		{
			/* Right arrow key */
			case 39:
				thisObject.handle(-1);
				break;
		
			/* Left arrow key */
			case 37:
				thisObject.handle(1);
				break;
		}
	};

}
