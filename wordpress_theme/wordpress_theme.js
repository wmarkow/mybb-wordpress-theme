function iframeLoaded() {
	var iFrameID = document.getElementById('mybb_iframe');
	if(iFrameID) {
		iFrameID.height = "";
		iFrameID.style.height = iFrameID.contentWindow.document.body.scrollHeight + 'px';		
	}
}
