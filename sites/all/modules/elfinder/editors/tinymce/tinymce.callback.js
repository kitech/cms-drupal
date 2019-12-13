/*
 * elFinder Integration
 *
 * Copyright (c) 2010-2018, Alexey Sukhotin. All rights reserved.
 */

var elfinder_tinymce_callback = function(arg1) {
  var url = arg1;

  if (typeof arg1 == 'object') {
    url = arg1.url;
  }
  /* window.tinymceFileWin.document.forms[0].elements[window.tinymceFileField].value = url;
   window.tinymceFileWin.focus();
   window.close();*/

  //make inline popup work

  var win = tinyMCEPopup.getWindowArg("window");

  // insert information now
  win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;

  // are we an image browser
  if (typeof (win.ImageDialog) != "undefined") {
    // we are, so update image dimensions...
    if (win.ImageDialog.getImageData)
      win.ImageDialog.getImageData();

    // ... and preview if necessary
    if (win.ImageDialog.showPreviewImage)
      win.ImageDialog.showPreviewImage(url);
  }

  // close popup window
  tinyMCEPopup.close();

}
