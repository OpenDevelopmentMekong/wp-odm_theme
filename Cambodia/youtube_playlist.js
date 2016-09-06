  //
  // YouTube JavaScript API Player With Playlist
  // http://salman-w.blogspot.com/2009/10/youtube-javascript-player-with-playlist.html
  // Revision 2 [2012-03-24]
  //
  // Prerequisites//
  // 1) Create following elements in your HTML:
  // -- a) ytplayer: a named anchor
  // -- b) ytplayer_div1: placeholder div for YouTube JavaScript Player
  // -- c) ytplayer_div2: container div for playlist
  // 2) Include SWFObject library from http://code.google.com/p/swfobject/
  //
  // Variables
  // -- ytplayer_playlist: an array containing YouTube Video IDs
  // -- ytplayer_playitem: index of the video to be played at any given time
  //
  var ytplayer_playlist = [ ];
  var ytplayer_playitem = 0;
  swfobject.addLoadEvent( ytplayer_render_player );
  swfobject.addLoadEvent( ytplayer_render_playlist );
  function ytplayer_render_player( )
  {
    swfobject.embedSWF
    (
      'https://www.youtube.com/v/' + ytplayer_playlist[ ytplayer_playitem ] + '&enablejsapi=1&rel=0&fs=1&version=3',
      'ytplayer_div1',
      '100%',
      '500',
      '10',
      null,
      null,
      {
        allowScriptAccess: 'always',
        allowFullScreen: 'true'
      },
      {
        id: 'ytplayer_object'
      }
    );
  }
  function ytplayer_render_playlist( )
  {
    var detect_lang_site = document.documentElement.lang;
    var titles;
    if(detect_lang_site == "en-US")
    {
      titles = ["Cambodia", "North", "Virachey", "East", "West", "Cardamon", "South"];
    }
    else
    {
      titles = ["កម្ពុជា", "ខាងជើង", "វីរៈជ័យ", "ខាងកើត", "ខាងលិច", "ជួរភ្នំក្រវ៉ាញ", "ខាងលិច"];
    }

    for ( var i = 0; i < ytplayer_playlist.length; i++ )
    {
      var div = document.createElement( "div" );
      var title = document.createElement( "p" );
      title.appendChild( document.createTextNode( titles[i] ) );
      var img = document.createElement( "img" );
      img.src = "https://img.youtube.com/vi/" + ytplayer_playlist[ i ] + "/default.jpg";
      var a = document.createElement( "a" );
      a.href = "#ytplayer";
      a.className = "ytplayer_title";
      //
      // Thanks to some nice people who answered this question:
      // http://stackoverflow.com/questions/1552941/variables-in-anonymous-functions-can-someone-explain-the-following
      //
      a.onclick = (
        function( j )
        {
          return function( )
          {
            ytplayer_playitem = j;
            ytplayer_playlazy( 1000 );
          };
        }
      )( i );
      a.appendChild( img );
      a.appendChild( title );
      div.appendChild( a );
      document.getElementById( "ytplayer_list" ).appendChild( div );
    }
  }
  function ytplayer_playlazy( delay )
  {
    //
    // Thanks to the anonymous person posted this tip:
    // http://www.tipstrs.com/tip/1084/Static-variables-in-Javascript
    //
    if ( typeof ytplayer_playlazy.timeoutid != 'undefined' )
    {
      window.clearTimeout( ytplayer_playlazy.timeoutid );
    }
    ytplayer_playlazy.timeoutid = window.setTimeout( ytplayer_play, delay );
  }
  function ytplayer_play( )
  {
    var o = document.getElementById( 'ytplayer_object' );
    if ( o )
    {
      o.loadVideoById( ytplayer_playlist[ ytplayer_playitem ] );
    }
  }
  //
  // Ready Handler (this function is called automatically by YouTube JavaScript Player when it is ready)
  // * Sets up handler for other events
  //
  function onYouTubePlayerReady( playerid )
  {
    var o = document.getElementById( 'ytplayer_object' );
    if ( o )
    {
      o.addEventListener( "onStateChange", "ytplayerOnStateChange" );
      o.addEventListener( "onError", "ytplayerOnError" );
    }
  }
  //
  // State Change Handler
  // * Sets up the video index variable
  // * Calls the lazy play function
  //
  function ytplayerOnStateChange( state )
  {
    if ( state == 0 )
    {
      ytplayer_playitem += 1;
      ytplayer_playitem %= ytplayer_playlist.length;
      ytplayer_playlazy( 5000 );
    }
  }
  //
  // Error Handler
  // * Sets up the video index variable
  // * Calls the lazy play function
  //
  function ytplayerOnError( error )
  {
    if ( error )
    {
      ytplayer_playitem += 1;
      ytplayer_playitem %= ytplayer_playlist.length;
      ytplayer_playlazy( 5000 );
    }
  }
