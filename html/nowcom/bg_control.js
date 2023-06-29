function bg_write_object()
{
	document.writeln("<OBJECT CLASSID='CLSID:8768D5EA-5412-4810-A032-09AD2A726C69' CODEBASE='http://bgweb.nowcdn.co.kr/Bin/DownStarter2.cab#version=2,0,1,6' ID='DownStarter' WIDTH=1 HEIGHT=1>");
	document.writeln("<PARAM NAME='Target' VALUE='NowCDN' />");
	document.writeln("</OBJECT>");
}

function bg_start_download(downurl)
{
	document.DownStarter.Do(3001, downurl.length, downurl);
}

function bg_add_download(downurl)
{
	bg_start_download(downurl);
}

function bg_autorun()
{
	var strCmd;
	strCmd = '!CmdRunAfterDownload_On';
	document.DownStarter.Do(3001, strCmd.length, strCmd);
}

function bg_terms(termurl)
{
	var strCmd;
	strCmd = '!CmdTerms ' + termurl;
	document.DownStarter.Do(3001, strCmd.length, strCmd);
}
