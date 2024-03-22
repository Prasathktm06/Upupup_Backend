 
 /*......................................../*
         Get Region
 /*......................................../*
/**
  *@api{get}place/region  Get Region
  *@apiSampleRequest off
  *@apiName getRegion
  *@apiGroup Place
  *@apiVersion 0.1.0
  *@apiDescription  Get all Region.
  *

  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
       "id": "1",
      "location": "Kochi"
  *}
  *@apiErrorExample  Error-Response:
  *{
   "ErrorCode": 1,
     "Message": "Error"
  *}  
*/


 /*......................................../*
         Get area by location
 /*......................................../*
/**
  *@api{get}place/area/1  Get Area by Location
  *@apiSampleRequest off
  *@apiName getArea
  *@apiGroup Place
  *@apiVersion 0.1.0
  *@apiDescription  Get all area By location.
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
      "id": "1",
      "area": "Kaloor",
      "location_id": "1",
      "location": "Kochi"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

 /*......................................../*
         Get sports
 /*......................................../*
/**
  *@api{get}sports/ Get Sports 
  *@apiSampleRequest off
  *@apiName getSports
  *@apiGroup Sports
  *@apiVersion 0.1.0
  *@apiDescription  Get all sports.
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
       "id": "1",
      "sports": "Cricket",
      "image": "URL\r\n"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

 /*......................................../*
         Get hosted matches
 /*......................................../*
/**
  *@api{get}matches/1 Get Hosted Matches 
  *@apiSampleRequest off
  *@apiName getHostedMatches
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription  Get hosted matches.
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
        "id": "2",
      "match_name": "IPL",
      "image": "URL\r\n",
      "date": "2017-02-14",
      "time": "12:34:54",
      "postedBy": "Varun Gopal",
      "area": "Kaloor",
      "status": "Active"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/


 /*......................................../*
         Get host a match
 /*......................................../*
/**
  *@api{post}matches/1  Host A Match 
  *@apiSampleRequest off
  *@apiName hostMatch
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription  Host a match.
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} sports_id Sports ID
  *@apiParam {Number} area_id Area ID
  *@apiParam {String} match_name Name
  *@apiParam {Date} date Date
  *@apiParam {Time} time Time
  *@apiParam {Number} no_players Number of players
  *@apiParam {String} description Description
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
        "ErrorCode": 0,
         "Data": "",
        "Message": "Match Hosted!"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

/*......................................../*
         Get upcoming matches
 /*......................................../*
/**
  *@api{get}matches/upcoming_matches/2  Get Upcoming Matches 
  *@apiSampleRequest off
  *@apiName upcomingMatches
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription  Upcoming Matches.
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
      "match_name": "New Match",
      "id": "4",
      "image": "",
      "date": "2017-02-06",
      "time": "05:00:00",
      "status": "Coming"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

/*......................................../*
         Get past matches matches
 /*......................................../*
/**
  *@api{get}matches/past_booking/1  Get Past Matches 
  *@apiSampleRequest off
  *@apiName pastMatches
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription  Past Matches.
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
      "match_name": "name",
      "id": "3",
      "image": "",
      "date": "2017-02-14",
      "time": "12:34:54",
      "status": "Finished"
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

/*......................................../*
         Get Venue
 /*......................................../*
/**
  *@api{post}venue/1  Get Venue   
  *@apiSampleRequest off
  *@apiName getVenue
  *@apiGroup Venue
  *@apiVersion 0.1.0
  *@apiDescription  Get Venue by users .
  *
 
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      "venue_id": "2",
      "lat": "22.2",
      "lon": "12.2",
      "cost": "0",
      "description": "Good",
      "court": "2",
      "venue": "Venue",
      "image": "",
      "area": "Kaloor",
      "sports": "Cricket",
      "id": "1",
      "sports_image": "URL\r\n",
      "timing": {
        "morning": "12:34:54",
        "evening": "12:34:54"
      },
      "facilities": [
        {
          "facility": "Drinking Water"
        }
      ]
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Data Not Found!" 
  *}  
*/

/*......................................../*
         Rate Venue
 /*......................................../*
/**
  *@api{post}venue/rate  Rate A Venue   
  *@apiSampleRequest off
  *@apiName rateVenue
  *@apiGroup Venue
  *@apiVersion 0.1.0
  *@apiDescription  Rate a Venue  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} venue_id Venue ID
  *@apiParam {Number} rate Rate
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      "ErrorCode": 0,
      "Data": true,
      "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Get Court Time
 /*......................................../*
/**
  *@api{post}venue/court_time  Get Court Time 
  *@apiSampleRequest off
  *@apiName getVenue
  *@apiGroup Venue
  *@apiVersion 0.1.0
  *@apiDescription  Get court time by user  .
  *
  *@apiParam {Number} venue_id Venue ID
  *@apiParam {Number} court_id Court ID
  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
     "court": "court1",
      "venue": "Venue",
      "time": "12:34:54"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Get User Skills
 /*......................................../*
/**
  *@api{get}users/skill/1  Get Users Skills
  *@apiSampleRequest off
  *@apiName getuserSkills
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Get users skills   .
  *

  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      "rating": "6",
      "sports": "Cricket"
      
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Get Co-Players
 /*......................................../*
/**
  *@api{get}users/co_players/1  Get Co-Players
  *@apiSampleRequest off
  *@apiName getcoPlayers
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Get Co-Players   .
  *

  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      [
    {
      "user": "Varun Gopal",
      "user_id": "1",
      "co_player": "Mushin",
      "co_player_id": "2",
      "co_player_image": "",
      "co_player_sports": [
        {
          "sports": "1",
          "sports_name": "Cricket",
          "sports_image": "URL\r\n"
        }
      ]
    ]
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Get Co-Players
 /*......................................../*
/**
  *@api{get}users/co_players/1  Get Co-Players
  *@apiSampleRequest off
  *@apiName getcoPlayers
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Get Co-Players   .
  *

  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      [
    {
      "user": "Varun Gopal",
      "user_id": "1",
      "co_player": "Mushin",
      "co_player_id": "2",
      "co_player_image": "",
      "co_player_sports": [
        {
          "sports": "1",
          "sports_name": "Cricket",
          "sports_image": "URL\r\n"
        }
      ]
    ]
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Rate a co-player
 /*......................................../*
/**
  *@api{post}users/rate_co_players  Rate Co-Players
  *@apiSampleRequest off
  *@apiName ratecoPlayer
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Rate Co-Players   .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} co_player_id Co-Player ID
  *@apiParam {Number} rate Rate
  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
    "ErrorCode": 0,
     "Data": true,
     "Message": "
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
         Rate myself
 /*......................................../*
/**
  *@api{post}users/rate  Rate My Self
  *@apiSampleRequest off
  *@apiName rateMyself
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Rate My Self   .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} rate Rate
  
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
    "ErrorCode": 0,
     "Data": true,
     "Message": "
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Login
 /*......................................../*
/**
  *@api{post}login/  Login
  *@apiSampleRequest off
  *@apiName login
  *@apiGroup Login
  *@apiVersion 0.1.0
  *@apiDescription  Login   .
  *
  *@apiParam {Number} country_code Country ID
  *@apiParam {Number} phone_no Phone Number
  *@apiParam {Number} device_id Device Id
  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
     "Data": [
    {
      "id": "1",
      "name": "Varun Gopal",
      "nick": "VG",
      "address": "Ernakulam",
      "phone_no": "124",
      "designation": "PHP Dev",
      "rating": "2",
      "image": "",
      "otp": "6898"
    }
  ],
  "Message": "Login Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Otp verify
 /*......................................../*
/**
  *@api{post}login/otp_verify  Otp Verify
  *@apiSampleRequest off
  *@apiName otpVerify
  *@apiGroup Login
  *@apiVersion 0.1.0
  *@apiDescription  Otp Verification   .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} otp Otp Number

  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
     "ErrorCode": 0,
    "Data": "",
    "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Update User 
 /*......................................../*
/**
  *@api{post}users/update  Update Preference
  *@apiSampleRequest off
  *@apiName updatePreference
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Users Perference   .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Array} area_id  Area
  *@apiParam {Array} sport_id Sports


  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
     "ErrorCode": 0,
    "Data": "",
    "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Load Court Time
 /*......................................../*
/**
  *@api{post}venue/court_time  Court Time
  *@apiSampleRequest off
  *@apiName courtTime
  *@apiGroup Venue
  *@apiVersion 0.1.0
  *@apiDescription  Court Time  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} venue_id  Venue ID



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      {
      "court": "court1",
      "venue": "Venue",
      "time": "12:34:54"
    }
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Get Co-Player Details
 /*......................................../*
/**
  *@api{post}users/co_players_details  Co-Player Details
  *@apiSampleRequest off
  *@apiName coPlayerDetails
  *@apiGroup Users
  *@apiVersion 0.1.0
  *@apiDescription  Co-Player Details .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} co_player  Co-Player ID



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
      "user": "Varun Gopal",
      "user_id": "1",
      "co_player": "Mushin",
      "co_player_id": "2",
      "co_player_image": "",
      "sports_id": "1",
      "sports": "Cricket",
      "sports_image": "URL\r\n",
      "rating": "3"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Edit User Area
 /*......................................../*
/**
  *@api{post}area/edit_user_area  Edit User Area
  *@apiSampleRequest off
  *@apiName userAreaUpdate
  *@apiGroup Place
  *@apiVersion 0.1.0
  *@apiDescription  User Area  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Array} area  Area



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
           
     "ErrorCode": 0,
    "Data": "",
    "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Edit User Sports
 /*......................................../*
/**
  *@api{post}sports/edit_user_sports  Edit User Sports
  *@apiSampleRequest off
  *@apiName userSportsUpdate
  *@apiGroup Sports
  *@apiVersion 0.1.0
  *@apiDescription  User Sports  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Array} sports  Sports



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
         
           
     "ErrorCode": 0,
    "Data": "",
    "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Pending Match
 /*......................................../*
/**
  *@api{post}matches/pending_match  Pending Match
  *@apiSampleRequest off
  *@apiName pendingMatch
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription   Pending Match  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} match_id  Match ID



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
           
     "name": "Mushin",
      "id": "2",
      "image": "",
      "status": "Pending"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/

/*......................................../*
        Pending Match
 /*......................................../*
/**
  *@api{post}matches/update_match_status  Match Status Update
  *@apiSampleRequest off
  *@apiName matchStatus
  *@apiGroup Matches
  *@apiVersion 0.1.0
  *@apiDescription   Status Update  .
  *
  *@apiParam {Number} user_id User ID
  *@apiParam {Number} match_id  Match ID
  *@apiParam {Number} status  Status ID


  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
           
     "ErrorCode": 0,
  "Data": "",
  "Message": "Success"
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/


/*......................................../*
        Get User Sports
 /*......................................../*
/**
  *@api{get}sports/get_user_sports  Get User Sports
  *@apiSampleRequest off
  *@apiName userSports
  *@apiGroup Sports
  *@apiVersion 0.1.0
  *@apiDescription   Get User Sports  .
  *



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
           
     [
    {
      "sports": "Cricket",
      "id": "1",
      "image": "URL\r\n"
    },
    {
      "sports": "Football",
      "id": "2",
      "image": ""
    }
  ]
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/



/*......................................../*
        Get User Area
 /*......................................../*
/**
  *@api{get}area/get_user_area  Get User Area
  *@apiSampleRequest off
  *@apiName userArea
  *@apiGroup Place
  *@apiVersion 0.1.0
  *@apiDescription   Get User Area  .
  *



  *
  *@apiSampleRequest off
  *@apiSuccessExample Example data on success:
  *{
           
     [
    {
      "area": "Kaloor",
      "id": "1"
    }
  ],
    
  *}
  *@apiErrorExample  Error-Response:
  *{
    "error":"0",
    "Message": "Failed!" 
  *}  
*/