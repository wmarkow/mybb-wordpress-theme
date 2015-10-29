function iframeLoaded() {
	var iFrameID = document.getElementById('mybb_iframe');
	if(iFrameID) {
		iFrameID.height = "";
		iFrameID.style.height = iFrameID.contentWindow.document.body.scrollHeight + 'px';

		var hash = window.location.hash;
                if(hash) {
                        var anchorId = hash.replace('#', '');
                        var anch = iFrameID.contentWindow.document.getElementById(anchorId);
			if(anch) {        
				this.scrollTo(0, anch.offsetTop);
			}
                }		
	}
}

function intervalChecks() {
	setIframeSize();
	setModalPosition();
}

function setIframeSize() {
	var mybbIframe = document.getElementById("mybb_iframe");

	// set the iframe height on Firefox and others
	var ffHeight = mybbIframe.contentWindow.document.body.scrollHeight;
	var chromeHeight = mybbIframe.contentDocument.documentElement.scrollHeight;

	var height = chromeHeight;
	if (navigator.userAgent.indexOf('Firefox') != -1) {
		height = ffHeight;
	}

	document.getElementById("mybb_iframe").style.height = Math.max(ffHeight, chromeHeight) + 'px';
}


function setModalPosition() {
	var mybbIframe = document.getElementById("mybb_iframe");
	
	// set the modal window position on Firefox
	var currentModals = mybbIframe.contentWindow.document.getElementsByClassName("modal current");
	if(currentModals.length == 1) {
		var screenAvailHeight = window.screen.availHeight;
		var pageYOffset = window.pageYOffset;
		var mybbIframeOffsetTop = mybbIframe.offsetParent.offsetTop;
		var mybbIframeHeight = mybbIframe.contentWindow.document.body.scrollHeight;
		var mozInnerScreenY = window.mozInnerScreenY;
		var screenY = window.screenY;

		var currentFixedTop = pageYOffset;
		if(pageYOffset > mybbIframeOffsetTop) {
			currentFixedTop = pageYOffset -  mybbIframeOffsetTop;
		}

		var currentFixedBottom = currentFixedTop + screenAvailHeight - mozInnerScreenY - mybbIframeOffsetTop + screenY;
		if(pageYOffset > mybbIframeOffsetTop){
			currentFixedBottom = currentFixedBottom + mybbIframeOffsetTop
		}
		if(currentFixedBottom > mybbIframeHeight) {
			currentFixedBottom = mybbIframeHeight;
		}
		var currentFixedCenter = (currentFixedTop + currentFixedBottom) / 2;


		currentModals[0].style.setProperty ("top", currentFixedCenter + 'px', "important");
	}
}
