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

function setIframeSize() {
	var ffHeight = document.getElementById("mybb_iframe").contentWindow.document.body.scrollHeight;
	var chromeHeight = document.getElementById("mybb_iframe").contentDocument.documentElement.scrollHeight;

	var height = chromeHeight;
	if (navigator.userAgent.indexOf('Firefox') != -1) {
		height = ffHeight;
	}

	document.getElementById("mybb_iframe").style.height = Math.max(ffHeight, chromeHeight) + 'px';
}
