/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

// Compressed version of core/ckeditor_base.js. See original for instructions.
/* jshint ignore:start */
/* jscs:disable */
// replace_start
window.CKEDITOR||(window.CKEDITOR=function(){var o,d=/(^|.*[\\\/])ckeditor\.js(?:\?.*|;.*)?$/i,e={timestamp:"",version:"%VERSION%",revision:"%REV%",rnd:Math.floor(900*Math.random())+100,_:{pending:[],basePathSrcPattern:d},status:"unloaded",basePath:function(){var t=window.CKEDITOR_BASEPATH||"";if(!t)for(var e=document.getElementsByTagName("script"),n=0;n<e.length;n++){var a=e[n].src.match(d);if(a){t=a[1];break}}if(-1==t.indexOf(":/")&&"//"!=t.slice(0,2)&&(t=0===t.indexOf("/")?location.href.match(/^.*?:\/\/[^\/]*/)[0]+t:location.href.match(/^[^\?]*\/(?:)/)[0]+t),!t)throw'The CKEditor installation path could not be automatically detected. Please set the global variable "CKEDITOR_BASEPATH" before creating editor instances.';return t}(),getUrl:function(t){return-1==t.indexOf(":/")&&0!==t.indexOf("/")&&(t=this.basePath+t),this.timestamp&&"/"!=t.charAt(t.length-1)&&!/[&?]t=/.test(t)&&(t+=(0<=t.indexOf("?")?"&":"?")+"t="+this.timestamp),t},domReady:(o=[],function(t){if(o.push(t),"complete"===document.readyState&&setTimeout(i,1),1==o.length)if(document.addEventListener)document.addEventListener("DOMContentLoaded",i,!1),window.addEventListener("load",i,!1);else if(document.attachEvent){document.attachEvent("onreadystatechange",i),window.attachEvent("onload",i);var e=!1;try{e=!window.frameElement}catch(n){}document.documentElement.doScroll&&e&&!function a(){try{document.documentElement.doScroll("left")}catch(n){return void setTimeout(a,1)}i()}()}})};function i(){try{document.addEventListener?(document.removeEventListener("DOMContentLoaded",i,!1),window.removeEventListener("load",i,!1),n()):document.attachEvent&&"complete"===document.readyState&&(document.detachEvent("onreadystatechange",i),window.detachEvent("onload",i),n())}catch(t){}}function n(){for(var t;t=o.shift();)t()}var a,r=window.CKEDITOR_GETURL;return r&&(a=e.getUrl,e.getUrl=function(t){return r.call(e,t)||a.call(e,t)}),e}());
// replace_end
/* jscs:enable */
/* jshint ignore:end */

if ( CKEDITOR.loader )
	CKEDITOR.loader.load( 'ckeditor' );
else {
	// Set the script name to be loaded by the loader.
	CKEDITOR._autoLoad = 'ckeditor';

	// Include the loader script.
	if ( document.body && ( !document.readyState || document.readyState == 'complete' ) ) {
		var script = document.createElement( 'script' );
		script.type = 'text/javascript';
		script.src = CKEDITOR.getUrl( 'core/loader.js' );
		document.body.appendChild( script );
	} else {
		document.write( '<script type="text/javascript" src="' + CKEDITOR.getUrl( 'core/loader.js' ) + '"></script>' );
	}

}

/**
 * The skin to load for all created instances, it may be the name of the skin
 * folder inside the editor installation path, or the name and the path separated
 * by a comma.
 *
 * **Note:** This is a global configuration that applies to all instances.
 *
 *		CKEDITOR.skinName = 'moono';
 *
 *		CKEDITOR.skinName = 'myskin,/customstuff/myskin/';
 *
 * @cfg {String} [skinName='moono-lisa']
 * @member CKEDITOR
 */
CKEDITOR.skinName = 'moono-lisa';
CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'html5audio, dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,notification,button,toolbar,clipboard,panelbutton,panel,floatpanel,colorbutton,colordialog,xml,ajax,templates,menu,contextmenu,copyformatting,div,editorplaceholder,resize,elementspath,enterkey,entities,exportpdf,popup,filetools,filebrowser,find,floatingspace,listblock,richcombo,font,fakeobjects,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastetools,pastefromgdocs,pastefromlibreoffice,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,tableselection,undo,lineutils,widgetselection,widget,notificationaggregator,uploadwidget,uploadimage';
	config.skin = 'moono-lisa';
	config.removePlugins = 'easyimage, cloudservices, exportpdf';
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
