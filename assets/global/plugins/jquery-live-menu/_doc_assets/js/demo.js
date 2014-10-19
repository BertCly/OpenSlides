$(document).ready(function(){
   initSlidesAnchors(); 
});
function viewCode(link)
{
    if ($(link).next('.code').css('display') == 'block')
    {
        $(link).next('.code').slideUp(500);
        $(link).html('View source code');
    }
    else
    {
        $(link).next('.code').slideDown(500);
        $(link).html('Hide source code');
    }
}
function initSlidesAnchors()
{
    $('a[href*=#]').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
            && location.hostname == this.hostname) {
            var $target = $(this.hash);
            $target = $target.length && $target
            || $('[name=' + this.hash.slice(1) +']');
            if ($target.length) {
                var targetOffset = $target.offset().top;
                $('html,body')
                .animate({
                    scrollTop: targetOffset
                }, 500);
                return false;
            }
        }
    });
}