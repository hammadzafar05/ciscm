//CKEDITOR.dtd.$removeEmpty.i = 0; /*btbutton*/
//CKEDITOR.dtd.$removeEmpty.span = 0; /*btbutton*/

CKEDITOR.editorConfig = function( config ) {
	// config.language = 'fr';
	//config.uiColor = '#AADC6E';
	//config.skin = 'office2013';

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors','pbckcode' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
		//{ name: 'tables', groups: [ 'table','tablerow','tablecolumn', 'tablecell','tablecellmergesplit' ] }/*tabletoolstoolbar*/
	];

config.removeButtons = 'Form,Radio,Checkbox,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,Smiley,About,Preview,Print,CreateDiv,Language';

config.extraPlugins = 'wordcount,notification,eqneditor,btgrid,pastefromexcel,tableresize,imageresizerowandcolumn,uploadimage,uploadwidget,base64image,tableresizerowandcolumn,autocorrect,mathjax,chart,zoom,richcombo,crossreference,panel,listblock,list,liststyle,bt_table,scayt,menubutton,image2,autoembed,pbckcode,simplebutton';//texttransform,lineheight,colordialog,lineutils,imageresponsive,tabletoolstoolbar,texzilla,symbol,FMathEditor,googledocs,btbutton,tabletools,bootstrapVisibility,btbutton,embed,autoembed,image2,autogrow,
/*This plugin uses TeXZilla to convert user (La)TeX input to MathML. No MathJax neither bitmap images.*/
config.filebrowserImageUploadUrl = 'uploader/ckupload.php?type=Images';

config.autoEmbed_widget = 'customEmbed';
config.scayt_autoStartup = true;

//config.language_list = [ 'en:English', 'en-gb:British English' ];
	
/*config.contentsCss = 'http://fonts.googleapis.com/css?family=Lobster';
config.contentsCss = 'http://fonts.googleapis.com/css?family=Cardo:400,400italic,700';
config.font_names =  'Cardo; serif;'+config.font_names;
config.font_names =  'serif;sans serif;monospace;cursive;fantasy;Lobster;'+config.font_names;	
*/	
	
/*config.contentsCss = 'https://fonts.googleapis.com/css?family=Open Sans';
config.font_names =  'Open Sans';
config.font_defaultLabel = 'Open Sans';
config.fontSize_defaultLabel = '12px';*/

	config.mathJaxLib = '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-AMS_HTML';/*mathjax*/
	config.mathJaxClass = 'equation'/*mathjax*/

	/*config.allowedContent = true;//btbutton
    config.contentsCss = [
        'http://localhost/sites/powergroupbd.com/PGBDLTDcodecanyon/ecommerceAppShoe/code/adm/bootstrap/css/bootstrap.min.css',//btbutton
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'//btbutton
    ];*/

	config.plugins =
		'about,' +
		'a11yhelp,' +
		'basicstyles,' +
		'bidi,' +
		'blockquote,' +
		'clipboard,' +
		'colorbutton,' +

		'copyformatting,' +
		'contextmenu,' +
		'dialogadvtab,' +
		'div,' +
		'elementspath,' +
		'enterkey,' +
		'entities,' +
		'filebrowser,' +
		'find,' +
		'flash,' +
		'floatingspace,' +
		'font,' +
		'format,' +
		'forms,' +
		'horizontalrule,' +
		'htmlwriter,' +
		'image,' +
		'iframe,' +
		'indentlist,' +
		'indentblock,' +
		'justify,' +
		'language,' +
		'link,' +
		'magicline,' +
		'maximize,' +
		'newpage,' +
		'pagebreak,' +
		'pastefromword,' +
		'pastetext,' +
		'preview,' +
		'print,' +
		'removeformat,' +
		'resize,' +
		'save,' +
		'selectall,' +
		'showblocks,' +
		'showborders,' +
		'smiley,' +
		'sourcearea,' +
		'specialchar,' +
		'stylescombo,' +
		'tab,' +
		'table,' +
		'tableselection,' +
		'templates,' +
		'toolbar,' +
		'undo,' +
		'wysiwygarea';

};