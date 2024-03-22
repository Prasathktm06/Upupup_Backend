//*******  Developed By Nithin, Jain, Mohamed Muhsin and Atheeth **********/

// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 1,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
    }, opts );
 
    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }
         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    json.data.splice( requestLength, json.data.length );
                     
                    drawCallback( json );
                },
                
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};
 
// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );


$(document).ready(function() {
    
   /* BANNER DATATABLE */
   //var base_url="http://app.appzoc.com/upupup/";
   var venue_id=$('#venue_id').val();
  // console.log(venue_id);
   var user=$('#user_id').val();
   if(venue_id==null)
      venue_id='';
   $('#datatable-locations').dataTable( {  
        "processing": true,
         bJQueryUI: true,

        "stateSave": true,
        oLanguage: {
        sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
        },
        "serverSide": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: base_url+"places/locationTable",
            pages: 1 // number of pages to cache
        } ),
       
        "bDestroy": true,
        "iDisplayLength": 15,
        "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
        "columns": [
           { "data": "serial_number",'sWidth': '5%',sortable: false},
           { "data": "location",'sWidth': '15%',sortable: true},
             {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'places/change_status_area/'+full.id+'/'+full.status+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'places/change_status_area/'+full.id+'/'+full.status+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            },
           {
                 data: "null",
                 'sWidth': '15%',
                 sortable: false,
                 render: function (data, type, full) { 
                    u = '';
                    if(full.perm_edit==1){
                        u+='<a href="'+base_url+'places/location_edit/'+full.id+'" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>';
                    }
                    if(full.perm_delete==1){
                        u+='<a href="'+base_url+'places/location_delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                    }
                    return u;
                }
                    
             }
        ]
    } );
    
   $('#datatable-area').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"places/areaTable",
           pages: 1 // number of pages to cache
       } ),
      
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "area",'sWidth': '15%',sortable: true},
           { "data": "location",'sWidth': '15%',sortable: true},
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'places/change_status/'+full.id+'/'+full.status+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'places/change_status/'+full.id+'/'+full.status+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            },
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                       u+='<a href="'+base_url+'places/area_edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'places/area_delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   $('#datatable-venue').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"venue/venueTable",
           pages: 1 // number of pages to cache
       } ),
      
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          
      /*     {
                 data: "image",
                'sWidth': '15%',
                sortable: false,
                render: function (image, type, full) { 
                   u = '';
                  
                    u='<img class="img-circle" width="100" height="100" src="'+image+'" ></img>';
                   
                  
                   return u;
               }
                   
            },*/

            { "data": "venue",'sWidth': '15%',sortable: true},
         
          { "data": "location",'sWidth': '15%',sortable: true},
          { "data": "area",'sWidth': '15%',sortable: true},
           { "data": "phone",'sWidth': '15%',sortable: true},
             {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'venue/change_status/'+full.id+'/'+full.status+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'venue/change_status/'+full.id+'/'+full.status+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            },
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                       u+='<a href="'+base_url+'venue/venue_edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'venue/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   $('#datatable-sports').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"sports/sportsTable",
           pages: 1 // number of pages to cache
       } ),
      
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
         
          {
                 data: "image",
                'sWidth': '15%',
                sortable: false,
                render: function (image, type, full) { 
                   u = '';
                  
                    u='<img class="" width="100" height="100" src="'+image+'" ></img>';
                   
                  
                   return u;
               }
                   
            },
             { "data": "sports",'sWidth': '15%',sortable: true},
             {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'sports/change_status/'+full.id+'/'+full.status+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'sports/change_status/'+full.id+'/'+full.status+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            },
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                       u+='<a href="'+base_url+'sports/edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'sports/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );



$('#datatable-inactive').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"inactivate/inactivateTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 10,
       "aLengthMenu": [[10, 25, 50, 100], [ 10, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "court",'sWidth': '15%',sortable: true},      
          { "data": "sdate",'sWidth': '15%',sortable: true,
        type: "datetime", format: 'MM\/DD\/YYYY '},  
          { "data": "edate",'sWidth': '15%',sortable: true}, 
          { "data": "stime",'sWidth': '15%',sortable: true},
          { "data": "etime",'sWidth': '15%',sortable: true},  
          { "data": "description",'sWidth': '15%',sortable: true},  
     
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'inactivate/inactivate_edit/'+full.id+'/'+venue_id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'inactivate/inactivate_delete/'+full.id+'/'+venue_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   $('#datatable-speciality').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"venue/specialityTable",
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "facility",'sWidth': '15%',sortable: true},         
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                       u+='<a href="'+base_url+'venue/speciality_edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'venue/speciality_delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   $('#datatable-matches').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"matches/matchesTable",
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "match_name",'sWidth': '15%',sortable: true},      
          { "data": "name",'sWidth': '15%',sortable: true},      
          { "data": "sports",'sWidth': '15%',sortable: true},      
          { "data": "area",'sWidth': '15%',sortable: true},  
          { "data": "date",'sWidth': '15%',sortable: true}, 
          { "data": "time",'sWidth': '15%',sortable: true},  
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                       u+='<a  href="'+base_url+'matches/edit/'+full.id+'" class="btn btn-small matches-edit" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'matches/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   
   $('#datatable-offer').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>",
      
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"offer/offerTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "offer",'sWidth': '15%',sortable: true},
          { "data": "start",'sWidth': '15%',sortable: true},
          { "data": "end",'sWidth': '15%',sortable: true},
          { "data": "start_time",'sWidth': '15%',sortable: true},
          { "data": "end_time",'sWidth': '15%',sortable: true}, 
          {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'offer/change_status/'+full.id+'/'+full.status+'/'+venue_id+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'offer/change_status/'+full.id+'/'+full.status+'/'+venue_id+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            },     
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'offer/edit/'+full.id+'/'+venue_id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'offer/delete/'+full.id+'/'+venue_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   
   $('#datatable-users').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"users/usersTable",
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          
            
          {
                 data: "image",
                'sWidth': '15%',
                sortable: false,
                render: function (image, type, full) { 
                   u = '';
                  
                    
                    if (image!="") {
                       u='<img width="100" height="100" class="img-circle" src="'+image+'" ></img>';
                    } else {
                      u='<img src="'+base_url+'assets/img/user_default.png" class="img-circle" width="100px" height="100px">';
                    }
                  
                   return u;
               }
                   
            },
            { "data": "name",'sWidth': '15%',sortable: true},      
          { "data": "phone_no",'sWidth': '15%',sortable: true},  
        {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   /*if(full.perm_edit==1){
                    u+='<a href="'+base_url+'users/edit/'+full.id+'" class="btn btn-small " title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }*/
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'users/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   u+='<a href="'+base_url+'users/profile/'+full.id+'" class=" btn-small " title="Profile"><i class="fa fa-eye"></i> </a>';
                   return u;
               }
                   
            }
       ]
   } );
    $('#datatable-court').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"court/courtTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "court",'sWidth': '15%',sortable: true},      
          { "data": "sports",'sWidth': '15%',sortable: true},  
            {
                data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.status==1){
                       u+='<a href="'+base_url+'court/change_status/'+full.id+'/'+full.status+'/'+venue_id+'" class="btn btn-success" >Active </a>';
                   }else
                   if(full.status==0){
                       u+='<a href="'+base_url+'court/change_status/'+full.id+'/'+full.status+'/'+venue_id+'" class="btn btn-danger" >Inactive</a>';
                   }
                   return u;
               }
                   
            }, 
     
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'court/edit/'+full.id+'/'+venue_id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'court/delete/'+full.id+'/'+venue_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
   $('body').on("change",".users-coPlayers",function(e){
     $('#coPlayerTable').show();
   var user=$('#user_id').val();
   var sports=$('#users-sports').val();
   $('#datatable-coplayers').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"users/coplayers/"+user+"/"+sports,
           
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "name",'sWidth': '15%',sortable: true},      
          { "data": "rating",'sWidth': '15%',sortable: true},      
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   
                    u+='<a data-custom-value="'+full.id+'" class="btn btn-small edit_rating" title="Edit" data-toggle="modal" data-target="#myModal2"><i class="fa fa-pencil"></i> </a>';
                   
                 
                       u+='<a href="'+base_url+'users/delete_coplayers/'+full.id+'/'+full.sports_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                  
                   return u;
               }
                   
            }
       ]
   } );
   });



$('body').on("click","#matches-players",function(e){
  var match=$(this).data('custom-value');
   $('#datatable-match-players').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"matches/players/"+match,
           
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "name",'sWidth': '15%',sortable: true},      
          { "data": "status",'sWidth': '15%',sortable: true},      
         /*{
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   
                    u+='<a data-custom-value="'+full.id+'" class="btn btn-small edit_status" title="Edit" data-toggle="modal" data-target="#myModal2"><i class="fa fa-pencil"></i> </a>';
                   
                 
                       u+='<a href="'+base_url+'matches/matches_players_delete/'+full.id+'/" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                  
                   return u;
               }
                   
            }*/
       ]
   } );
  });
    $('#datatable-booking').dataTable( {  
       "processing": true,
        bJQueryUI: true,
        "scrollX" : true,
       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"booking/bookingTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "booking_id",'sWidth': '15%',sortable: true},      
          { "data": "venue",'sWidth': '15%',sortable: true},      
          { "data": "venue_phone",'sWidth': '15%',sortable: true},  
          { "data": "court",'sWidth': '15%',sortable: true},  
          { "data": "capacity",'sWidth': '15%',sortable: true},  
          { "data": "name",'sWidth': '15%',sortable: true},  
          { "data": "phone_no",'sWidth': '15%',sortable: true},  
          { "data": "booking_date",'sWidth': '15%',sortable: true}, 
          { "data": "cost",'sWidth': '15%',sortable: true}, 
          { "data": "coupon_value",'sWidth': '15%',sortable: true},  
          { "data": "offer_percentage",'sWidth': '15%',sortable: true},  
          { "data": "amount",'sWidth': '15%',sortable: true},  
          { "data": "payment_mode",'sWidth': '15%',sortable: true},  
          { "data": "price",'sWidth': '15%',sortable: true},  
          { "data": "bal",'sWidth': '15%',sortable: true},  
          { "data": "payment_id",'sWidth': '15%',sortable: true}, 
          { "data": "time",'sWidth': '15%',sortable: true},
            
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'court/edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'booking/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );

      $('#datatable-vendorbooking').dataTable( {  
       "processing": true,
        bJQueryUI: true,
        "scrollX" : true,
       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"booking/vendorbookingTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "booking_id",'sWidth': '15%',sortable: true},      
          { "data": "venue",'sWidth': '15%',sortable: true},      
          { "data": "venue_phone",'sWidth': '15%',sortable: true},  
          { "data": "court",'sWidth': '15%',sortable: true},  
          { "data": "capacity",'sWidth': '15%',sortable: true},  
          { "data": "name",'sWidth': '15%',sortable: true},  
          { "data": "phone_no",'sWidth': '15%',sortable: true},  
          { "data": "booking_date",'sWidth': '15%',sortable: true}, 
          { "data": "cost",'sWidth': '15%',sortable: true},  
          { "data": "offer_value",'sWidth': '15%',sortable: true},  
          { "data": "payment_mode",'sWidth': '15%',sortable: true},  
          { "data": "cost",'sWidth': '15%',sortable: true},    
          { "data": "payment_id",'sWidth': '15%',sortable: true},
          { "data": "role_name",'sWidth': '15%',sortable: true},  
          { "data": "time",'sWidth': '15%',sortable: true},
           
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'court/edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'booking/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );

      $('#datatable-cancelbooking').dataTable( {  
       "processing": true,
        bJQueryUI: true,
        "scrollX" : true,
       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"booking/cancelbookingTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "booking_id",'sWidth': '15%',sortable: true},      
          { "data": "venue",'sWidth': '15%',sortable: true},      
          { "data": "venue_phone",'sWidth': '15%',sortable: true},  
          { "data": "court",'sWidth': '15%',sortable: true},  
          { "data": "capacity",'sWidth': '15%',sortable: true},  
          { "data": "name",'sWidth': '15%',sortable: true},  
          { "data": "phone_no",'sWidth': '15%',sortable: true},  
          { "data": "booking_date",'sWidth': '15%',sortable: true}, 
          { "data": "cost",'sWidth': '15%',sortable: true},  
          { "data": "offer_value",'sWidth': '15%',sortable: true},  
          { "data": "payment_mode",'sWidth': '15%',sortable: true},  
          { "data": "cost",'sWidth': '15%',sortable: true},    
          { "data": "payment_id",'sWidth': '15%',sortable: true},
          { "data": "role_name",'sWidth': '15%',sortable: true},  
          { "data": "time",'sWidth': '15%',sortable: true},
           
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'court/edit/'+full.id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'booking/delete/'+full.id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );

    $('#datatable-venue-users').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"venue/venueUsersTable/"+venue_id,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "name",'sWidth': '15%',sortable: true},      
          { "data": "email",'sWidth': '15%',sortable: true},  
          { "data": "role",'sWidth': '15%',sortable: true},  
     
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'acl/user/edit/'+full.id+'/'+venue_id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'acl/user/delete_venue_users/'+full.id+'/'+venue_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
        $('#datatable-court-manager').dataTable( {  
       "processing": true,
        bJQueryUI: true,

       "stateSave": true,
       oLanguage: {
       sProcessing: "<img src='"+base_url+"assets/img/balls.svg'>"
       },
       "serverSide": true,
       "ajax": $.fn.dataTable.pipeline( {
           url: base_url+"court/get_courtTable_manager/"+user,
           pages: 1 // number of pages to cache
       } ),
       
       "bDestroy": true,
       "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]],
       "columns": [
          { "data": "serial_number",'sWidth': '5%',sortable: false},
          { "data": "court",'sWidth': '15%',sortable: true},      
          { "data": "sports",'sWidth': '15%',sortable: true},  
          { "data": "venue",'sWidth': '15%',sortable: true},  
        
         {
                 data: "null",
                'sWidth': '15%',
                sortable: false,
                render: function (data, type, full) { 
                   u = '';
                   if(full.perm_edit==1){
                    u+='<a href="'+base_url+'court/edit/'+full.id+'/'+full.venue_id+'" class="btn btn-small" title="Edit"><i class="fa fa-pencil"></i> </a>';
                   }
                   if(full.perm_delete==1){
                       u+='<a href="'+base_url+'court/edit/'+full.id+'/'+full.venue_id+'" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>';
                   }
                   return u;
               }
                   
            }
       ]
   } );
} ); //end

 
