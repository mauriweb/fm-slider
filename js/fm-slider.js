/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
;
(function($) {
    var con = function(d) {
        console.log(d);
    }
    var defaults = {
        autoControls: true,
        controls: true,
        stopOnHober: false,
        controlsDir: true,
        speed: 2000,
        pause: 20,
        checkVisibilityControlsDir: true,
        responsive: false,
        sliderWidth: '80',
        fullScreen: false,
        //slideMode: 'horizontal'
        //slideMode: 'vertical'
        slideMode: 'fade'
    }
    
    

    $.fn.fmSlider = function(options) {

        var $this = this;
        


        this.each(function() {

            var fm = {};
            var $ul = $(this);
            options = $.extend({}, defaults, options);
            con(options);
            if(options.slideMode!='fade'){
                //ADD FAKES
                var cloneLastToFake = $ul.children().last().clone();
                $ul.prepend(cloneLastToFake);
            }
            fm.lis = $ul.children();
            fm.totNumSlides = fm.lis.length;
            $ul.wrap('<div class="fm-slider-wrap" ><div id="fm-visor-slider"></div></div>');
            $ul.after('<div style="clear:both;"></div>')
            fm.sliderWrap = $ul.parent().parent();
            fm.visorSlider = $ul.parent();
            fm.pausado = false;
            reset();
            //STOPS ON HOVER
            if (options.stopOnHober) {
                $ul.mouseenter(function() {
                    fm.pausado = true;
                }).mouseleave(function() {
                    if (fm.pausado) {
                        fm.pausado = false;
                        start();
                    }
                });
            }
            //ADD CONTROLS
            if(options.slideMode!='fade'){
                appendAutoControls();
                appendControlsPager();
                appendControlsDir();
            }
            
            //start();

            function reset() {
                
                setSliderWidth();
                fm.sliderWrap.css({
                    width: fm.slideWidth,
                });

                setSliderHeight();
//                fm.sliderWrap.css({
//                    height: fm.slideHeight+25
//                });
                
                setSlidesPositions();
                fm.currentSlideIndex = 1;
                
                if(options.slideMode=='horizontal'){
                    fm.ulWidth = (fm.totNumSlides) * fm.slideWidth;
                    //fm.ulHeight = 'auto';
                    $ul.css({
                        width: fm.ulWidth*2,
                        height: fm.slideHeight,
                        marginLeft: fm.margins[fm.currentSlideIndex]
                    });
                    fm.lis.css({
                        width: fm.slideWidth,
                        float: 'left',
                        position: 'reltive',
                        liststyle: 'none'
                    });
                }else if(options.slideMode=='vertical'){
                    fm.ulWidth = fm.slideWidth;
                    fm.ulHeight = (fm.totNumSlides) * fm.slideHeight;//con(fm.ulHeight);
                    $ul.css({
                        width: fm.ulWidth,
                        height: (fm.totNumSlides) *fm.slideHeight,
                        marginTop: fm.margins[fm.currentSlideIndex]
                    });
                    fm.visorSlider.css({
                        width: fm.slideWidth,
                        height: fm.slideHeight,
                        position: 'relative',
                        liststyle: 'none'
                    });
                    fm.lis.css({
                        width: fm.slideWidth,
                        height: fm.slideHeight,
                        position: 'relative',
                        liststyle: 'none'
                    });
                    
                    
                    
                }else if(options.slideMode=='fade'){
                    fm.currentSlideIndex = 0;
                    fm.ulWidth = fm.slideWidth;
                    fm.ulHeight = fm.slideHeight;//con(fm.ulHeight);
                    $ul.css({
                        width: fm.ulWidth,
                        height: fm.ulHeight,
                      
                    });
                    fm.lis.css({
                        width: fm.slideWidth,
                        height: fm.slideHeight,
                        position: 'absolute',
                        liststyle: 'none'
                    });
  
                }
                
            }
            
            $(window).resize(function() {
                $ul.stop();
                fm.pausado = true;
                reset();
            });
            
//get percentage of with from options
            function setSliderWidth() {
                var sliderWidth = fm.sliderWrap.parent().width();
                fm.slideWidth = (options.sliderWidth * sliderWidth) / 100;
                //con(options.sliderWidth);
            }
            
            function setSliderHeight() {
                fm.slideHeight = Math.max.apply(Math, fm.lis.find('img').map(function() { //con('max height: '+$(this).height());//con($(this).height());
                    return $(this).height();
                }).get());
                
            }
            


            //start();

            function setSlidesPositions() {
                //CALCULATE MARGIN-POSITION FOR EACH SLIDE
                if(options.slideMode=='vertical' || options.slideMode=='horizontal'){
                    var margenIni = 0;
                    fm.margins = Array();
                    for (var i = 0; i < fm.totNumSlides; i++) {
                        fm.margins[i] = margenIni;
                        fm.minMargin = margenIni;
                        if(options.slideMode=='horizontal'){
                            margenIni -= fm.slideWidth;
                        }else if(options.slideMode=='vertical'){
                            margenIni -= fm.slideHeight;//con('slider height: '+fm.slideHeight);
                        }
                    }
                }else if(options.slideMode=='fade'){
                    fm.slidesStacked=Array();
                    $(fm.lis).each(function(i){
                        var index=1000-i;
                        $(this).css('z-index', index);
                        fm.slidesStacked[i]=$(this);
                        fm.lastIndex=index;
                    });
                }
            }


            //PLAY / STOP
            function appendAutoControls() {
                    if (options.autoControls) {
                        $autoControls = $('<div class="fm-controls-auto"><div class="fm-controls-auto-in"><a href="" class="fm-play active">Play</a></div><div class="fm-controls-auto-in"><a href="" class="fm-pause">Pause</a></div></div>');
                        fm.sliderWrap.append($autoControls);
                        $('.fm-play').click(function(e) {
                            e.preventDefault();
                            fm.pausado = false;
                            start();
                            return false;
                        });
                        $('.fm-pause').click(function(e) {
                            e.preventDefault();
                            fm.pausado = true;
                            return false;
                        });

                    }
                }
                //NEXT / PREV
            function appendControlsDir() {
                    if (options.controlsDir) {
                        $controls = $('<div class="fm-controls-direction"><a href="" class="fm-prev">Prev</a><a href="" class="fm-next">Next</a></div>');
                        fm.sliderWrap.append($controls);

                        $('.fm-controls-direction a').css('marginTop', '-15%');
                        //con($('.fm-controls-direction a'));
                        $('.fm-prev').click(function(e) {
                            e.preventDefault();
                            fm.pausado = true;
                            if (fm.currentSlideIndex <= 1) return;
                            if ($ul.is(':animated')) return;
                            fm.currentSlideIndex--;
                            goTo();
                            return false;
                        });
                        $('.fm-next').click(function(e) {
                            e.preventDefault();
                            fm.pausado = true;
                            if (fm.currentSlideIndex >= fm.totNumSlides) return;
                            if ($ul.is(':animated')) return;
                            fm.currentSlideIndex++;
                            goTo();
                            return false;
                        });
                        
                        

                    }
                }
                //PAGER
            function appendControlsPager() {
                if (options.controls) {
                    $control = $('<div class="fm-pager"></div>');
                    var inControls = '';
                    for (var i = 1; i < fm.totNumSlides; i++) {
                        $control.append('<div class="bx-pager-in"><a class="fm-pager-link" data-slide-index="' + i + '" href="#">' + i + '</a></div>');
                    }
                    fm.sliderWrap.append($control);
                    $('.fm-pager-link').click(function(e) {
                        e.preventDefault();
                        fm.pausado=true;
                        console.log($(this).data('slideIndex'));
                        fm.currentSlideIndex = parseInt($(this).data('slideIndex'));
                        goTo()
                        return false;
                    });

                }
            }
            
            function goTo(){
                        
                        if(options.slideMode=='horizontal'){
                            var newMargin = fm.margins[fm.currentSlideIndex];
                            fm.pausado = true;
                            $ul.stop().animate({
                                marginLeft: newMargin
                            }, options.speed, "easeInOutExpo", function() {
                                checkVisibilityControlsDir();
                                fm.pausado = false;
                                start();
                            });
                        }else if(options.slideMode=='vertical'){
                            var newMargin = fm.margins[fm.currentSlideIndex];
                            fm.pausado = true;
                            $ul.stop().animate({
                                marginTop: newMargin
                            }, options.speed, "easeInOutExpo", function() {
                                checkVisibilityControlsDir();
                                fm.pausado = false;
                                start();
                            });
                        }
            }



            function start() {
                if(options.slideMode=='horizontal'){
                    setTimeout(nextSlideHor, options.pause);
                }else if(options.slideMode=='vertical'){
                    setTimeout(nextSlideVer, options.pause);
                }else if(options.slideMode=='fade'){
                    setTimeout(nextSlideFade, options.pause);
                }
            }
            function nextSlideVer() {
                if (fm.currentSlideIndex >= fm.totNumSlides) {
                    fm.currentSlideIndex = 0;
                    $ul.css({
                        marginTop: fm.margins[fm.currentSlideIndex]
                    });
                }
                fm.currentSlideIndex++;
                $ul.animate({
                    marginTop: fm.margins[fm.currentSlideIndex]
                }, options.speed, "easeInOutExpo", function() {
                    checkVisibilityControlsDir();
                    if (!fm.pausado)
                        start();
                });
            }
            function nextSlideHor() {
                if (fm.currentSlideIndex >= fm.totNumSlides) {
                    fm.currentSlideIndex = 0;
                    $ul.css({
                        marginLeft: fm.margins[fm.currentSlideIndex]
                    });
                }
                fm.currentSlideIndex++;
                $ul.animate({
                    marginLeft: fm.margins[fm.currentSlideIndex]
                }, options.speed, "easeInOutExpo", function() {
                    checkVisibilityControlsDir();
                    if (!fm.pausado)
                        start();
                });
            }
            
            function nextSlideFade() {

                fm.slidesStacked[fm.currentSlideIndex].animate({
                    opacity: 0
                }, options.speed, "easeInOutExpo", function() {
                    checkVisibilityControlsDir();
                    normalizeStack()
                    fm.currentSlideIndex++;
                    if(fm.currentSlideIndex >= fm.slidesStacked.length)
                        fm.currentSlideIndex=0;
                    
                    if (!fm.pausado)
                        start();
                });
            }
            function normalizeStack(){
                for(var i=0; i<fm.slidesStacked.length; i++){con('fm.slidesStacked: '+fm.slidesStacked[i]);
                    if(fm.currentSlideIndex==i){
                        fm.slidesStacked[i].css({'zIndex': fm.lastIndex, 'opacity': 1} );
                    }else{
                        fm.slidesStacked[i].css('zIndex', parseInt(fm.slidesStacked[i].css('z-index'))+1 );
                    }
                    
                }
            }
            

      

            function checkVisibilityControlsDir() {
                if (options.checkVisibilityControlsDir) {
                    var fixCurrentSlideIndex = fm.currentSlideIndex;
//                    con('index to hide next: ' + (fixCurrentSlideIndex));
//                    con('index totNumSlides: ' + (fm.totNumSlides));
                    if (fixCurrentSlideIndex <= 1) {
                        $('.fm-prev').css('display', 'none');
                    } else {
                        $('.fm-prev').css('display', 'inherit');
                    }
                    if (fixCurrentSlideIndex >= fm.totNumSlides) {

                        $('.fm-next').css('display', 'none');
                    } else {
                        $('.fm-next').css('display', 'inherit');
                    }
                }
            }
            

        });
        


    }




})(jQuery);



