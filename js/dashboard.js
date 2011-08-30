var WGBitLyDashboard = WGBitLyDashboard || function($)
{
	var functions = {
		page : 1,
		init : function()
		{
			$(document).ready(function()
			{
				WGBitLyDashboard.loadPosts();
				$('#wg-bitly-dashboard .more').click(function(evt)
				{
					WGBitLyDashboard.loadPosts();
					evt.preventDefault();
				});
			});
		},
		loadPosts : function()
		{
			$('#wg-bitly-dashboard .more').html('Loading...');
			$.ajax({
				'url' : ajaxurl,
				'data' : {
					'action' : 'wg-bitly-stats',
					'page' : WGBitLyDashboard.page,
					'view' : 'dashboard'
				},
				'type' : 'POST',
				'success' : function(response)
				{
					WGBitLyDashboard.page++;
					$.tmpl('<tr><td>${date}</td><td>${title}</td><td>${clicks}</td></tr>', response['posts']).appendTo('#wg-bitly-dashboard tbody');
					if ( response['last'] )
					{
						$('#wg-bitly-dashboard .more').hide();
					}
					else
					{
						$('#wg-bitly-dashboard .more').html('Load more');
					}
				}
				});
		}
	};
	functions.init();
	return functions;
}(jQuery);