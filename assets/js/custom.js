
$(document).ready(function() {
var location;
$('#acl_user').DataTable();
$('#acl_perm').DataTable();
$('#acl_role').DataTable();
$('#coPlayerTable').hide();

$('#delete_perm_name').attr("href",base_url+"acl/perm/delete_perm_name/"+$('#perm_name option:selected').val());
	$( "#location" ).change(function() {
		 location=$('#location option:selected').val();
		 document.getElementById("area").options.length = 0;

		$.ajax({

			url: base_url+"Ajax/area/"+location,
			 beforeSend: function(){
			    // alert('asdsa')
			  },
			success: function(result){

	        var area=JSON.parse(result);

	        area.forEach(function(val) {
	        	  $('#area').append($('<option>', {
	  	            value: val.id,
	  	            text: val.area
	  	        }));
	        });

	    }
		});
	});
	 $('body').on( "click", '.delete',function(e) {
	        e.preventDefault();
	        $( ".right_col" ).fadeTo( 3000,0.5, function() {});
	        var href = $(this).attr('href');
	        (new PNotify({
	     	    title: 'Confirmation Needed',
	     	    text: 'Are you sure?',
	     	    icon: 'glyphicon glyphicon-question-sign',

	     	    hide: false,
	     	    confirm: {
	     	        confirm: true
	     	    },
	     	    buttons: {
	     	        closer: false,
	     	        sticker: false
	     	    },
	     	    history: {
	     	        history: false
	     	    }
	     	})).get().on('pnotify.confirm', function() {
	     		window.location.href = href;
	            }).on('pnotify.cancel', function() {
	               $( ".right_col" ).fadeTo( 1000,1, function() {});
	     	});

	    });

	 $('body').on("change","#perm_name",function(e){

		$('#delete_perm_name').attr("href",base_url+"acl/perm/delete_perm_name/"+this.value);

		});

	 $('body').on("click",".edit_rating",function(e){

		 $("#co").val($(this).data("custom-value"));
		 $('#co_sports').val($('#users-sports').val());
	 });
	 $('body').on("click",".edit_status",function(e){


		 $("#match-players-id").val($(this).data("custom-value"));

	 });

	 $('#tab2').on('click',function(){
	 	var msg =[];
      if($('#name').val()=="")
      {
      		msg+="Match Field Required<br>";
      }
     if($('#datepicker').val()==""){
     		msg+="Date Field Required<br>";
      	}
      	 if($('#area').val()==""){
     		 msg+="Area Field Required<br>";
      	}
      	if($('#no_players').val()==""){
     		 msg+="Number of players Field Required";
      	}

      	if($('#name').val()=="" || $('#datepicker').val()=="" || $('#area').val()==""){
       $('#tab2').attr('href','');
       new PNotify({
            title: 'Field Missing',
            text: msg,
            type: 'error',
            delay : 2000
        });
      }else{
      	$('#tab2').attr('href','#tab_2');
      }

    });
 	 $('#add-user-tab_2').on('click',function(){
	 	var msg =[];
//console.log($('#user-add-sports').val());
      if($('#user').val()=="")
      {
      		msg+="Match Field Required<br>";
      }
     if($('#user-add-phone').val()==""){
     		msg+="Phone Field Required<br>";
      	}
      	 if($('#user-add-sports').val()==null){
     		 msg+="Sports Field Required<br>";
      	}
      	if($('#area').val()==null){
     		 msg+="Area Field Required";
      	}

      	if($('#user').val()=="" || $('#user-add-phone').val()=="" || $('#user-add-sports').val()=="" ||$('#area').val()==null){
       $('#add-user-tab_2').attr('href','');
       new PNotify({
            title: 'Field Missing',
            text: msg,
            type: 'error',
            delay : 2000
        });
      }else{
      	$('#add-user-tab_2').attr('href','#tab_2');
      }

    });
 	  $('#add-venue-tab_2').on('click',function(){
	 	var msg =[];
//console.log($('#user-add-sports').val());
      if($('#venue').val()=="")
      {
      		msg+="Venue Field Required<br>";
      }
     if($('#area').val()==null){
     		msg+="Area Field Required<br>";
      	}
				if($('#morn').val()==''){
					 msg+="Morning Field Required<br>";
					 }
					 if($('#even').val()==''){
	 					 msg+="Evening Field Required<br>";
	 					 }

						 console.log($('#even').val());

      	if($('#venue').val()=="" || $('#area').val()==null || $('#morn').val()=='' || $('#even').val()==''  ){
       $('#add-user-tab_2').attr('href','');
       new PNotify({
            title: 'Field Missing',
            text: msg,
            type: 'error',
            delay : 2000
        });
      }else{
      	$('#add-venue-tab_2').attr('href','#tab_2');
      }

    });
    
    
    $('#add-trainer-tab_2').on('click',function(){
    var msg =[];
//console.log($('#user-add-sports').val());
      if($('#trainer').val()=="")
      {
          msg+="Trainer Name Field Required<br>";
      }
        if($('#phone').val()==""){
           msg+="Phone Number Field Required<br>";
           }
     if($('#area').val()==null){
     		msg+="Area Field Required<br>";
      	}
        if($('#trainer').val()==null || $('#area').val()==null || $('#phone').val()==null){
       new PNotify({
            title: 'Field Missing',
            text: msg,
            type: 'error',
            delay : 2000
        });
      }else{
        $('#add-trainer-tab_2').attr('href','#tab_2');
      }

    });
/////////////////////////////////////////////////////////////////////////////////

    $('#add-shop-tab_2').on('click',function(){
    var msg =[];
//console.log($('#user-add-sports').val());
      if($('#name').val()=="")
      {
          msg+="Shop Name Field Required<br>";
      }
      if($('#phone').val()=="")
      {
          msg+="Phone Number Field Required<br>";
      }
     if($('#area').val()==null)
     {
         msg+="Area Field Required<br>";
     }
        if($('#name').val()=="" || $('#area').val()==null || $('#phone').val()==""){
           new PNotify({
                title: 'Field Missing',
                text: msg,
                type: 'error',
                delay : 2000
            });
      }else{
        $('#add-shop-tab_2').attr('href','#tab_2');
      }

    });
//////////////////////////////////////////////////////////////////////////////////////

	  $('#users-sports').on('change',function(){
	  	$('#users-edit-sports_id').val($(this).val());
	  });

    $('form').submit(function() {

     $('#myModal').modal('show');
});


});//ready
