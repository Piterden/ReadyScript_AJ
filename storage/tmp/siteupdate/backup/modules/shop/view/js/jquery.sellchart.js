    /**
    * Plugin, активирующий вкладку "характеристики" у товаров
    */
(function($){
    $.fn.sellChart = function(method) {
        var defaults = {
            yearsList: '.years-list',
            placeholder: '#placeholder',
            plotOptions: {
                xaxis: {
                    mode: 'time', 
                    minTickSize: [1, "month"], 
                    monthNames: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"]
                },
                yaxis: {
                    tickDecimals:0
                },
                lines: { show: true },
                points: { show: true },
                legend: {
                    show: true,
                    noColumns: 1, // number of colums in legend table
                    margin: 5, // distance from grid edge to default legend container within plot
                    backgroundColor: '#fff', // null means auto-detect
                    backgroundOpacity: 0.85 // set to 0 to avoid background
                },
                grid: {
                        hoverable: true,
                        borderWidth: 1,
                        borderColor: '#D0D0D0'
                      },
                hooks: {
                    processRawData: function(plot, series, data, datapoints) {
                        var seriesData = [];
                        $.each(data, function(key, val) {
                            seriesData.push([val.x, val.y]);
                        });

                        series.originalData = $.extend({}, data);
                        series.data = seriesData;
                    }
                    
                }
            },
            data: [],
            tooltipDate: null,
            currency: ''
        }, 
        args = arguments;
        
        return this.each(function() {
            var $this = $(this), 
                previousPoint,
                data = $this.data('sellChart');
            
            var methods = {
                init: function(initoptions) {                    
                    if (data) return;
                    data = {}; $this.data('sellChart', data);
                    data.opt = $.extend(true, defaults, initoptions);
                    
                    initYears();
                    $(data.opt.placeholder).bind("plothover", plotHover);
                }
            }
            
            //private
            var initYears = function() {
                var yearList = $(data.opt.yearsList, $this).html('');
                var i = 0;
                $.each(data.opt.data, function(key, val) {
                    val.color = i;
                    ++i;
                    yearList.append('<div class="year-div">'+
                                    '<input type="checkbox" name="'+key+'" id="year_'+key+'" checked>'+
                                    '<label for="year_'+key+'">'+key+'</label>'+
                                    '</div>');
                });
                yearList.find("input").change(plotAccordingToChoices);
                plotAccordingToChoices();
            },
                
            plotAccordingToChoices = function() 
            {
                var dataset = [], 
                    yearList = $(data.opt.yearsList, $this);
                
                if (yearList.length) {
                    yearList.find("input:checked").each(function () {
                        var key = $(this).attr("name");
                        if (key && data.opt.data[key])
                            dataset.push(data.opt.data[key]);
                    });
                } else {
                    dataset = data.opt.data;
                }

                if (dataset.length > 0) {
                    $.plot($(data.opt.placeholder), dataset, data.opt.plotOptions);
                }
            },
            
            showTooltip = function(x, y, contents) {
                $("#flottooltip").remove();
                $('<div id="flottooltip" />').html(contents).css( {
                    top: y + 10,
                    left: x + 10
                }).appendTo("body").fadeIn(200);
            },
            
            number_format = function(number, decimals, dec_point, thousands_sep) {
              number = (number + '')
                .replace(/[^0-9+\-Ee.]/g, '');
              var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                  var k = Math.pow(10, prec);
                  return '' + (Math.round(n * k) / k)
                    .toFixed(prec);
                };
              // Fix for IE parseFloat(0.55).toFixed(0) = 0;
              s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                .split('.');
              if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
              }
              if ((s[1] || '')
                .length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1)
                  .join('0');
              }
              return s.join(dec);
            },
            
            plotHover = function(event, pos, item) {
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;                        
                        var
                            pointData = item.series.originalData[item.dataIndex],
                            dateStr = data.opt.tooltipDate.call(this, pointData);
                            
                        var tooltipText = 'Заказов '+dateStr+': <strong>'+pointData.count+'</strong> <br\> На сумму: <strong>'+number_format(pointData.total_cost,2,',',' ')+' '+data.opt.currency+'</strong>';
                        showTooltip(item.pageX, item.pageY, tooltipText);
                    }
                }
                else {
                    $("#flottooltip").remove();
                    previousPoint = null;            
                }
            };
            
            if ( methods[method] ) {
                methods[ method ].apply( this, Array.prototype.slice.call( args, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, args );
            }
        });
    }
    
})(jQuery);