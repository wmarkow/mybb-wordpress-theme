function iframeLoaded() {
	var iFrameID = document.getElementById('mybb_iframe');
	if(iFrameID) {
		iFrameID.height = "";
		var newHeight = parseInt(iFrameID.contentWindow.document.body.scrollHeight) + 179;
		iFrameID.style.height = newHeight + 'px';

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
