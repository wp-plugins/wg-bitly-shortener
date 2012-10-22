var WGBitLyPost = WGBitLyPost || function($)
{
	var functions = {
		init : function()
		{
			if (adminpage == 'post-php')
			{
				$(document).ready(function()
				{
					WGBitLyPost.loadPosts();
				});				
			}
		},
		loadPosts : function()
		{
			$('#wg-bitly .inside .stats').html('Loading stats');
			$.ajax({
				'url' : ajaxurl,
				'data' : {
					'action' : 'wg-bitly-stats',
					'view' : 'post',
					'post' : $('#wg-bitly .inside .stats').attr('data-post')
				},
				'type' : 'POST',
				'success' : function(response)
				{
					console.log(response);
					$('#wg-bitly .stats').html($.tmpl('<p><b>Number of clicks:</b> ${clicks}</p>', response));
				}
				});
		}
	};
	functions.init();
	return functions;
}(jQuery);