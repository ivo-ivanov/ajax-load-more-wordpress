jQuery(document).ready(function($) {


    //ajax custom loader news
    var pull_page = 1;

    $('#ajax-load-more-news').on('click', function(){

        var jsonFlag = true;
        if(jsonFlag){

        jsonFlag = false;
        pull_page++;
        $.getJSON("https://www.lilin.ch/wprs/wp-json/news/all-posts?page=" + pull_page, function(data){

        	if(data.length){

        		var items = [];
        		$.each(data, function(key, val){
        			var arr = $.map(val, function(el) { return el; });
        			var format = arr[0];
        			var permalink = arr[1];
        			var title = arr[2];
        			var thumbnail = arr[3];
                    var post_date = arr[4];

                    var item_string = '<div class="post-item item size2"><div class="artikel"><a href="'+ permalink +'" class="post-thumb default-thumb" style="background-image: url('+ thumbnail +')"></a><span class="date">'+ post_date +'</span><a href="'+ permalink +'" class="post-title"><h2>'+ title +'</h2></a></div></div>';

        			items.push(item_string);
        		});
        		if(data.length >= 12){

                    $('.post-container').append(items);

                } else {

                    $('.post-container').append(items);
    			    $('.load-more-wrapper').hide();

        		}
                
        	} else {

        		$('.load-more-wrapper').hide();

        	}

        }).done(function(data){
        	if(data.length){ jsonFlag = true; }
        });}
    });


});
