(function($) {    
    $.tour = function(method) {
        var 
            defaults = {
                startTourButton: '.start-tour',
                baseUrl: '/',
                folder: '',
                adminSection: '/admin',
                tipInfoCorrectionY:20,
            },
            args = arguments,
            timeoutHandler;
            
        var data = $('body').data('tour');
        if (!data) { //Инициализация
            data = {
                options: defaults
            }; 
            $('body').data('tour', data);
        }            
        
        //public
        var 
            methods = {
                init: function(tours, localOptions) {
                    data.tours = tours;
                    data.options = $.extend({}, data.options, localOptions);
                    
                    $(data.options.startTourButton).click(function() {
                        methods.start($(this).data('tourId'), 'index', true);
                    });
                    //Если тур был запущен раннее, то пытаем определить действие
                    var tourId = $.cookie('tourId');
                    if (tourId) methods.start(tourId);
                    
                },
                start: function(tour_id, startStep, force) {
                    if (!data.tours[tour_id]) {
                        console.log('Tour '+tour_id+' not found');
                        return;
                    };
                    $.cookie('tourId', tour_id, {path:'/'});
                    data.tour = data.tours[tour_id];
                    data.tourTotalSteps = getTotalSteps();
                    data.tourStepIndex = [];
                    $.each(data.tour, function(i, val) {
                        data.tourStepIndex.push(i);
                    });                    
                    
                    var 
                        step = findStep(data.tour, startStep, force);
                    
                    //Проверка: если step = false, то значит стартовая страница не соответствует туру.
                    if (step) {
                        runStep(step);
                    } else {
                        if (step !== null) {
                            methods.stop();
                        }
                    }
                    
                    $('body').bind('keypress.tour', function(e){
                        if (e.keyCode == 27) methods.stop();
                    });
                },
                stop: function() {
                    $.cookie('tourId', null, {path:'/'});
                    $.cookie('tourStep', null, {path:'/'});
                    hideStep();
                },
            }
        
        //private
        var 
            /**
            * Выполняет поиск текущего шага в туре по принципу:
            * текущий URL должен совпадать с URL, заявленным в шаге
            * 
            * @param tour
            */
            findStep = function(tour, step, force) {
                if (!step) step = $.cookie('tourStep');
                if (!step && !$('#debug-top-block').is('.debug-mobile')) {
                    step = 'index';
                }
                if (!data.tour[step]) return false;

                //Проверяем соответствует ли шаг тура текущей странице
                var a = $('<a />').attr('href', location.href).get(0);
                var relpath = ('/'+a.pathname.replace(/^([/])/gi, '')) + a.search;
                var relpath_mask = relpath.replace(data.options.adminSection, '%admin%').replace(/([/])$/gi, '');
                
                var steppath;
                if (step) {
                    steppath = data.options.folder + data.tour[step].url.replace(/([/])$/gi, '');
                }
                
                if (relpath_mask != steppath && !force) {
                    foundStep = false;
                    //Пытаемся найти шаг, по URL.
                    var before, found;
                    for(var key in data.tour) {
                        if (data.options.folder + data.tour[key].url.replace(/([/])$/gi, '') == relpath_mask) {
                            if (!before || before == step) { //Этот шаг идет вслед за предыдущим отображенным
                                //Мы нашли шаг по URL, возвращаем его
                                foundStep = key; 
                                break;
                            }
                        }
                        before = key;
                    }
                    
                    //Если не нашли, то выводим сообщение о прерывании тура
                    if (!foundStep) {
                        showDialog({
                            type: 'dialog',
                            message: lang.t('Вы перешли на страницу, не предусмотренную интерактивным курсом. <br>Вернуться и продолжить обучение?'),
                            buttons: {
                                yes: step,
                                no: false
                            }
                        });
                        return null;
                    }
                    step = foundStep;                    
                }

                return step;
            },
            
            getStepIndex = function(step) {
                var i = 1;
                for(var key in data.tour) {
                    if (key == step) return i;
                    i++;
                }
                return false;
            },
            
            getTotalSteps = function() {
                var i = 0;
                for(var key in data.tour) i++;
                return i;
            },
            
            runStep = function(step, noRedirect) {                
                var tourStep = data.tour[step];
                hideStep();                
                
                data.curStep = step;
                data.curStepIndex = getStepIndex(step);
                $.cookie('tourStep', step, {path:'/'});

                //Проверим, соответствует ли текущая страница шагу step
                var a = $('<a />').attr('href', location.href).get(0);
                var relpath = ('/'+a.pathname.replace(/^([/])/gi, '')) + a.search;
                var relpath_mask = relpath.replace(data.options.adminSection, '%admin%').replace(/([/])$/gi, '');
                if (relpath_mask != data.options.folder + tourStep.url.replace(/([/])$/gi, '') && !noRedirect) {
                    //Необходим переход на другую страницу
                    loading.show();
                    location.href = data.options.folder + tourStep.url.replace('%admin%', data.options.adminSection);
                    return;
                }
            
                //Выполняет один шаг обучения
                var type = (tourStep.type) ? tourStep.type : 'tip';
                if (tourStep.onStart) tourStep.onStart();
                switch (type) {
                    case 'dialog': showDialog(tourStep); break;
                    case 'tip': showTip(step); break;
                    case 'info': showInfo(step); break;
                    case 'form': showForm(step); break;
                }
                
                //Выполняем watch
                if (tourStep.watch) {
                    $('body').on(tourStep.watch.event + '.tour', tourStep.watch.element, function() { 
                        runAction(tourStep.watch.next, true);
                    });
                }
                
                $('a[data-step]').click(function() {
                    runAction( $(this).data('step') );
                });
            },
            
            overlayShow = function(blur) {
                if (blur) {
                    $('body > *').addClass('filterBlur');
                }
                $('<div id="tourOverlay"></div>').appendTo('body');
                
            },
            
            overlayHide = function() {
                $('#tourOverlay').remove();
                $('body > *').removeClass('filterBlur');
            },
            
            showDialog = function(tourStep) {
                overlayShow(true);
                var dialog = $('<div id="tipDialog" />').addClass('tipDialog').append('<a class="tipDialogClose" />')
                var content = $('<div class="tipContent" />').html(tourStep.message);
                var buttons = $('<div class="tipButtons" />');
                
                $.each(tourStep.buttons, function(key, val) {
                    var button = $('<a class="tipButton"/>');
                    var buttonText = (typeof(val) == 'object' && val.text) ? val.text : false;
                    
                    switch(key) {
                        case 'no': {
                            button.text(buttonText ? buttonText : lang.t('Нет')).addClass('tipNo');
                            break;
                        }
                        case 'yes': {
                            button.text(buttonText ? buttonText : lang.t('Да')).addClass('tipYes');
                            break;
                        }
                        case 'finish': {
                            button.text(buttonText ? buttonText : lang.t('Завершить')).addClass('tipYes');
                            break;
                        }
                        default: {
                            button.text(buttonText).attr(val.attr);
                        }
                    }
                    button.click(hideDialog);
                    
                    //Переход на следующий шаг
                    if (typeof(val) == 'string' || typeof(val) == 'boolean' || typeof(val) == 'object') {
                        button.click(function() {
                            var next = (typeof(val) == 'object') ? val.step : val;
                            runAction(next);
                        });
                    }
                    
                    $('.tipDialogClose', dialog).click(methods.stop);
                    $('#tourOverlay').click(methods.stop);
                    
                    button.appendTo(buttons);
                });
                
                dialog
                    .append(content)
                    .append(buttons)
                    .appendTo('body')
                    .addClass('flipInX animated');
                
                dialog.css({
                        marginLeft: -parseInt(dialog.width()/2),
                        marginTop:-parseInt(dialog.height()/2),
                    });
                
            },
            
            showTip = function(step)
            {
                var 
                    tourStep = data.tour[step];
                
                tourStep.tip = $.extend(true, {
                    correctionX: 0,
                    correctionY: 0,
                    animation: 'fadeInDown',
                    css: {
                        'minWidth': 280,
                        'maxWidth': 400
                    }
                }, tourStep.tip);
                
                var element = $(tourStep.tip.element).first();
                
                if (!element.length) {
                    if (tourStep.tip.notFound) {
                        runAction(tourStep.tip.notFound);
                    }
                    return;
                }
            
                var tip = $('<div class="tipTour" />')
                    tip.html('<div class="tipContent">'+tourStep.tip.tipText+'</div>')
                       .append(getStatusLine())
                       .append('<i class="corner"/>')
                       .css(tourStep.tip.css)
                       .appendTo('body')
                       .data('originalWidth', tip.width())
                       .width(tip.width())
                       .draggable();
                
                getTipPosition(element, tourStep.tip, tip);
                
                scrollWindow(tip);
                
                $(window).bind('resize.tour', function() {
                    getTipPosition(element, tourStep.tip, tip);
                });
                
                if (tourStep.tip.animation) {
                    tip.addClass(tourStep.tip.animation + ' animated');
                }
                
                if (tourStep.whileTrue) {
                    var whileTrue = function() {
                        if (!tourStep.whileTrue()) {
                            goNext();
                        } else {
                            timeoutHandler = setTimeout(whileTrue, 2000);
                        }
                    }();
                    
                    timeoutHandler = setTimeout(whileTrue, 2000);
                }
                
                if (tourStep.checkTimeout) {
                    timeoutHandler = setTimeout(goNext, tourStep.checkTimeout);
                }
            },
            
            getTipPosition = function(element, tipData, tip)
            {
                
                var position = {
                    top: element.offset().top + element.height(),
                    left: element.offset().left + element.width()/2,
                },
                bodyWidth = $('body').width();
                
                if (tipData.bottom) {
                    //Выноска находится внизу экрана
                    position.top = element.offset().top - getHeight(tip);
                    tip.addClass('bottom');
                }
                
                var tipWidth = getWidth(tip);
                
                if (tipWidth > bodyWidth-20) {
                    tip.width( bodyWidth-40 );
                    tipWidth = bodyWidth-20;
                }
                
                if (position.left + tipWidth > bodyWidth) {
                    position.marginLeft = -(position.left + tipWidth - bodyWidth + 10);
                } else {
                    position.marginLeft = 0;
                }
                position.left = position.left + tipData.correctionX;
                position.top = position.top + tipData.correctionY;
                tip.css(position);
                
                //Устанавливаем смещение выноски
                tip.find('.corner').css('marginLeft', -position.marginLeft);
                
                
            },
            
            runAction = function(action, noRedirect) {
                    
                switch(typeof(action)) {
                    
                    case 'boolean': if (!action) {
                        methods.stop();
                    }; break;
                    case 'string': runStep(action, noRedirect); break;
                    case 'function': {
                        var result = action();
                        if (result) runStep(result, noRedirect);
                        if (result === false) return false;
                    }
                    default: return false;
                }
                return true;
            },
            
            closeFormDialog = function() {
                if (data.curStep && data.tour[data.curStep].type == 'form') {
                    //Пытаемся закрыть окно, если текущий шаг связан с формой
                    $('body').off('dialogBeforeDestroy.tour');
                    $('.dialog-window').dialog('close');
                }
            },
            
            goNext = function() {
                if (data.curStepIndex < data.tourTotalSteps) {
                    closeFormDialog();
                    runStep(data.tourStepIndex[data.curStepIndex]);
                }
            },
            
            goPrev = function() {
                if (data.curStepIndex > 1) {
                    closeFormDialog();
                    runStep(data.tourStepIndex[data.curStepIndex-2]);
                }
            },
            
            scrollWindow = function(oneTip) {
                //Если tip не помещается на экран, то перемещаем scroll 
                if (oneTip.offset().top - 90 < $(window).scrollTop() 
                    || oneTip.offset().top -90 > $(window).scrollTop() + $(window).height()
                    ) {
                        $('html, body').animate({
                            scrollTop: oneTip.offset().top-90
                        });
                    }
            },
            
            showForm = function(step)
            {
                var tourStep = data.tour[step],
                    checkTimeout,
                    tipMap = {};
                
                data.curSubStep = 0;
                data.totalSubSteps = 0;
                
                //Создаем массив tip.label => index, для быстрого нахождения index по label.
                $.each(tourStep.tips, function(i, tip) {
                    if (tip.label) {
                        tipMap[tip.label] = i;
                    }
                    data.totalSubSteps++;
                });
                    
                //Запускает подшаги по событию
                $('body').on('new-content.tour', function() {                   
                    if (tourStep.tips[data.curSubStep].waitNewContent || data.curSubStep == 0) {
                        setTimeout(function() {
                            showSubTip(true);
                        }, 50);
                    }
                });                
                
                //Возвращаемся на предыдущий шаг, если закрывается окно диалога
                $('body').on('dialogBeforeDestroy.tour', function() {
                    goPrev();
                });
                
                var showSubTip = function(skipCheckWait) {
                    
                    $('.tipForm').each(function() {
                        if (tourStep.tips[ $(this).data('substep') ].onStop) {
                            tourStep.tips[ $(this).data('substep') ].onStop();
                        }
                        $(this).remove();
                        clearTimeout(checkTimeout);
                    });
                    
                    tip = tourStep.tips[data.curSubStep];

                    if (!tip) return;
                    
                    //Устанавливаем значения по умолчанию
                    tip = $.extend({
                        tipText: '',
                        css: {},
                        animation: null,
                        correctionX: 0,
                        correctionY: 0,
                        onStart: null,
                        onStop: null
                    }, tip);                    
                    
                    var element = $(tip.element).first();

                    if ( (!skipCheckWait && tip.waitNewContent) ) return;
                    
                    //Проверяем условие для отображения
                    if (typeof(tip.ifTrue) == 'function' ) {
                        if (!tip.ifTrue()) {
                            //Если отображать tip не следует, то перекидываем на другой tip
                            data.curSubStep = (tip.elseStep !== undefined) ? tipMap[tip.elseStep] : data.curSubStep + 1;
                            showSubTip();
                            return;
                        }
                    }
                    
                    var goToNextSubStep = function() {
                        data.curSubStep = (tip.next) ? tipMap[tip.next] : data.curSubStep + 1;
                        showSubTip();
                    }                   
                    
                    if ( !element.length  ) {
                        //Пытаемся перейти на следующий элемент
                        if (data.curSubStep>0) goToNextSubStep();
                        return;
                    }
                    
                    var oneTip = $('<div class="tipTour tipForm" />')
                        oneTip.html('<div class="tipContent">'+tip.tipText+'</div>')
                           .data('substep', data.curSubStep)
                           .append('<i class="corner"></i>')
                           .append(getStatusLine())
                           .css(tip.css);
                      
                    if (tip.correctionX) {
                        oneTip
                            .css('marginLeft', tip.correctionX);
                        
                        if (tip.correctionX<0) {
                            oneTip.find('.corner').css({
                                left: -tip.correctionX
                            });
                        }
                    }
                    
                    if (tip.correctionY) {
                        oneTip.css('marginTop', tip.correctionY);
                    }
                           
                    if (tip.bottom) {
                        oneTip
                            .addClass('bottom')
                            .appendTo('body');
                        
                        updateTipFormPosition(element, tip, oneTip);
                        $(window).on('resize.tour', function() {
                            updateTipFormPosition(element, tip, oneTip);
                        });
                        
                    } else {
                        oneTip
                            .appendTo(element.parent());
                    }
                    
                    if (tip.onStart) tip.onStart(); 
                    
                    scrollWindow(oneTip);

                    if (tip.checkPattern) {                        
                        if ( (element.is('input') && element.attr('type') == 'text')
                            || element.is('textarea')) {
                            
                                var checkText = function() {
                                    if (tip.checkPattern.test( $(element).val() )) {
                                        goToNextSubStep();
                                    } else {
                                        checkTimeout = setTimeout(checkText, 1500);
                                    }
                                }
                                checkTimeout = setTimeout(checkText, 1500);
                        }
                        if (element.is('input') && element.attr('type') == 'checkbox') {
                            element.off('.tour').on('change.tour', function(e) {
                                if ($(this).is(':checked') ==  tip.checkPattern) {
                                    element.off('.tour');
                                    goToNextSubStep();
                                }
                            });
                        }
                        
                        if (element.is('select')) {
                               element.off('.tour').on('change.tour', function(e) {
                                if (tip.checkPattern.test( $(this).val() )) {
                                    element.off('.tour');
                                    goToNextSubStep();
                                }
                            });
                        }
                    }
                    
                    if (tip.checkSelectValue) {
                        console.log('2233');
                        element.on('change.tour', function(e) {
                            if (tip.checkSelectValue.test( $('option:selected', e.currentTarget).html() )) {
                                element.off('.tour');
                                goToNextSubStep();
                            }                            
                            
                        });
                    }
                    
                    if (tip.watch) {
                        var watchElement = tip.watch.element ? $(tip.watch.element) : element;
                        
                        watchElement.one(tip.watch.event+'.tour', function() {
                            if (tip.watch.next) {
                                runAction(tip.watch.next);
                            } else {
                                goToNextSubStep();
                            }
                        });
                    }
                    
                    if (tip.tinymceTextarea) {
                        var textarea = $(tip.tinymceTextarea);
                        
                        var checkText = function() {
                            if (tip.checkPattern.test( textarea.html() )) {
                                goToNextSubStep();
                            } else {
                                setTimeout(checkText, 1000);
                            }
                        };
                        setTimeout(checkText, 1000);
                    }
                    
                    if (tip.checkTimeout) {
                        checkTimeout = setTimeout(function() {
                            goToNextSubStep();
                        }, tip.checkTimeout);
                    }
                }
                
                showSubTip();
            },            
            
            updateTipFormPosition = function(element, tipData, oneTip)
            {
                var position = {
                    top: element.offset().top + getHeight(element),
                    left: element.offset().left
                }
                
                if (tipData.bottom) {
                    position.top = element.offset().top - getHeight(oneTip);
                }
                
                if (oneTip.css('position') == 'fixed') {
                    position.top = position.top - $(window).scrollTop();
                }
                
                oneTip.css(position);
                
                //Выставляем смещение выноски
                if (tipData.correctionX) {
                    oneTip.find('.corner').css({
                        left: tipData.correctionX
                    });
                }
            },
            
            showInfo = function(step)
            {
                var tourStep = data.tour[step];
                overlayShow();
                
                if (tourStep.tips)
                    $.each(tourStep.tips, function(i, tip) {
                        
                        //Устанавливаем значения по умолчанию
                        tip = $.extend({
                            tipText: '',
                            css: {},
                            animation: null,
                            position:['left', 'bottom'],
                            correctionX: 0,
                            correctionY: 0
                        }, tip);
                        
                        var element = $(tip.element).first();
                        
                        if (element.length) {
                            var oneTip = $('<div class="tipInfoTour" />')
                                oneTip.html('<div class="tipInfoTourContent">'+tip.tipText+'</div>')
                                   .append('<i class="corner"><span class="line"><span class="arrow"></span></span></i>')
                                   .addClass( tip.position[0]+tip.position[1][0].toUpperCase()+tip.position[1].substring(1) )
                                   .css(tip.css)
                                   .appendTo('body');
                            
                            updateTipInfoPosition(tip.element, tip, oneTip);
                                                    
                            $(window).on('resize.tour', function() {
                                updateTipInfoPosition(tip.element, tip, oneTip);
                            });
                            
                            if (tip.animation) {
                                oneTip.addClass(tip.animation + ' animated');
                            }
                        }
                   });
               var 
                   text = $('<div class="contentTour">').html(tourStep.message);
               
               $('<div class="infoTour" />')
                   .append('<div class="infoBack"/>')
                   .append('<h2>'+lang.t('Информация')+'</h2>')
                   .append(text)
                   .append(getStatusLine())
                   .appendTo('body')                   
                   .css('marginTop', -$('.infoTour').height()/2)                   
                   .draggable({handle: 'h2'});
                   
               $('.goNext').addClass('pulse animated infinite');
            },
            
            getWidth = function(element) {
                return element.width() + parseInt(element.css('paddingLeft')) + parseInt(element.css('paddingRight'));
            },
            
            getHeight = function(element) {
                return element.height() + parseInt(element.css('paddingTop')) + parseInt(element.css('paddingBottom'));
            },
            
            updateTipInfoPosition = function(elementString, tipData, oneTip) {
                var 
                    element = $(elementString),
                    horiz = tipData.position[0],
                    vert = tipData.position[1],
                    cornerSourceY,
                    css = {};
                    
                switch(horiz) {
                    case 'left': css.left = element.offset().left + getWidth(element) - getWidth(oneTip); 
                        if (vert == 'middle') {
                            css.left = css.left - getWidth(element);
                        }
                        break;
                    case 'center': css.left = element.offset().left + getWidth(element)/2 - getWidth(oneTip)/2; break;
                    case 'right': css.left = element.offset().left; 
                        if (vert == 'middle') {
                            css.left = css.left + getWidth(element);
                        }
                        break;
                }
                
                switch(vert) {
                    case 'top': css.top = element.offset().top - getHeight(oneTip) - data.options.tipInfoCorrectionY; cornerSourceY = element.offset().top; break;
                    case 'middle': css.top = element.offset().top + getHeight(element)/2 - getHeight(oneTip)/2; cornerSourceY = element.offset().top + getHeight(element)/2; break;
                    case 'bottom': css.top = element.offset().top + getHeight(element) + data.options.tipInfoCorrectionY; cornerSourceY = element.offset().top + getHeight(element);  break;
                }
                
                css.marginTop = tipData.correctionY;
                css.marginLeft = tipData.correctionX;
                oneTip.css(css);
                
                //Устанавливаем высоту выноски
                var cornerCss = {
                    left: 'auto',
                    right: 'auto',
                    top: 'auto',
                    bottom: 'auto',
                    width: 10,
                    height: 1,
                }
                if (vert == 'middle') {
                    
                    //Выноска горизонтальная
                    cornerCss.top = cornerSourceY-css.top;                    
                    
                    if (horiz == 'right') {
                        cornerCss.width = (css.left + tipData.correctionX) - (element.offset().left + getWidth(element));                        
                        cornerCss.left = -cornerCss.width;
                    }
                    if (horiz == 'left') {
                        cornerCss.width = element.offset().left - (css.left + getWidth(oneTip) + tipData.correctionX);
                        cornerCss.right = -cornerCss.width;
                    }
                    
                } else {
                    //Выноска вертикальная
                    cornerCss.left = element.offset().left + getWidth(element)/2 - css.left;                    
                    if (vert == 'bottom') {
                        cornerCss.height = Math.abs(cornerSourceY - css.top) + css.marginTop;
                        cornerCss.top = -cornerCss.height;
                    }
                    if (vert == 'top') {
                        cornerCss.height = Math.abs(cornerSourceY - (css.top + getHeight(oneTip))) - css.marginTop;
                        cornerCss.bottom = -cornerCss.height;
                    }
                }

                oneTip.find('.corner').css(cornerCss);
            },
            
            getStatusLine = function() 
            {
                var 
                    tourStep = data.tour[data.curStep],
                    curSubStep = '',
                    showNext = false;
                    
                if (tourStep.type == 'form') {
                    var 
                        curSubStep = '<span class="tourSubStep">.'+(data.curSubStep)+'</span>',
                        showNext = curSubStep < data.totalSubSteps;
                }
                
                var infoline = $('<div class="infoLineTour">').html(
                    '<span class="infoLineStep">'+lang.t('шаг')+' <strong>'+data.curStepIndex+'</strong>'+curSubStep+' '+lang.t('из')+' '+data.tourTotalSteps+'</span>'
                );
                
                if (data.curStepIndex>1) {
                   infoline.prepend( $('<a class="goPrev">'+lang.t('назад')+'</a>').on('click', goPrev) );
                   $('body').on('keydown.tour', function(e) {
                        if (e.ctrlKey && e.keyCode == 37) goPrev();
                   });
                }
                if (data.curStepIndex < data.tourTotalSteps || showNext) {
                   infoline.append( $('<a class="goNext">'+lang.t('далее')+'</a>').on('click', goNext) );
                   $('body').on('keydown.tour', function(e) {
                        if (e.ctrlKey && e.keyCode == 39) goNext();
                   });                   
                }  
                
                infoline.append( $('<a class="tourClose"><span>'+lang.t('завершить')+'</span></a>').on('click', methods.stop) );
                
                return infoline;
            },
            
            hideStep = function()
            {
                overlayHide();
                hideDialog();
                $('body').off('dialogBeforeDestroy.tour');
                $('.infoTour, .tipTour, .tipInfoTour').remove();
                $(window).off('.tour');
                $('*').off('.tour');
                clearTimeout(timeoutHandler);

                if (data.curStep && typeof(data.tour[data.curStep].onStop) == 'function') data.tour[data.curStep].onStop();
            },
            
            hideDialog = function()
            {
                overlayHide();
                $('#tipDialog').remove();
            };
        
        if ( methods[method] ) {
            methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
        } else if ( typeof method === 'object') {
            return methods.init.apply( this, args );
        }
    }; 
})(jQuery);

$(function() {
    /**
    * Тур по первичной настройке сайта
    */
    var tourTopics = {
        'base': lang.t('Базовые настройки'),
        'products': lang.t('Категории и Товары'),
        'menu': lang.t('Меню'),
        'article': lang.t('Новости'),
        'delivery': lang.t('Способы доставки'),
        'payment': lang.t('Способы оплаты'),
        'debug': lang.t('Правка информации на сайте')
    }

    var welcomeTour = {}

    welcomeTour.commonStart =  {
        'index': {
                url: '/',
                topic: tourTopics.base,
                type: 'dialog',
                message: lang.t(
                '<div class="tourIndexWelcome">Рады приветствовать Вас!</div>\
                    <div class="tourIndexBlock">\
                        <div class="tourBorder"></div>\
                        <p class="tourHello">Хотели бы Вы пройти<br> интерактивный курс обучения?</p>\
                        <div class="tourLegend">\
                            <a class="tourTop first indexTipToAdmin" data-step="index-tip-toadmin">Базовые настройки</a>\
                            <a class="adminCatalogAddInfo" data-step="admin-catalog-add-info">Категории<br> и Товары</a>\
                            <a class="tourTop menuCtrl" data-step="menu-ctrl">Текстовые<br> страницы<br> (Меню)</a>\
                            <a class="articleCtrl" data-step="article-ctrl">Новости</a>'+
                            (global.scriptType != 'Shop.Base' ? '<a class="tourTop shopDeliveryCtrl" data-step="shop-deliveryctrl">Способы доставки</a>\
                            <a class="shopPaymentCtrl" data-step="shop-paymentctrl">Способы оплаты</a>' : '') +
                            '<a class="tourTop debugIndex" data-step="debug-index">Правка информации на сайте</a>\
                        </div>\
                    </div>\
                </div>', null, 'tourWelcome'),
                buttons: {
                    yes: {
                        text: lang.t('Да, пройти курс с начала'),
                        step: 'index-tip-toadmin'
                    },
                    no: false
                }
            },
            
            'index-tip-toadmin': {
                url: '/',
                topic: tourTopics.base,            
                tip: {
                    element: '.action-zone .action.to-admin',
                    tipText: lang.t('Все настройки интернет-магазина располагаются в административной панели. Нажмите на кнопку быстрого перехода в панель администрирования.')
                }
            },
            
            'admin-index': {
                url: '%admin%/',
                topic: tourTopics.base,            
                type: 'info',
                message: lang.t('Это главный экран панели управления магазином. Здесь могут размещаться информационные виджеты с самой актуальной информацией по ключевым показателям магазина.'),
                tips: [
                    {
                        element: '.addwidget',
                        tipText: lang.t('Нажав на плюс, можно настроить отображение виджетов'),
                        position: ['right', 'bottom'],  //Положение относительно element - [(left|center|right),(top|middle|bottom)]
                        animation: 'bounceInDown'
                    },
                    {
                        element: '.action-zone .action.to-site',
                        tipText: lang.t('Быстрый переход на сайт'),
                        position: ['left', 'bottom'],
                        animation: 'slideInLeft'
                    },
                    {
                        element: '.action-zone .action.clean-cache',
                        tipText: lang.t('Кнопка для очистки кэша системы'),
                        position: ['left', 'bottom'],
                        correctionY: 50,
                        animation: 'slideInDown'
                    },
                    {
                        element: '.panel-menu .current',
                        tipText: lang.t('Показан текущий сайт. Если управление ведется несколькими сайтами, то при наведении будет показан список сайтов.'),
                        position: ['left', 'bottom'],
                        correctionY: 100,
                        css: {
                            width: 300
                        },
                        animation: 'bounceInDown'
                    }
                ]
            },
            
            'admin-index-to-siteoptions': {
                url: '%admin%/',
                topic: tourTopics.base,            
                tip: {
                    element: 'a[href$="/menu-ctrl/"]',
                    tipText: lang.t('Перейдите в раздел <i>Веб-сайт &rarr; Настройка сайта</i>'),
                    correctionX: 40
                },
                onStart: function() {
                    $('a[href$="/site-options/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('a[href$="/site-options/"]').removeClass('menuTipHover');
                }
            },
            
            'admin-siteoptions': {
                url: '%admin%/site-options/',
                topic: tourTopics.base,            
                type: 'info',
                message: lang.t('В этом разделе необходимо настроить основные параметры текущего сайта, к которым относятся: '+
                '<ul><li>контактные данные администратора магазина (будут использоваться для уведомлений обо всех событиях в интернет-магазине);</li>'+
                '<li>реквизиты организации продавца (будут использоваться для формирования документов покупателям);</li>'+
                '<li>логотип интернет-магазина;</li>'+
                '<li>тема оформления сайта;</li>'+
                '<li>параметры писем, отправляемых интернет-магазином.</li></ul>', null, 'tourAdminSiteOptions'),
                tips:[
                    {
                        element: '.tab-container li:eq(3)',
                        tipText: lang.t('Заполните сведения во всех вкладках. При наведении мыши на символ вопроса, расположенный справа от поля, отобразится подсказка по нзначению и заполнению поля.'),
                        position: ['right', 'middle'],
                        correctionX:50,
                        css: {
                            width:300
                        },
                        animation: 'slideInDown'
                    }
                ],
                buttons: {
                    next: 'admin-siteoptions-save'
                }
            },
            
            'admin-siteoptions-save': {
                url: '%admin%/site-options/',
                topic: tourTopics.base,            
                tip: {
                    element: '.button.saveform',
                    tipText: lang.t('Заполните сведения во всех вкладках, расположенных выше. Далее, нажмите на зеленую кнопку, чтобы сохранить изменения.'),
                    correctionY: -15,
                    bottom: true,
                    css: {
                        position: 'fixed'
                    }
                },
                watch: {
                    element: '.button.saveform',
                    event: 'click',
                    next:'admin-siteoptions-to-products'
                }
            },
            
            'admin-siteoptions-to-products': {
                url: '%admin%/site-options/',
                topic: tourTopics.base,
                tip: {
                    element: 'a[href$="/catalog-ctrl/"]',
                    tipText: lang.t('Теперь необходимо добавить товары, для этого перейдите в раздел <i>Товары &rarr; Каталог товаров</i>'),
                    correctionX: 40,
                    css: {
                        zIndex: 50
                    }
                },
                onStart: function() {
                    $('.menu ul a[href$="/catalog-ctrl/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('.menu ul a[href$="/catalog-ctrl/"]').removeClass('menuTipHover');
                }
            },
            
            'admin-catalog-add-info': {
                url: '%admin%/catalog-ctrl/',
                topic: tourTopics.products,
                type: 'info',
                message: lang.t('В этом разделе происходит управление товарами и категориями товаров. \
                            Обратите внимание на расположение кнопок создания объектов.\
                            <p>На следующем шаге мы попробуем создать, для примера, одну категорию и один товар. \
                            По аналогии вы сможете наполнить каталог собственными категориями и товарами.', null, 'tourAdminCatalogAddInfo'),
                tips: [
                    {
                        element: '.treehead .addspec',
                        tipText: lang.t('Создать спец.категорию <br>(например: новинки, лидеры продаж,...)'),
                        position:['left', 'bottom'],
                        animation: 'slideInLeft'
                    },
                    {
                        element: '.treehead .add',
                        tipText: lang.t('Создать категорию товаров'),
                        position:['left', 'bottom'],
                        correctionY:60,
                        animation: 'slideInDown'
                    },
                    {
                        element: '.c-head .button.add.rs-active',
                        tipText: lang.t('Создать товар'),
                        position:['left', 'middle'],
                        correctionX:-30,
                        animation: 'fadeInLeft'
                    },
                    {
                        element: '.c-head .rs-active:contains("Импорт/Экспорт")',
                        tipText: lang.t('Через эти инструменты можно массово загрузить товары, <br>категории в систему через CSV файлы. Подробности в <a target="_blank" href="http://readyscript.ru/manual/catalog_csv_import_export.html">документации</a>.'),
                        animation: 'slideInDown'
                        
                    }
                ],
                buttons: {
                    next: 'admin-siteoptions-save'
                }
            },
            
            'admin-catalog-add-dir': {
                url: '%admin%/catalog-ctrl/',
                topic: tourTopics.products,
                tip: {
                    element: '.treehead .add',
                    tipText: lang.t('Перед добавлением товара нужно создать его категорию. Для примера, создадим тестовую категорию "<b>Холодильники</b>". Нажмите на кнопку <i>создать категорию</i>'),
                    bottom:true
                },
                watch: {
                    element: '.treehead .add',
                    event: 'click',
                    next: 'admin-catalog-add-dir-form'
                }
            },
            
            //Шаги, связанные с добавлением категории
            
            'admin-catalog-add-dir-form': {
                url: '%admin%/catalog-ctrl/?pid=0&do=add_dir',
                topic: tourTopics.products,
                type: 'form',
                tips: [
                    {
                        element: '.crud-form [name="name"]',
                        tipText: lang.t('Укажите название - <b>Холодильники</b>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="alias"]',
                        tipText: lang.t('Укажите Псевдоним - это имя на английском языке, которое будет использоваться для построения URL-адреса страницы'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.formbox .tab-container > li:eq(2) > a',
                        tipText: lang.t('Перейдите на вкладку <i>Характеристики</i>. Для примера создадим 1 характеристику (мощность), <br>\
                                  которая обязательно будет присутствовать у всех товаров создаваемой категории.'),
                        watch: {
                            event: 'click'
                        }
                    },
                    {
                        element: '.property-actions .add-property',
                        tipText: lang.t('Нажмите добавить характеристику'),
                        watch: {
                            event: 'click',
                        },
                        onStart: function() {
                            $('.frame[data-name="tab2"]').append('<div style="height:110px" id="tourPlaceholder1"></div>');
                        }
                    },
                    {
                        element: '.property-form .p-title',
                        tipText: lang.t('Укажите название - <b>Мощность</b>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.property-form .p-type',
                        tipText: lang.t('Укажите тип - <b>Список</b>, чтобы в дальнейшем включить фильтр по данной харктеристике'),
                        checkPattern: /^(list)$/gi
                    },
                    {
                        element: '.property-form .p-unit',
                        tipText: lang.t('Укажите единицу измерения - <b>Вт</b>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.property-form .p-values',
                        tipText: lang.t('Укажите с новой строки или через точку с запятой 2 возможных значения характеристики - <b>1000</b> и <b>2000</b>'),
                        checkPattern: /^1000[;\n]2000$/gi
                    },
                    {
                        element: '.formbox .p-values-block .apply span',
                        tipText: lang.t('Примените изменения'),
                        correctionX:-230,
                        watch: {
                            event: 'click'
                        }
                    },                
                    {
                        element: '.property-form .add',
                        tipText: lang.t('<i>Добавьте</i> характеристику к категории'),
                        css: {
                            marginTop:46
                        },
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        waitNewContent: true,
                        element: '.property-container .property-item .h-public',
                        tipText: lang.t('Установите флажок <i>Отображать в поиске на сайте</i>, чтобы по данной характеристике можно было отфильтровать товары на сайте. Подробности в <a href="http://readyscript.ru/manual/catalog_categories.html#cat_tab_characteristics" target="_blank">документации</a>.'),
                        checkPattern: true,
                        correctionX: -230,
                        css: {
                            width:300
                        }
                    },
                    {
                        element: '.bottomToolbar .saveform',
                        tipText: lang.t('Нажмите на кнопку <i>Сохранить</i>, чтобы создать категорию'),
                        bottom: true,
                        css: {
                            position: 'fixed'
                        },
                        correctionY: -20,
                        watch: {
                            element: 'body',
                            event: 'crudSaveSuccess',
                            next: 'admin-catalog-add-product'
                        }
                    }
                ]
            },        
            
            'admin-catalog-add-product': {
                url: '%admin%/catalog-ctrl/',
                topic: tourTopics.products,
                tip: {
                    element: '.button.add.rs-active',
                    tipText: lang.t('Чтобы добавить товар, нажмите на зеленую кнопку <i>Добавить товар</i>'),
                },
                watch: {
                    element: '.button.add.rs-active',
                    event: 'click',
                    next: 'admin-catalog-add-product-form'
                }
            },
            
            'admin-catalog-add-product-form': {
                url: '%admin%/catalog-ctrl/?dir=0&do=add',
                topic: tourTopics.products,
                type: 'form',
                tips: [
                    {
                        element: '.crud-form [name="title"]',
                        tipText: lang.t('Укажите название товара - <b>Холодильник ТОМАС</b>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="alias"]',
                        tipText: lang.t('Укажите любое URL имя на англ.языке. <br>Будет использовано для создания адреса страницы товара'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '#tinymce-description_parent',
                        tinymceTextarea: '#tinymce-description',
                        tipText: lang.t('Укажите описание товара'),
                        bottom:true,
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="barcode"]',
                        tipText: lang.t('Укажите артикул товара'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name^="excost"]:first',
                        tipText: lang.t('Укажите стоимость товара'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name^="xdir[]"]',
                        tipText: lang.t('Выберите категорию - <b>Холодильники</b>'),
                        checkSelectValue: /^.*$/gi,
                        correctionX:150,
                        correctionY:-300
                    },
                    {
                        element: '.formbox .tab-container li:eq(1) > a',
                        tipText: lang.t('Теперь добавим характеристику товару, для этого перейдите на соответствующую вкладку'),
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        ifTrue: function() {
                            return !$('.item-title:contains("Мощность")').length>0;
                        },
                        elseStep: 'myval_noajax',
                        element: '.property-actions .add-property',
                        tipText: lang.t('Нажмите <i>Добавить характеристику</i>'),
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        element: '.property-form .p-proplist',
                        tipText: lang.t('Выберите характеристику - <b>Мощность</b>'),
                        checkPattern: /^\d+$/gi
                    },
                    
                    {
                        element: '.property-form .add',
                        tipText: lang.t('<i>Добавьте</i> характеристику к товару'),
                        css: {
                            marginTop:46
                        },
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        label: 'myval_ajax',
                        waitNewContent: true,
                        ifTrue: function() {
                            //Если есть флажок - "задать персональное значение"
                            return $('.property-item:contains("Мощность") .h-useval').length>0;
                        },
                        element: '.property-item:contains("Мощность") .h-useval',
                        tipText: lang.t('Отметьте флажок, чтобы задать индивидуальное значение характеристики для товара'),
                        checkPattern: true,
                        next: 'propval'
                    },
                                    
                    {
                        label: 'myval_noajax',
                        ifTrue: function() {
                            //Если есть флажок - "задать персональное значение"
                            return $('.property-item:contains("Мощность") .h-useval').length>0;
                        },
                        element: '.property-item:contains("Мощность") .h-useval',
                        tipText: lang.t('Отметьте флажок, чтобы задать индивидуальное значение характеристики для товара'),
                        checkPattern: true
                    },
                    {
                        label: 'propval',
                        element: '.property-item:contains("Мощность") .inline-item:contains("1000") input',
                        tipText: lang.t('Укажите, что мощность холодильника - 1000 Вт'),
                        checkPattern: true
                    },
                    {
                        element: '.formbox .tab-container li:eq(2) > a',
                        tipText: lang.t('На закладке <i>Комплектации</i> можно задать остатки, а также <a href="http://readyscript.ru/manual/catalog_products.html#catalog_products_tab_offers">вариации(комплектации)</a> товара.'),
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        element: '.crud-form [name^="offers[main][stock_num]"]:first',
                        tipText: lang.t('Укажите остаток товара на всех складах - <i>10</i>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.formbox .tab-container li:eq(6) > a',
                        tipText: lang.t('Загрузите фотографии на вкладке <i>Фото</i>'),
                        watch: {
                            event: 'click',
                        }
                    },
                    {
                        element: '.bottomToolbar .saveform',
                        tipText: lang.t('При желании вы можете заполнить сведения на оставшихся вкладках товара.<br>'+
                                 'Затем нажмите на кнопку <i>Сохранить</i>, чтобы создать товар'),
                        bottom: true,
                        css: {
                            position: 'fixed'
                        },
                        correctionY: -20,
                        watch: {
                            element: 'body',
                            event: 'crudSaveSuccess',
                            next: 'admin-catalog'
                        },
                        
                    }
                ],
            },
            
            'admin-catalog': {
                url: '%admin%/catalog-ctrl/',
                topic: tourTopics.products,
                type: 'info',
                message: lang.t('Товар и категория добавлены. \
                          В дальнейшем Вы часто будете пользоваться данным разделом, чтобы корректировать описания товаров, цены, количество товаров, и т.д. \
                          Предлагаем ознакомиться с основными элементами управления, присутствующими на данной странице.'),
                tips: [
                    {
                        element: '.rs-table .options',
                        tipText: lang.t('Настройка состава колонок <br>таблицы и сортировки по-умолчанию'),
                        animation: 'slideInDown'
                    },
                    {
                        element: '.rs-table thead th:eq(4)',
                        tipText: lang.t('При нажатии на заголовок колонки <br>можно изменять сортировку данных в таблице'),
                        correctionY:70,
                        correctionX:40,
                        animation: 'slideInDown'
                    },
                    {
                        element: '.bottomToolbar .right-column .crud-multiedit',
                        tipText: lang.t('В нижней панели представлены действия (редактировать, удалить), <br>которые можно применить ко всем <br>отмеченным элементам (товарам или категориям).'),
                        position: ['right', 'top'],
                        animation: 'bounceInDown',
                        css: {
                            position:'fixed'
                        }
                    },
                    {
                        element: '.treehead .showchilds-on, .showchilds-off',
                        tipText: lang.t('Включить/выключить показ товаров из вложенных категорий'),
                        position:['right', 'top'],
                        correctionY:-20,
                        animation: 'rotateIn'
                    },                
                    {
                        element: '.rs-table .chk',
                        tipText: lang.t('Можно отметить товары как на одной,<br> так и на всех страницах'),
                        position:['right', 'bottom'],
                        animation: 'slideInLeft'
                    },
                    {
                        element: '.treebody > li:eq(1) .move',
                        tipText: lang.t('Сортируйте категории с помощью перетаскивания'),
                        position:['right', 'bottom'],
                        animation: 'slideInDown'
                    }
                ],
                onStart: function() {
                    var act = function() {
                        $('.rs-table .chk').addClass('chk-over');
                        $('.treebody > li:eq(3)').addClass('over');
                        $('.treebody > li:eq(1)').addClass('drag');
                        $('.rs-table tbody tr:eq(7)').addClass('over');
                    }
                    
                    $('body').on('new-content.tour', act);
                    act();
                },
                onStop: function() {
                    $('.rs-table .chk').removeClass('chk-over');
                    $('.treebody > li:eq(3)').removeClass('over');
                    $('.treebody > li:eq(1)').removeClass('drag');
                    $('.rs-table tbody tr:eq(7)').removeClass('over');
                }
            },
            
            'to-menu-ctrl': {
                url: '%admin%/catalog-ctrl/',
                topic: tourTopics.products,
                tip: {
                    element: 'a[href$="/menu-ctrl/"]',
                    tipText: lang.t('Перейдите в раздел <i>Веб-сайт &rarr; Меню</i>'),
                    correctionX: 40,
                    css: {
                        zIndex:50
                    }
                },
                onStart: function() {
                    $('.menu ul a[href$="/menu-ctrl/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('.menu ul a[href$="/menu-ctrl/"]').removeClass('menuTipHover');
                }
            },
            
            'menu-ctrl': {
                url: '%admin%/menu-ctrl/',
                topic: tourTopics.menu,
                type: 'info',
                message: lang.t('В данном разделе можно создавать иерархию страниц сайта разных типов, которые могут быть доступны пользователям через меню. Каждой странице будет присвоен определенный URL адрес, по которому она будет доступна из браузера. \
                         <p>Например, если вы желаете: <ul>\
                         <li>создать страницу с какой-либо текстовой информацией, то необходимо создать пункт меню с типом "<b>Статья</b>".</li>\
                         <li>создать страницу, на которой должны быть представлены функциональные блоки с каким-либо более сложным поведением (например, форма обратной связи), то необходимо создать пункт меню с типом "<b>Страница</b>". \
                         Далее эту страницу можно будет настроить в разделе Веб-сайт &rarr; Конструктор сайта.</li>\
                         <li>создать простую ссылку в меню, то используйте тип "<b>Ссылка</b>" для такого пункта меню.</li>\
                         </ul><p>Ознакомьтесь с основными функциональными кнопками на данной странице. \
                         На следующем шаге, мы создадим для примера пункт меню с информацией о рекламной акции в интернет-магазине.', null, 'tourMenuCtrlInfo'),
                tips: [
                    {
                        element: '.c-head .button.add.rs-active:contains("добавить пункт меню")',
                        tipText: lang.t('Создать новый пункт меню'),
                        animation: 'bounceInDown'
                    },
                    {
                        element: '.c-head .rs-active:contains("Импорт/Экспорт")',
                        tipText: lang.t('Через эти инструменты можно массово <br>загрузить пункты меню в систему через CSV файлы.'),
                        animation: 'slideInDown',
                        correctionY:60
                    },
                    {
                        element: '.activetree  .allplus',
                        tipText: lang.t('Развернуть отображение дерева пунктов меню'),
                        position:['right', 'bottom'],
                        animation: 'slideInLeft'
                        
                    },
                    {
                        element: '.activetree  .allminus',
                        tipText: lang.t('Свернуть отображение дерева пунктов меню'),
                        position:['right', 'middle'],
                        correctionX:40,
                        animation: 'slideInDown'
                    }
                ]
            },
            
            'menu-ctrl-add': {
                url: '%admin%/menu-ctrl/',
                topic: tourTopics.menu,
                tip: {
                    element: '.c-head .button.add.rs-active',
                    tipText: lang.t('Добавим на сайте раздел <b>Акция</b>, в котором будет представлена текстовая информация. \
                              Нажмите на кнопку <i>Добавить пункт меню</i>')
                },
                watch: {
                    element: '.button.add.rs-active',
                    event: 'click',
                    next: 'menu-ctrl-add-form'
                }
            },
            
            'menu-ctrl-add-form': {
                url: '%admin%/menu-ctrl/?do=add',
                topic: tourTopics.menu,
                type: 'form',
                tips: [
                    {
                        element: '.crud-form [name="title"]',
                        tipText: lang.t('Укажите название пункта меню - <b>Акция</b>'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="alias"]',
                        tipText: lang.t('Укажите любое название пункта меню на Англ. языке. <br>Оно будет использоваться для построения URL адреса раздела.'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '#tinymce-acontent_parent',
                        tinymceTextarea: '#tinymce-acontent',
                        tipText: lang.t('Укажите информацию об акции'),
                        bottom:true,
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.mceButton.mce_images',
                        tipText: lang.t('Используйте кнопку Images Manager с зеленым<br> плюсом, чтобы добавить изображения к тексту'),
                        correctionY:10,
                        correctionX:-50,
                        checkTimeout: 5000
                    },
                    {
                        element: '.bottomToolbar .saveform',
                        tipText: lang.t('После ввода всей необходимой текстовой информации, нажмите \
                                 <br>на кнопку <i>Сохранить</i>, чтобы создать раздел на сайте, который отобразится в меню'),
                        bottom: true,
                        css: {
                            position: 'fixed'
                        },
                        correctionY: -20,
                        watch: {
                            element: 'body',
                            event: 'crudSaveSuccess',
                            next: 'to-article-ctrl'
                        },
                        
                    }
                ]
            },
            
            'to-article-ctrl': {
                url: '%admin%/menu-ctrl/',
                topic: tourTopics.article,
                tip: {
                    element: '.menu a:contains("Веб-сайт")',
                    tipText: lang.t('Перейдите в раздел <i>Веб-сайт &rarr; Контент</i>'),
                    correctionX: 50,
                    css: {
                        zIndex: 50
                    }
                },
                onStart: function() {
                    $('.menu ul a[href$="/article-ctrl/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('.menu ul a[href$="/article-ctrl/"]').removeClass('menuTipHover');
                }
            },
            
            'article-ctrl': {
                url: '%admin%/article-ctrl/',
                topic: tourTopics.article,
                type: 'info',
                message: lang.t('На этой странице происходит управление списками текстовых материалов, например новостями.\
                         <p>Для добавления новости на сайте, достаточно создать статью в соответствующей категории.\
                         <p>Также в этом разделе административной панели могут размещаться статьи, используемые темой оформления на различных страницах.'),
                tips: [
                    {
                        element: '.treehead .add',
                        tipText: lang.t('Создать категорию статей'),
                        position:['right', 'top'],
                        animation: 'slideInDown'
                    },
                    {
                        element: '.c-head .button.add.rs-active',
                        tipText: lang.t('Создать статью'),
                        animation: 'slideInDown'
                    },
                    {
                        element: '.treebody > li:eq(0)',
                        tipText: lang.t('Категория статей'),
                        position:['right', 'middle'],
                        animation: 'slideInLeft',
                        correctionX:40
                    }
                ]
            }
    }

    welcomeTour.commonEnd = {
            'to-index': {
                url: global.scriptType != 'Shop.Base' ? '%admin%/shop-paymentctrl/' : '%admin%/article-ctrl/',
                topic: tourTopics.payment,
                tip: {
                    element: '.action-zone .action.to-site',
                    tipText: lang.t('Основные настройки в административной панели произведены. Желаете добавлять товары, категории, новости, и т.д., не заходя в панель администрирования? Нажмите на кнопку <i>Перейти на сайт</i>, чтобы узнать как.')
                },
                watch: {
                    element: '.action-zone .action.to-site',
                    event: 'click',
                    next: 'debug-index'
                }
            },
            
            'debug-index': {
                url: '/',
                topic: tourTopics.debug,
                tip: {
                    element: '.debug-mode-switcher .rs-switch',
                    tipText: lang.t('Включите режим отладки, чтобы редактировать элементы прямо на странице'),
                    correctionY:40
                },
                whileTrue: function() {
                    return $('.debug-mode-switcher .rs-switch.off').length;
                }            
            },
            
            'debug-text': {
                url: '/',
                topic: tourTopics.debug,
                tip: {
                    element: '.module-wrapper:has([data-debug-contextmenu]):first',
                    tipText: lang.t('Любой товар, категорию, пункт меню, и т.д. на данной странице можно отредактировать, удалить или создать, кликнув над ним правой кнопкой мыши и выбрав необходимое действие.'),
                    correctionY:10,
                    css: {
                        zIndex:3
                    },
                    notFound: 'finish'
                },
                watch: {
                    element: '',
                    event: 'showContextMenu',
                    next: 'debug-block-text'
                },
                checkTimeout: 15000
            },
            
            'debug-block-text': {
                url: '/',
                topic: tourTopics.debug,
                tip: {
                    element: '.module-wrapper:eq(0) .debug-icon-blockoptions',
                    tipText: lang.t('Любой блок можно настроить, нажав на иконку с изображением гаечного ключа.'),
                    correctionY:10,
                    notFound: 'finish',
                    css: {
                        zIndex:3
                    }
                },
                onStart: function() {
                    $('.module-wrapper:eq(0)').addClass('over');
                },                
                onStop:  function() {
                    $('.module-wrapper:eq(0)').removeClass('over');
                },      
                watch: {
                    element: '.debug-icon-blockoptions',
                    event: 'click',
                    next: 'finish'
                },
                checkTimeout: 15000
            },
            
            'finish': {
                url: '/',
                topic: tourTopics.debug,
                type:'dialog',
                message: lang.t('<span class="finishText">Интерактивный курс по базовым настройкам<br> интернет-магазина успешно завершен.</span> <br>Более подробную информацию по возможностям платформы ReadyScript можно найти в <a href="http://readyscript.ru/manual/" target="_blank"><u>документации</u></a>.'),
                buttons: {
                    finish: {
                        text: 'Закрыть окно',
                        step: false                        
                    },
                    docs: {
                        text: 'Документация',
                        attr: {
                            href: 'http://readyscript.ru/manual/',
                            target: '_blank'
                        },
                        step:false
                    }
                }
            }
    }

    welcomeTour.shop = {
        'to-shop-deliveryctrl': {
                url: '%admin%/article-ctrl/',
                topic: tourTopics.article,            
                tip: {
                    element: '.menu > li > a[href$="/shop-orderctrl/"]',
                    tipText: lang.t('Теперь перейдем к настройке параметров, связанных с заказами. Перейдите в раздел <i>Магазин &rarr; Доставка &rarr; Способы доставки</i>'),
                    css: {
                        zIndex:50
                    }
                },
                onStart: function() {
                    $('.menu a[href$="/shop-regionctrl/"]:first').addClass('menuTipHover');
                    $('.menu a[href$="/shop-deliveryctrl/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('.menu a[href$="/shop-regionctrl/"]:first').removeClass('menuTipHover');
                    $('.menu a[href$="/shop-deliveryctrl/"]').removeClass('menuTipHover');
                }
            },
            
            'shop-deliveryctrl': {
                url: '%admin%/shop-deliveryctrl/',
                topic: tourTopics.delivery,
                type:'info',
                message: lang.t('В этом разделе необходимо произвести настройку способов доставок, которые будут\
                         предложены пользователю во время оформления заказа. \
                         <p>До настройки данного раздела, необходимо иметь представление о том, как вы будете доставлять товары вашим покупателям и по каким ценам.\
                         <p>Ознакомьтесь с основными инструментами представленными на данной странице.\
                         <p>На следующем шаге, создадим для примера, "доставку по городу", которая будет стоить 500 руб.', null, 'tourShopDeliveryCtrlInfo'),
                tips: [
                    {
                        element: '.c-head .button.add',
                        tipText: lang.t('Добавить способ доставки'),
                        animation: 'slideInLeft'
                    },
                    {
                        element: '.c-head .rs-active:contains("Импорт/Экспорт")',
                        tipText: lang.t('Через эти инструменты можно массово загрузить способы доставок через CSV файлы.'),
                        animation: 'slideInDown',
                        correctionY:60
                    },
                    {
                        element: '.rs-table .sortdot',
                        tipText: lang.t('Сортировать способы доставок можно с помощью перетаскивания'),
                        position: ['right', 'top'],
                        animation: 'slideInLeft'
                    }
                ]
            },
            
            'shop-deliveryctrl-add': {
                url: '%admin%/shop-deliveryctrl/',
                topic: tourTopics.delivery,
                tip: {
                    element: '.c-head .button.add',
                    tipText: lang.t('Добавим, для примера, способ доставки <b>по городу</b>. Нажмите на кнопку <i>Добавить способ доставки</i>')
                },
                watch: {
                    element: '.c-head .button.add',
                    event: 'click',
                    next: 'shop-deliveryctrl-add-form'
                }            
            },
            
            'shop-deliveryctrl-add-form': {
                url: '%admin%/shop-deliveryctrl/?do=add',
                topic: tourTopics.delivery,
                type: 'form',
                tips: [
                    {
                        element: '.crud-form [name="title"]',
                        tipText: lang.t('Укажите название доставки - <b>по городу</b>. Будет отображено во время оформления заказа в списке возможных способов доставки.'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="description"]',
                        tipText: lang.t('Укажите условия или подробности доставки, которые будут отображаться под названием'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="xzone[]"]',
                        tipText: lang.t('Выберите географические зоны или пункт <b>- все -</b>, <br>чтобы определить регионы пользователей, для которых <br>будет отображен данный способ доставки'),
                        checkPattern: /^(0)$/gi
                    },
                    {
                        element: '.crud-form [name="user_type"]',
                        tipText: lang.t('Выберите категорию пользователей, <br>для которых будет доступна доставка.'),
                        watch: {
                            event: 'click'
                        }
                    },
                    {
                        element: '.crud-form [name="class"]',
                        tipText: lang.t('Расчетный класс отвечает за то, какой модуль <br>будет расчитывать стоимость и обрабатывать доставку. \
                                  Выберите <b>Фиксированная цена</b>. Подробнее о других расчетных классах можно узнать <a href="http://readyscript.ru/manual/shop_delivery.html#shop_delivery_add" target="_blank">в документации</a>'),
                        watch: {
                            event: 'click'
                        }
                    },
                    {
                        element: '.crud-form [name="data[cost]"]',
                        tipText: lang.t('Укажите стоимость доставки по городу'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.bottomToolbar .saveform',
                        tipText: lang.t('После ввода всех необходимых параметров доставки, нажмите \
                                 <br>на кнопку <i>Сохранить</i>'),
                        bottom: true,
                        css: {
                            position: 'fixed'
                        },
                        correctionY: -20,
                        watch: {
                            element: 'body',
                            event: 'crudSaveSuccess',
                            next: 'to-shop-paymentctrl'
                        },
                        
                    }
                ]
            },
            
            'to-shop-paymentctrl': {
                url: '%admin%/shop-deliveryctrl/',
                topic: tourTopics.delivery,
                tip: {
                    element: '.menu > li > a[href$="/shop-orderctrl/"]',
                    tipText: lang.t('Перейдите в раздел <i>Магазин &rarr; Способы оплаты</i>'),
                    css: {
                        zIndex:50
                    }
                },
                onStart: function() {
                    $('.menu a[href$="/shop-paymentctrl/"]').addClass('menuTipHover');
                },
                onStop: function() {
                    $('.menu a[href$="/shop-paymentctrl/"]').removeClass('menuTipHover');
                }
            },
            
            'shop-paymentctrl': {
                url: '%admin%/shop-paymentctrl/',
                topic: tourTopics.payment,
                type: 'info',
                message: lang.t('Перед началом продаж следует настроить способы оплат, которые будут предложены пользователю во время оформления заказа.\
                          <p>Если Вы желаете добавить возможность оплачивать заказы с помощью электроных денег или карт Visa, Mastercard, и т.д., то\
                            Вам необходимо предварительно создать аккаунт магазина на одном из сервисов-агрегаторов платежей - Yandex.Касса, Robokassa, Assist, PayPal, ...\
                          <p>На следующем шаге, добавим для примера, способ оплаты "Безналичный расчет". Это будет означать, что покупатель сможет получить счет сразу после оформления заказа.', null, 'tourShopPaymentCtrlInfo'),
                tips: [
                    {
                        element: '.c-head .button.add',
                        tipText: lang.t('Добавить способ оплаты'),
                        animation: 'slideInDown'
                    },
                    {
                        element: '.rs-table .sortdot',
                        tipText: lang.t('Сортировать способы оплаты можно с помощью перетаскивания'),
                        position: ['right', 'top'],
                        animation: 'slideInLeft'
                    }
                ]
            },
            
            'shop-paymentctrl-add': {
                url: '%admin%/shop-paymentctrl/',
                topic: tourTopics.payment,
                tip: {
                    element: '.c-head .button.add',
                    tipText: lang.t('Добавим, для примера, способ оплаты <b>Безналичный расчет</b>. Нажмите на кнопку <i>Добавить способ оплаты</i>')
                },
                watch: {
                    element: '.c-head .button.add',
                    event: 'click',
                    next: 'shop-paymentctrl-add-form'
                }  
            },
            
            'shop-paymentctrl-add-form': {
                url: '%admin%/shop-paymentctrl/?do=add',
                topic: tourTopics.payment,
                type:'form',
                tips: [
                    {
                        element: '.crud-form [name="title"]',
                        tipText: lang.t('Укажите название способа оплаты - <b>Безналичный расчет</b>. Будет отображено во время оформления заказа в списке возможных способов оплаты.'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="description"]',
                        tipText: lang.t('Укажите условия или подробности оплаты. Будут отображены под названием'),
                        checkPattern: /^(.+)$/gi
                    },
                    {
                        element: '.crud-form [name="first_status"]',
                        tipText: lang.t('Счет будет доступен пользователю только если заказ находится в статусе <i>Ожидает оплату</i>, поэтому выберите стартовый статус <b>Ожидает оплату</b>'),
                        checkSelectValue: /^(Ожидает оплату)$/gi
                    },
                    {
                        element: '.crud-form [name="class"]',
                        tipText: lang.t('Расчетный класс отвечает за то, какой модуль будет обрабатывать платежи <br>или предоставлять документы на оплату пользователю. Выберите <b>Безналичный расчет</b>'),
                        checkPattern: /^(bill)$/gi
                    },
                    {
                        waitNewContent: true,
                        element: '.crud-form [name="data[use_site_company]"]',
                        tipText: lang.t('Установите флажок, чтобы использовать реквизиты, которые были заполнены раннее в разделе <i>Веб-сайт &rarr; Настройка сайта</i>.'),
                        checkPattern: true
                    },
                    {
                        element: '.bottomToolbar .saveform',
                        tipText: lang.t('После ввода всех необходимых параметров оплаты, нажмите \
                                 <br>на кнопку <i>Сохранить</i>'),
                        bottom: true,
                        css: {
                            position: 'fixed'
                        },
                        correctionY: -20,
                        watch: {
                            element: 'body',
                            event: 'crudSaveSuccess',
                            next: 'to-index'
                        },
                    }
                    
                ]
            }
     }

    var tours = 
    {
        'welcome': $.extend({}, 
                            welcomeTour.commonStart, 
                            global.scriptType != 'Shop.Base' ? welcomeTour.shop : {},
                            welcomeTour.commonEnd)
    };
    
    $.tour(tours, {
        baseUrl: global.folder+'/',
        folder: global.folder,
        adminSection: global.adminSection
    });
    
});