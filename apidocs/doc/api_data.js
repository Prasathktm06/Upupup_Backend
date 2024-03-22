define({ "api": [
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./doc/main.js",
    "group": "C__xampp_htdocs_UpUpUp_apidocs_doc_main_js",
    "groupTitle": "C__xampp_htdocs_UpUpUp_apidocs_doc_main_js",
    "name": ""
  },
  {
    "type": "post",
    "url": "login/",
    "title": "Login",
    "name": "login",
    "group": "Login",
    "version": "0.1.0",
    "description": "<p>Login   .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "country_code",
            "description": "<p>Country ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "phone_no",
            "description": "<p>Phone Number</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "device_id",
            "description": "<p>Device Id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n     \"Data\": [\n    {\n      \"id\": \"1\",\n      \"name\": \"Varun Gopal\",\n      \"nick\": \"VG\",\n      \"address\": \"Ernakulam\",\n      \"phone_no\": \"124\",\n      \"designation\": \"PHP Dev\",\n      \"rating\": \"2\",\n      \"image\": \"\",\n      \"otp\": \"6898\"\n    }\n  ],\n  \"Message\": \"Login Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Login"
  },
  {
    "type": "post",
    "url": "login/otp_verify",
    "title": "Otp Verify",
    "name": "otpVerify",
    "group": "Login",
    "version": "0.1.0",
    "description": "<p>Otp Verification   .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "otp",
            "description": "<p>Otp Number</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n     \"ErrorCode\": 0,\n    \"Data\": \"\",\n    \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Login"
  },
  {
    "type": "get",
    "url": "matches/1",
    "title": "Get Hosted Matches",
    "name": "getHostedMatches",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Get hosted matches.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n        \"id\": \"2\",\n      \"match_name\": \"IPL\",\n      \"image\": \"URL\\r\\n\",\n      \"date\": \"2017-02-14\",\n      \"time\": \"12:34:54\",\n      \"postedBy\": \"Varun Gopal\",\n      \"area\": \"Kaloor\",\n      \"status\": \"Active\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "post",
    "url": "matches/1",
    "title": "Host A Match",
    "name": "hostMatch",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Host a match.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "sports_id",
            "description": "<p>Sports ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "area_id",
            "description": "<p>Area ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "match_name",
            "description": "<p>Name</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "date",
            "description": "<p>Date</p>"
          },
          {
            "group": "Parameter",
            "type": "Time",
            "optional": false,
            "field": "time",
            "description": "<p>Time</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "no_players",
            "description": "<p>Number of players</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Description</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n        \"ErrorCode\": 0,\n         \"Data\": \"\",\n        \"Message\": \"Match Hosted!\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "post",
    "url": "matches/update_match_status",
    "title": "Match Status Update",
    "name": "matchStatus",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Status Update  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "match_id",
            "description": "<p>Match ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>Status ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n           \n     \"ErrorCode\": 0,\n  \"Data\": \"\",\n  \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "get",
    "url": "matches/past_booking/1",
    "title": "Get Past Matches",
    "name": "pastMatches",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Past Matches.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n      \"match_name\": \"name\",\n      \"id\": \"3\",\n      \"image\": \"\",\n      \"date\": \"2017-02-14\",\n      \"time\": \"12:34:54\",\n      \"status\": \"Finished\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "post",
    "url": "matches/pending_match",
    "title": "Pending Match",
    "name": "pendingMatch",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Pending Match  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "match_id",
            "description": "<p>Match ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n           \n     \"name\": \"Mushin\",\n      \"id\": \"2\",\n      \"image\": \"\",\n      \"status\": \"Pending\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "get",
    "url": "matches/upcoming_matches/2",
    "title": "Get Upcoming Matches",
    "name": "upcomingMatches",
    "group": "Matches",
    "version": "0.1.0",
    "description": "<p>Upcoming Matches.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n      \"match_name\": \"New Match\",\n      \"id\": \"4\",\n      \"image\": \"\",\n      \"date\": \"2017-02-06\",\n      \"time\": \"05:00:00\",\n      \"status\": \"Coming\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Matches"
  },
  {
    "type": "get",
    "url": "place/area/1",
    "title": "Get Area by Location",
    "name": "getArea",
    "group": "Place",
    "version": "0.1.0",
    "description": "<p>Get all area By location.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n      \"id\": \"1\",\n      \"area\": \"Kaloor\",\n      \"location_id\": \"1\",\n      \"location\": \"Kochi\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Place"
  },
  {
    "type": "get",
    "url": "place/region",
    "title": "Get Region",
    "name": "getRegion",
    "group": "Place",
    "version": "0.1.0",
    "description": "<p>Get all Region.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n       \"id\": \"1\",\n      \"location\": \"Kochi\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n   \"ErrorCode\": 1,\n     \"Message\": \"Error\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Place"
  },
  {
    "type": "get",
    "url": "area/get_user_area",
    "title": "Get User Area",
    "name": "userArea",
    "group": "Place",
    "version": "0.1.0",
    "description": "<p>Get User Area  .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n           \n     [\n    {\n      \"area\": \"Kaloor\",\n      \"id\": \"1\"\n    }\n  ],\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Place"
  },
  {
    "type": "post",
    "url": "area/edit_user_area",
    "title": "Edit User Area",
    "name": "userAreaUpdate",
    "group": "Place",
    "version": "0.1.0",
    "description": "<p>User Area  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "area",
            "description": "<p>Area</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n           \n     \"ErrorCode\": 0,\n    \"Data\": \"\",\n    \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Place"
  },
  {
    "type": "get",
    "url": "sports/",
    "title": "Get Sports",
    "name": "getSports",
    "group": "Sports",
    "version": "0.1.0",
    "description": "<p>Get all sports.</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n       \"id\": \"1\",\n      \"sports\": \"Cricket\",\n      \"image\": \"URL\\r\\n\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Sports"
  },
  {
    "type": "get",
    "url": "sports/get_user_sports",
    "title": "Get User Sports",
    "name": "userSports",
    "group": "Sports",
    "version": "0.1.0",
    "description": "<p>Get User Sports  .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n           \n     [\n    {\n      \"sports\": \"Cricket\",\n      \"id\": \"1\",\n      \"image\": \"URL\\r\\n\"\n    },\n    {\n      \"sports\": \"Football\",\n      \"id\": \"2\",\n      \"image\": \"\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Sports"
  },
  {
    "type": "post",
    "url": "sports/edit_user_sports",
    "title": "Edit User Sports",
    "name": "userSportsUpdate",
    "group": "Sports",
    "version": "0.1.0",
    "description": "<p>User Sports  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "sports",
            "description": "<p>Sports</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n           \n     \"ErrorCode\": 0,\n    \"Data\": \"\",\n    \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Sports"
  },
  {
    "type": "post",
    "url": "users/co_players_details",
    "title": "Co-Player Details",
    "name": "coPlayerDetails",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Co-Player Details .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "co_player",
            "description": "<p>Co-Player ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      \"user\": \"Varun Gopal\",\n      \"user_id\": \"1\",\n      \"co_player\": \"Mushin\",\n      \"co_player_id\": \"2\",\n      \"co_player_image\": \"\",\n      \"sports_id\": \"1\",\n      \"sports\": \"Cricket\",\n      \"sports_image\": \"URL\\r\\n\",\n      \"rating\": \"3\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "get",
    "url": "users/co_players/1",
    "title": "Get Co-Players",
    "name": "getcoPlayers",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Get Co-Players   .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      [\n    {\n      \"user\": \"Varun Gopal\",\n      \"user_id\": \"1\",\n      \"co_player\": \"Mushin\",\n      \"co_player_id\": \"2\",\n      \"co_player_image\": \"\",\n      \"co_player_sports\": [\n        {\n          \"sports\": \"1\",\n          \"sports_name\": \"Cricket\",\n          \"sports_image\": \"URL\\r\\n\"\n        }\n      ]\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "get",
    "url": "users/co_players/1",
    "title": "Get Co-Players",
    "name": "getcoPlayers",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Get Co-Players   .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      [\n    {\n      \"user\": \"Varun Gopal\",\n      \"user_id\": \"1\",\n      \"co_player\": \"Mushin\",\n      \"co_player_id\": \"2\",\n      \"co_player_image\": \"\",\n      \"co_player_sports\": [\n        {\n          \"sports\": \"1\",\n          \"sports_name\": \"Cricket\",\n          \"sports_image\": \"URL\\r\\n\"\n        }\n      ]\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "get",
    "url": "users/skill/1",
    "title": "Get Users Skills",
    "name": "getuserSkills",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Get users skills   .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      \"rating\": \"6\",\n      \"sports\": \"Cricket\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "post",
    "url": "users/rate",
    "title": "Rate My Self",
    "name": "rateMyself",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Rate My Self   .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "rate",
            "description": "<p>Rate</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n    \"ErrorCode\": 0,\n     \"Data\": true,\n     \"Message\": \"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "post",
    "url": "users/rate_co_players",
    "title": "Rate Co-Players",
    "name": "ratecoPlayer",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Rate Co-Players   .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "co_player_id",
            "description": "<p>Co-Player ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "rate",
            "description": "<p>Rate</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n    \"ErrorCode\": 0,\n     \"Data\": true,\n     \"Message\": \"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "post",
    "url": "users/update",
    "title": "Update Preference",
    "name": "updatePreference",
    "group": "Users",
    "version": "0.1.0",
    "description": "<p>Users Perference   .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "area_id",
            "description": "<p>Area</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "sport_id",
            "description": "<p>Sports</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n     \"ErrorCode\": 0,\n    \"Data\": \"\",\n    \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Users"
  },
  {
    "type": "post",
    "url": "venue/court_time",
    "title": "Court Time",
    "name": "courtTime",
    "group": "Venue",
    "version": "0.1.0",
    "description": "<p>Court Time  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "venue_id",
            "description": "<p>Venue ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      {\n      \"court\": \"court1\",\n      \"venue\": \"Venue\",\n      \"time\": \"12:34:54\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Venue"
  },
  {
    "type": "post",
    "url": "venue/court_time",
    "title": "Get Court Time",
    "name": "getVenue",
    "group": "Venue",
    "version": "0.1.0",
    "description": "<p>Get court time by user  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "venue_id",
            "description": "<p>Venue ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "court_id",
            "description": "<p>Court ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n     \"court\": \"court1\",\n      \"venue\": \"Venue\",\n      \"time\": \"12:34:54\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Venue"
  },
  {
    "type": "post",
    "url": "venue/1",
    "title": "Get Venue",
    "name": "getVenue",
    "group": "Venue",
    "version": "0.1.0",
    "description": "<p>Get Venue by users .</p>",
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      \"venue_id\": \"2\",\n      \"lat\": \"22.2\",\n      \"lon\": \"12.2\",\n      \"cost\": \"0\",\n      \"description\": \"Good\",\n      \"court\": \"2\",\n      \"venue\": \"Venue\",\n      \"image\": \"\",\n      \"area\": \"Kaloor\",\n      \"sports\": \"Cricket\",\n      \"id\": \"1\",\n      \"sports_image\": \"URL\\r\\n\",\n      \"timing\": {\n        \"morning\": \"12:34:54\",\n        \"evening\": \"12:34:54\"\n      },\n      \"facilities\": [\n        {\n          \"facility\": \"Drinking Water\"\n        }\n      ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Data Not Found!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Venue"
  },
  {
    "type": "post",
    "url": "venue/rate",
    "title": "Rate A Venue",
    "name": "rateVenue",
    "group": "Venue",
    "version": "0.1.0",
    "description": "<p>Rate a Venue  .</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "venue_id",
            "description": "<p>Venue ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "rate",
            "description": "<p>Rate</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Example data on success:",
          "content": "{\n         \n      \"ErrorCode\": 0,\n      \"Data\": true,\n      \"Message\": \"Success\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"error\":\"0\",\n    \"Message\": \"Failed!\" \n}",
          "type": "json"
        }
      ]
    },
    "filename": "./doc.js",
    "groupTitle": "Venue"
  }
] });
