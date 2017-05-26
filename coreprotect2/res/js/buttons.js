(function(){
// CoLWI v0.9.3
// buttons JavaScript
// Copyright (c) 2015-2016 SimonOrJ

// Function that adds "active" to all checkboxes and radio buttons that are checked.
"use strict";

$('.dtButtons label').each(function(i, el) {
    var e = $(el);
    if (e.children()[0].hasAttribute("checked")) {
        e.addClass("active");
    }
    // Ohhhhh, so this is how it works! :D
});

}());