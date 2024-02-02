var els = document.getElementsByClassName('link');

$(window).on("scroll", function(){
    var scrollTop = $(window).scrollTop()
    if(scrollTop >= 100){
        document.getElementById("nav").style.backgroundColor = 'black';
        document.getElementById("bild").style.width = '60%';
        for (var i = 0; i < els.length; i++) {
            els[i].style.color = 'white';
        }
    }
    else{
        document.getElementById("nav").style.backgroundColor = 'transparent';
        document.getElementById("bild").style.width = '100%';
        for (var i = 0; i < els.length; i++) {
            els[i].style.color = 'white';
        }
    }
})