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

