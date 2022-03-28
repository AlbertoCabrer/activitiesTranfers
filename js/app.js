$(document).ready(function(){

    const f = new Date();

    let now = f.getFullYear()+'-'+  (f.getMonth() +1)+'-'+f.getDate() ;

    $('.datepicker').datepicker({ 
        format: 'yyyy-mm-dd',
        orientation: "left bottom",
        startDate: now,
        autoclose: true,
        todayHighlight: true
    }).val();


    $('.category').change(function(){
        let codSeg =[];        
        $(".category:checked").each(function() {
            codSeg.push($(this).val());
        });

        activities(codSeg);
        
    });
    
    

    
    const later = new tempusDominus.DateTime();

    

    new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'),{
        hooks: {
            inputFormat: (context, date) =>  { return date.toISOString('YYYY-MM-DD') }
            
        },
        restrictions: {
          minDate:  later
        },
        display:{
            components:{
                useTwentyfourHour:true
            }
        }
        
    });
    new tempusDominus.TempusDominus(document.getElementById('datetimepicker2'),{
        hooks: {
            inputFormat: (context, date) =>  { return date.toISOString('YYYY-MM-DD') }
            
        },
        restrictions: {
          minDate:  later
        },
        display:{
            components:{
                useTwentyfourHour:true
            }
        }
        
    });

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


function activities( activities ){
    if(activities.length <= 0){
        activities = 'all';
    }

    let parametros = {'activities': activities,
                      'hotel': $('#hotelsFrom').val(),
                      'from': $('input[name="from"]').val(),
                      'to': $('input[name="to"]').val(),
                      'language':$('select[name="language"]').val(),
                    };

    console.log(parametros);
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

function updateHTML(activity){
    
    let location='';
    let texhtml ='';
    if(activity.length > 0) {
        $.each(activity, function(i,item){
            if(activity[i].content.location.startingPoints[0].meetingPoint.city !== undefined ){
                location = activity[i].content.location.startingPoints[0].meetingPoint.city +', '+ activity[i].content.location.startingPoints[0].meetingPoint.country.name;
            }
            else{
                location = 'Varios destinos';
            }

            texhtml += '<div class="col col-md-6 col-lg-4">'+
                            '<div class="card shadow mb-3">'+
                                '<img src="'+ activity[i].content.media.images[0].urls[4].resource+'" class="card-img-top" alt="...">'+
                                '<div class="card-body" style="height: 10rem;">'+
                                    '<h5 class="card-title">'+ activity[i].name +'</h5>'+
                                    '<p class="card-text text-muted">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">'+
                                    '<path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"></path>'+
                                    '<path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>'+
                                    '</svg>'+ location +'</p>'+
                                    '<a href="activityDetails.php?code='+ activity[i].code +'&lang=es&amp;from=2022-03-27&amp;to=2022-03-28" class="stretched-link"></a>'+
                                '</div>'+
                                '<div class="text-end p-2">'+
                                    '<h5 class="text-danger fw-bold">'+activity[i].amountsFrom[0].amount +' '+ activity[i].currency+'</h5>'+
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
