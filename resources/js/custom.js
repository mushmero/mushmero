$(document).ready(function() {

    $.ajax({
        url: "/auth/check",
        async: false,
        datatype: 'json',
        success: function(data){
            if(data == 1){
                loadmap();
                disabledLink();
                select2JSColour();          
                insertOptionOnTextEditor('#design', '#fields');

                experiences();
                socials();
            }
        }
    });
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
});

function select2JSColour()
{
    /* select2 js */
    $('#primary_colour, #secondary_colour').select2({
      templateResult: function (data, container) {
          if (data.element) {
            $(container).addClass($(data.element).attr("class"));
          }
          return data.text;
        },
        placeholder: "Select color",
    });

}

function insertOptionOnTextEditor(textEditor, selectfield)
{
    if(textEditor == '') return;
    if(selectfield == '') return;

    $(selectfield).change(function(){
        var optionValue = $(selectfield).val();
        var textEditorContainer = $(textEditor);
        if(optionValue != '' || optionValue != '-'){
            textEditorContainer.summernote('editor.saveRange');
            textEditorContainer.summernote('editor.restoreRange');
            textEditorContainer.summernote('editor.focus');
            textEditorContainer.summernote('editor.insertText', optionValue);
        }
    });
}

function disabledLink()
{
    $('*').each(function() {
        if ($(this).hasClass('disabled')) {
            $(this).on('click', function(e){
                e.preventDefault();
            });
        }
    });
}

function experiences()
{
    $('#startDate').on('blur', function(){
        $('#startDate').datetimepicker('hide');
    });
    $('#endDate').on('blur', function(){
        $('#endDate').datetimepicker('hide');
    });

    $('#current').on('change', function(e){
        if($('#current:checked').length > 0){
            $('#endDate').parent().hide();
        }else{
            $('#endDate').parent().show();
        }
    });
    
    if($('#current:checked').length > 0){
        $('#endDate').parent().hide();
    }else{
        $('#endDate').parent().show();
    }
}

function socials()
{
    function formatIcon (icon) {
        return $('<span><i class="fa-fw ' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
    };
    
    $('.icon_select').select2({
        placeholder : "Select icon",
        allowClear : true,
        templateSelection: formatIcon,
        templateResult: formatIcon
    });
}

function loadmap(){

    var areas = {};  
    var plots = {};
    var plotdata;
    var info;
    var areaslices = {};
    var countries;
    var visitordata;

    $.ajax({
        url: "/getAllCountries",
        async: false,
        datatype: 'json',
        success: function(data){
            countries = JSON.parse(data);
        }
    });
    
    $.ajax({
        url: "/getVisitorByCountry",
        async: false,
        datatype: 'json',
        success: function(data){
            visitordata = JSON.parse(data);
        }
    });
    plotdata = JSON.parse(visitordata.data);
    info = JSON.parse(visitordata.info);
    $.each(plotdata, function(id,elem){
        var plot = {};
        plot.latitude = elem.latitude;
        plot.longitude = elem.longitude;
        plot.value = elem.visitor;
        plot.tooltip = {
            content: elem.country+"<br>Visitor: "+elem.visitor
        }
        plots[elem.countryCode] = plot;
    });

    var nullareaslice = {};
    var minareaslice = {};
    var betweenareaslice = {};
    var maxareaslice = {};
        nullareaslice.max = 0;
        nullareaslice.attrs = {fill: "rgba(0,0,0,0.5)"};
        nullareaslice.label = "0";

        minareaslice.mix = 1;
        minareaslice.max = 500;
        minareaslice.attrs = {fill: "#00E5FF"};
        minareaslice.label = "< 500";

        betweenareaslice.min = 500;
        betweenareaslice.max = 1500;
        betweenareaslice.attrs = {fill: "#FFC400"};
        betweenareaslice.label = "> 500 and < 1500";

        maxareaslice.min = 1500;
        maxareaslice.attrs = {fill: "#64DD17"};
        maxareaslice.label = "> 1500";

        areaslices = [nullareaslice, minareaslice, betweenareaslice, maxareaslice];

    var arealegend = {
        display: true,
        mode: "horizontal",
        title: "Visitors",
        labelAttrs: {
            "font-size": 10
        },
        marginLeft: 5,
        marginLeftLabel: 5,
        slices: areaslices,
    };
    var pdata = {};
    $.each(plotdata, function(key,data){
        var pd = {};
        pd.country = data.country;
        pd.value = data.visitor;
        pdata[data.countryCode] = pd;
    });
    $.each(countries, function(id, elem){
        if(id in pdata){
            var v = pdata[id].value;
            var c = pdata[id].country;
            areas[id] = {
                value: v,
                tooltip : {
                    content: c+"<br>Visitor: "+v,
                },
            }; 
        }else{
            areas[id] = {
                value: 0,
                tooltip : {
                    content: elem+"<br>Visitor: 0",
                },
            }
        }
    });

    $(".visitor_map").mapael({
        map: {
            // Set the name of the map to display
            name: "world_countries",
            zoom: {
                enabled: true,
                maxLevel: 10
            },
        },
        areas: areas,
        legend: {
            area : arealegend
        },
    });
}