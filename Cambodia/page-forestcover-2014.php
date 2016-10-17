<script type="text/javascript">
$(document).ready(function(){
	$(".ytplayer_title").click(function(event){
		var youtubeimg = $(this).find("img");
		var youtubeid = youtubeimg.attr("src").match(/[\w\-]{11,}/)[0];
		$("#vid_frame").attr({
      	src: "https://www.youtube.com/embed/" + youtubeid+"?autoplay=1&rel=0&amp;showinfo=1&amp;autohide=0",
    });
	});
});
</script>
