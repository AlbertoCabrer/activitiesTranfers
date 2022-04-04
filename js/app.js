$(document).ready(function(){

    const f = new Date();

    let now = f.getFullYear()+'-'+  (f.getMonth() +1)+'-'+f.getDate() ;

    let tomorrow = new Date(now);   
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow = tomorrow.toISOString().substring(0, 10);
    console.log(tomorrow);

    let limit = new Date(now);   
    limit.setDate(limit.getDate() + 365);
    limit = limit.toISOString().substring(0, 10);
    console.log(limit);

    localStorage.setItem('now', now);
    localStorage.setItem('tomorrow', tomorrow);
    

    if(localStorage.from == undefined && localStorage.to == undefined){
        localStorage.setItem('from', now);
        localStorage.setItem('to', limit);
    }

    
    console.log(localStorage);

    if(localStorage.dateAvailability == undefined){
        localStorage.setItem('dateAvailability', '');
    }
    else{
        $('#'+localStorage.dateAvailability).css({
            'color': '#fff',
            'background-color': '#0d6efd',
            'box-shadow': '0 0 0 .25rem rgba(255,255,255,.25)'
        });
        if(localStorage.dateAvailability == 'btnDate'){
            $('#rangeDate').removeClass('d-none');
        }
    }
    
    //console.log(tomorrow);
  
    $('.datepicker').datepicker({ 
        format: 'yyyy-mm-dd',
        orientation: "left bottom",
        startDate: now,
        autoclose: true,
        todayHighlight: true
    }).val();


    $('.category').change(function(){
        // let codSeg =[];        
        // $(".category:checked").each(function() {
        //     codSeg.push($(this).val());
        // });
        //localStorage.setItem('codCategoria', codSeg );

        filters();
        // post_activities(codSeg);
        
    });

    //bottons Availability
    $('#btnToday, #btnTomorrow, #btnDate').click(function(){
        //console.log(tomorrow);
        updateCssBtnAvailability();
        if(localStorage.dateAvailability != $(this).attr('id')){
            $(this).css({
                'color': '#fff',
                'background-color': '#0d6efd',
                'box-shadow': '0 0 0 .25rem rgba(255,255,255,.25)'
            });
            //localStorage.removeItem('dateAvailability');
            localStorage.setItem('dateAvailability', $(this).attr('id'));

            if($(this).attr('id') == 'btnDate'){
                $('#rangeDate').removeClass('d-none');
            }
            else{
                $('#rangeDate').addClass('d-none');
                switch ($(this).attr('id')){
                    case 'btnToday':
                        localStorage.setItem('from', now);
                        localStorage.setItem('to', now);
                    break;    
                    case 'btnTomorrow':
                        localStorage.setItem('from', tomorrow);
                        localStorage.setItem('to', tomorrow);
                    break;
                };
                filters();
            }
        }
        else{
            localStorage.setItem('dateAvailability', '');
            localStorage.setItem('from', now);
            localStorage.setItem('to', limit);
            filters();
        }
        

        
    });

    $('.inputTo').change(function(){
        localStorage.setItem('from', $('.inputFrom').val());
        localStorage.setItem('to', $(this).val());
        filters();
    });

    //input date range Availability
    $('.input-daterange input').each(function() {
        $(this).datepicker({
            format: 'yyyy-mm-dd',
            orientation: "left bottom",
            startDate: now,
            autoclose: true,
            todayHighlight: true
        });
       
    });

    $('.inputFrom').datepicker().on('changeDate' , function(e) {
        let endDate= toDisplay();
        $('.inputTo').val(endDate);
        $('.inputTo').datepicker('setStartDate', $('.inputFrom').val());
        $('.inputTo').datepicker('setEndDate', endDate);
    });

    

    

    // $('#btnTomorrow').click(function(){
    //     updateCssBtnAvailability();
    //     $(this).css({
    //         'color': '#fff',
    //         'background-color': '#0d6efd'
    //     });
    //     alert('Tomorrow');
    // });
    // $('#btnDate').click(function(){
    //     updateCssBtnAvailability();
    //     $(this).css({
    //         'color': '#fff',
    //         'background-color': '#0d6efd'
    //     });
    //     alert('today');
    // });
    
    

    
    // const later = new tempusDominus.DateTime();

    

    // new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'),{
    //     hooks: {
    //         inputFormat: (context, date) =>  { return date.toISOString('YYYY-MM-DD') }
            
    //     },
    //     restrictions: {
    //       minDate:  later
    //     },
    //     display:{
    //         components:{
    //             useTwentyfourHour:true
    //         }
    //     }
        
    // });
    // new tempusDominus.TempusDominus(document.getElementById('datetimepicker2'),{
    //     hooks: {
    //         inputFormat: (context, date) =>  { return date.toISOString('YYYY-MM-DD') }
            
    //     },
    //     restrictions: {
    //       minDate:  later
    //     },
    //     display:{
    //         components:{
    //             useTwentyfourHour:true
    //         }
    //     }
        
    // });

    // Vista de la pagina Transfers select From
    $('#typeFrom').change(function(){
        if($(this).val()=='IATA'){
            $('#hotelsFrom').addClass('d-none');
            $('#portFrom').addClass('d-none');
            $('#terminalFrom').removeClass('d-none');

            $("#hotelsFrom").removeAttr('name');
            $("#portFrom").removeAttr('name');
            $("#terminalFrom").attr('name','from');            
        }
        if($(this).val()=='ATLAS'){
            $('#terminalFrom').addClass('d-none');
            $('#portFrom').addClass('d-none');
            $('#hotelsFrom').removeClass('d-none');

            $("#terminalFrom").removeAttr('name');
            $("#portFrom").removeAttr('name');
            $("#hotelsFrom").attr('name','from');            
        }
        if($(this).val()=='PORT'){
            $('#terminalFrom').addClass('d-none');
            $('#hotelsFrom').addClass('d-none');
            $('#portFrom').removeClass('d-none');

            $("#terminalFrom").removeAttr('name');
            $("#hotelsFrom").removeAttr('name');
            $("#portFrom").attr('name','from');            
        }
    });
    // Vista de la pagina Transfers select To
    $('#typeTo').change(function(){
        if($(this).val()=='IATA'){
            $('#hotelsTo').addClass('d-none');
            $('#portTo').addClass('d-none');
            $('#terminalTo').removeClass('d-none');

            $("#hotelsTo").removeAttr('name');
            $("#portTo").removeAttr('name');
            $("#terminalTo").attr('name','to');  
        }
        if($(this).val()=='ATLAS'){
            $('#terminalTo').addClass('d-none');
            $('#portTo').addClass('d-none');
            $('#hotelsTo').removeClass('d-none');

            $("#terminalTo").removeAttr('name');
            $("#portTo").removeAttr('name');
            $("#hotelsTo").attr('name','to'); 
        }
        if($(this).val()=='PORT'){
            $('#terminalTo').addClass('d-none');
            $('#hotelsTo').addClass('d-none');
            $('#portTo').removeClass('d-none');

            $("#terminalTo").removeAttr('name');
            $("#hotelsTo").removeAttr('name');
            $("#portTo").attr('name','to');            
        }
    });
    // Vista de la pagina Transfers radio buttons
    $('#flexRadioDefault1').click(function(){
        if($(this).val() == 'on'){
            $('#dateFrom').removeClass('d-none');
            $('#dateTo').addClass('d-none');
        }
    });
    $('#flexRadioDefault2').click(function(){
        if($(this).val() == 'on'){
            $('#dateFrom,#dateTo').removeClass('d-none');
        }
    });
})


function updateCssBtnAvailability(){
    $('#btnToday, #btnTomorrow, #btnDate').css({
        'color': '#0d6efd',
        'background-color': '#fff'
    });
    $('#rangeDate').addClass('d-none');    
}

function toDisplay() {
    var d = new Date($('.inputFrom').val());   
    d.setDate(d.getDate() + 15);
    return d.toISOString().substring(0, 10);
}

//ajax para obtener las actividades segun las categorias seleccionadas
function post_activities( parametros ){
    //console.log(parametros);
    $.ajax({
        type: "POST",
        url: 'php/activities.php',
        data: parametros,
        success: function(response)
        {
            let result = JSON.parse(response)
           console.log(result.activities);
            updateHTML(result.activities);
        },
        error: function () {
            alert('Error peticion Ajax activities');
           
        }
   });
     
}

function filters(){
    let codSeg =[]; 
    

    console.log(localStorage);

    $(".category:checked").each(function() {
        codSeg.push($(this).val());
    });
    if(codSeg.length <= 0){
        codSeg = 'all';
    }
       
    let parametros = {'activities': codSeg,
        'hotel': $('#hotelsFrom').val(),
        'from': localStorage.from,
        'to': localStorage.to,
        'language':$('select[name="language"]').val(),
    };
    console.log(parametros);

    post_activities(parametros);
}

//formar el html segun los datos de la function post_activities
function updateHTML(activity){   
    let texhtml ='';
    
    if(activity.length > 0) {
        $.each(activity, function(i,item){
            let freeCancellation ='<div class="align-self-end text-success">Free cancellation</div>';
            let location = 'Multiple destinations';
            
            if($('select[name="language"]').val() == 'es'){
                location = 'Varios destinos';
                freeCancellation = '<div class="align-self-end text-success">Cancelación gratuita</div>';
            }  
           
            if(activity[i].content.location.startingPoints[0].meetingPoint.city !== undefined ){
                location = activity[i].content.location.startingPoints[0].meetingPoint.city +', '+ activity[i].content.location.startingPoints[0].meetingPoint.country.name;
            }

            if(!activity[i].modalities[0].freeCancellation){
                freeCancellation = '';
            }

            texhtml += '<div class="d-flex col col-md-6 col-lg-4">'+
                            '<div class="align-self-stretch card shadow mb-3">'+
                                '<img src="'+ activity[i].content.media.images[0].urls[4].resource+'" class="card-img-top" alt="...">'+
                                '<div class="card-body">'+
                                    '<h5 class="card-title">'+ activity[i].name +'</h5>'+
                                    '<p class="card-text text-muted">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">'+
                                    '<path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"></path>'+
                                    '<path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>'+
                                    '</svg>'+ location +'</p>'+
                                    '<a href="activityDetails.php?code='+ activity[i].code +'&lang='+ $('select[name="language"]').val() +'&amp;from='+ $('input[name="from"]').val() +'&amp;to='+ $('input[name="to"]').val() +'" class="stretched-link"></a>'+
                                '</div>'+
                                '<div class="d-flex flex-column-reverse text-end p-2" style="height: 5rem;">'+
                                    freeCancellation +
                                    '<div class="align-self-end">'+
                                        '<h5 class="text-danger fw-bold"><em>'+activity[i].amountsFrom[0].amount +' '+ activity[i].currency+'</em></h5>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

        });
    }
    else{
        texhtml += '<div class="alert alert-warning" role="alert">'+
                        'Revice los parametros insertados'+
                    '</div>'; 
    }    
    $('#cardActivity').empty();
    $('#cardActivity').append(texhtml);
}
