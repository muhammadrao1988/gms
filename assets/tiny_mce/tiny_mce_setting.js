/**
 * Adnan Bashir
 * Email: adnan.bashir@topgearmedia.co.uk
 */

tinyMCE.init({
    mode: "textareas",
    editor_selector: "editor",

    theme: "advanced",
    skin: "o2k7",
    skin_variant: "silver",
    plugins: "imagemanager,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

    // Theme options
    theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,|,insertimage",//insertfile,
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,

    // Example content CSS (should be your site CSS)
    content_css: assets_url + "css/tinymce/editor.css",

    /*relative_url: false,
    relative_urls: true,
    document_base_url: base_url,*/

    language: "en",

    // Drop lists for link/image/media/template dialogs
    template_external_list_url: "lists/template_list.js",
    external_link_list_url: "lists/link_list.js",



    // Replace values for the template plugin
    template_replace_values: {
        username: "Some User",
        staffid: "991234"
    }
});

/*-------------------------------------------------------------------------*/

tinyMCE.init({
    mode: "textareas",
    editor_selector: "small_editor",
    theme: "advanced",
    skin: "o2k7",
    skin_variant: "silver",
    plugins: "imagemanager",

    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,
    file_browser_callback: "imagemanager",
    theme_advanced_resize_horizontal: true,

    // Example content CSS (should be your site CSS)
    content_css: assets_url + "frontend/css/editor.css"
});

tinyMCE.init({
    mode: "textareas",
    editor_selector: "simple_editor",
    theme: "simple"
});