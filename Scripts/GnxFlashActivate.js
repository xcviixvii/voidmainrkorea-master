function GnxFlashActivate ( strFlashUrl , n4Width , n4Height , strWmode , strId , strClassName ) {
	document.writeln( GnxGetFlashActivateString( strFlashUrl , n4Width , n4Height , strWmode , strId , strClassName ) );

/*
	var flash = new ngbFlashActivate();
	flash.init(strFlashUrl,n4Width,n4Height,strId);
	if(strId) { flash.objId = strId; }
	if(strClassName) { flash.objClass = strClassName; }
	if (strWmode != 0) { flash.parameter('wmode',strWmode); }
	flash.loadFlash();
*/
}

function GnxGetFlashActivateString ( strFlashUrl , n4Width , n4Height , strWmode , strId , strClassName ) {
	var objSize_attribute = " width='100' height='100'";
	var objId_attribute = "id='flashObject'";
	var objId_IE_attribute = " id='flashObjectIE'";
	var className_attribute = "class='flashObjectClass'";
	var wmode_param = "<param name='wmode' value='window' />";
	var wmode_attribute = "wmode='window'";

	
	if (n4Width != 0) {
		objSize_attribute = " width='"+ n4Width +"' height='"+ n4Height +"'";
	} 
	if (strId != 0) {
		objId_attribute = " id='" + strId + "'";
		objId_IE_attribute = " id='" + strId + "'";
	} 
	if (strClassName != 0) {
		className_attribute = " class='" + strClassName + "'";
	} 
	if (strWmode != 0) {
		wmode_param = "<param name='wmode' value='" + strWmode + "' />";
		wmode_attribute = " wmode='" + strWmode + "'";
	} 
	
	var textToWrite = "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' " + objSize_attribute + objId_IE_attribute + className_attribute + ">\n";
	textToWrite += "<param name='movie' value='"+ strFlashUrl +"' />\n";
	textToWrite += "<param name='quality' value='high' />\n";
	textToWrite += "<param name=bgcolor value='#FFFFFF'>\n";
	textToWrite += "<param name='allowScriptAccess' value='always' />\n";
	textToWrite += wmode_param+"\n";
	textToWrite += "<!-- Hixie method -->\n";
	textToWrite += "<!--[if !IE]> <-->\n";
	textToWrite += "<object type='application/x-shockwave-flash' allowScriptAccess='always' bgcolor='#FFFFFF' data='"+ strFlashUrl +"'" + objSize_attribute + objId_attribute + wmode_attribute + className_attribute + "></object>\n";
	textToWrite += "<!--> <![endif]-->\n";
	textToWrite += "</object>\n";

	return 	textToWrite;
}

function GnxSetFlashActiveteToControl( strFlashUrl , n4Width , n4Height , strWmode , strId , strClassName, targetObj ) 
{
	targetObj.insertAdjacentHTML( "beforeEnd", GnxGetFlashActivateString( strFlashUrl , n4Width , n4Height , strWmode , strId , strClassName ) );
}

function GnxSetObjectToControl( stringWhere, objectString, targetObj )
{
	targetObj.insertAdjacentHTML( stringWhere, objectString );
}

function GnxFlashActivateWithLayer( strFlashUrl , n4Width , n4Height , strWmode , strObjectId , strClassName, strDivID, n4XOffset, n4YOffset ) {
	document.writeln( "<div id='" + strDivID + "' style='position:absolute;top:" + n4XOffset + "px;left:" + n4YOffset + "px;'>" );
	GnxFlashActivate( strFlashUrl , n4Width , n4Height , strWmode , strObjectId , strClassName );
	document.writeln( "</div>" );
}

function GnxFlashActivateWithIframe( strFlashUrl , n4Width , n4Height , strIframeId , strClassName ) {
	document.writeln("<iframe id='" + strIframeId + "' src='" + strFlashUrl + "' width='" + n4Width + "' height='" + n4Height + "' class='" + strClassName + "' border='0' frameborder='0'></iframe>");
}

function ngbFlashActivate()
{
	var fullParam = new String;
	var fullParamForIE = new String;

	this.clsid = "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"; //MS Object ClassID
	this.codebase = "https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"; //Flash Version
	this.pluginspage = "http://www.macromedia.com/go/getflashplayer"; //Flash Plugin Link
	
	this.flashURL = new String;
	this.width = new String;
	this.height = new String;
	this.objId = new String;
	this.objClass = new String;
	
	this.wmode = new String;
	this.quality = new String;
	this.bgcolor = new String;
	this.allowScriptAccess = new String;
	this.allowFullScreen = new String;
	this.accessibility = new String;
	this.parameter = function( property, value )
	{
		fullParamForIE += "<param name='"+ property +"' value='"+ value + "' />\n"; 
		fullParam += " " + property +"='" + value +"'";
		switch ( property )
		{
			case "wmode": this.wmode = value; break;
			case "quality": this.quality = value; break;
			case "bgcolor": this.bgcolor = value; break;
			case "allowScriptAccess": this.allowScriptAccess = value; break;
			case "allowFullScreen": this.allowScriptAccess = value; break;
			default:;
		}
	}

	this.init = function( strFlashUrl, n4Width, n4Height)
	{
		this.flashURL = strFlashUrl;
		this.width = n4Width;
		this.height = n4Height;
	}
	this.loadFlash = function( showId )
	{
		if(this.wmode == ""){ this.parameter("wmode", "window")};
		if(this.quality == "") { this.parameter("quality", "high")};
		if(this.bgcolor == "") { this.parameter("bgcolor", "#FFFFFF")};
		if(this.allowScriptAccess == "") { this.parameter("allowScriptAccess", "always")};
		if(this.allowFullScreen == "") { this.parameter("allowFullScreen", "false")};

		var resultHTML = new String;
		var genIDAttr = (this.objId!="") ? " id='" + this.objId + "'" : "";
		var genClassAttr = (this.objClass!="") ? " class='" + this.objClass + "'" : "";
		var widthSizeAttr = (this.width !=0) ? " width='"+ this.width +"'" : "";
		var heightSizeAttr = (this.height !=0) ? " height='"+ this.height +"'" : "";

		resultHTML = "<!--[if IE]>";
		resultHTML += "<object " + genIDAttr + genClassAttr + " classid='"+ this.clsid +"' codebase='"+ this.codebase +"' " + widthSizeAttr + heightSizeAttr + ">\n";
		resultHTML += "<param name='movie' value='"+ this.flashURL +"' />";
		resultHTML += fullParamForIE ;
		resultHTML += "<![endif] -->";
		resultHTML += "<!--[if !IE]> <-->";
		resultHTML += "<object  " + genIDAttr + genClassAttr + "  type='application/x-shockwave-flash' data='" + this.flashURL + "' " + widthSizeAttr + heightSizeAttr + fullParam + ">\n";
		resultHTML += "<!--><![endif]-->";
		resultHTML += this.accessibility;
		resultHTML += "</object>";

		(showId) ? document.getElementById(showid).innerHTML = resultHTML : document.writeln ( resultHTML );
	}
}