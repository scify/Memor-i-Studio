var WidgetVideo = {

	settings: {
		defaultAspectRatio: 264/640
	},

	videoArray: [],

	sampleVideoList: function () {
		var videos = [
			{
				src : [
					'http://stream.flowplayer.org/bauhaus/624x260.webm',
					'http://stream.flowplayer.org/bauhaus/624x260.mp4',
					'http://stream.flowplayer.org/bauhaus/624x260.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/13.jpg',
				title : 'Introducing Pleasure',
				user: 'Tolga Ergin'
			},
			{
				src : [
					'http://stream.flowplayer.org/night3/640x360.webm',
					'http://stream.flowplayer.org/night3/640x360.mp4',
					'http://stream.flowplayer.org/night3/640x360.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/8.jpg',
				title : 'Going Freelance',
				user: 'Gökhun Güneyhan'
			},
			{
				src : [
					'http://stream.flowplayer.org/functional/624x260.webm',
					'http://stream.flowplayer.org/functional/624x260.mp4',
					'http://stream.flowplayer.org/functional/624x260.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/9.jpg',
				title : 'Designing User Interfaces',
				user: 'Philip Murphy'
			},
			{
				src : [
					'http://stream.flowplayer.org/bauhaus/624x260.webm',
					'http://stream.flowplayer.org/bauhaus/624x260.mp4',
					'http://stream.flowplayer.org/bauhaus/624x260.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/10.jpg',
				title : 'Single Page Apps',
				user: 'Joseph Graham'
			},
			{
				src : [
					'http://stream.flowplayer.org/night3/640x360.webm',
					'http://stream.flowplayer.org/night3/640x360.mp4',
					'http://stream.flowplayer.org/night3/640x360.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/11.jpg',
				title : 'How to Create a Wordpress Theme',
				user: 'Janet Mason'
			},
			{
				src : [
					'http://stream.flowplayer.org/functional/624x260.webm',
					'http://stream.flowplayer.org/functional/624x260.mp4',
					'http://stream.flowplayer.org/functional/624x260.ogv'
				],
				poster : Pleasure.settings.paths.images+'/picjumbo/12.jpg',
				title : '10 Rules Before Submitting',
				user: 'Bryan Richardson'
			}
		];
		return videos;
	},

	resizeVideoJS: function (myPlayer, id, aspectRatio, playlist) {
		var width = document.getElementById(id).parentElement.offsetWidth;
		myPlayer.width(width).height( width * aspectRatio );

		if(playlist) {
			var height = document.getElementById(id).parentElement.offsetHeight;
			$('.video-playlist').css('height', height-50);
		}
	},

	responsiveVideo: function () {
		for (var i = $('.video-js-responsive').length - 1; i >= 0; i--) {
			videojs( $('.video-js-responsive')[i].id ).ready(function(){
				// Store the video object
				var myPlayer = this, id = myPlayer.id();

				// Make up an aspect ratio
				($('#'+id).data('width') && $('#'+id).data('height')) ? aspectRatio = $('#'+id).data('height')/$('#'+id).data('width') : aspectRatio = WidgetVideo.settings.defaultAspectRatio;

				// Store player in array
				WidgetVideo.videoArray[i] = myPlayer;
				WidgetVideo.videoArray[i][0] = id;
				WidgetVideo.videoArray[i][1] = aspectRatio;
				$('#'+id).data('array-key', i);

				// Initialize resizeVideoJS()
				WidgetVideo.resizeVideoJS(myPlayer,id,aspectRatio);

				// Add/Attach the event on resize
				if (window.addEventListener) {
					window.addEventListener('resize', function(){
						WidgetVideo.resizeVideoJS(myPlayer, id, aspectRatio);
					}, false);
				} else if (window.attachEvent)  {
					window.attachEvent('onresize', function(){
						WidgetVideo.resizeVideoJS(myPlayer, id, aspectRatio);
					});
				}
			});
		}
	},

	listenModalEvents: function () {
		$('.video-js-modal').on('shown.bs.modal', function() {
			var arrayKey = $(this).find('.video-js').data('array-key');
			WidgetVideo.resizeVideoJS( WidgetVideo.videoArray[arrayKey], WidgetVideo.videoArray[arrayKey][0], WidgetVideo.videoArray[arrayKey][1]);

			if( $(this).find('.video-js').data('modal-autoplay') === true )
				WidgetVideo.videoArray[arrayKey].play();
		});

		$('.video-js-modal').on('hidden.bs.modal', function() {
			var arrayKey = $(this).find('.video-js').data('array-key');

			WidgetVideo.videoArray[arrayKey].pause();
		});
	},

	createList: function () {
		// Create the playlist
		var html = '';
		for (var i = 0; i < WidgetVideo.sampleVideoList().length; i++) {
			html += '<li data-video-index="'+i+'">'+
							'<div class="media">'+
							'<div class="pull-left"><img class="media-object" src="'+WidgetVideo.sampleVideoList()[i].poster+'" alt=""></div>'+
							'<div class="media-body">'+
							'<div class="pull-left"><h6 class="media-heading">'+WidgetVideo.sampleVideoList()[i].title+'</h6>'+
							'<small>by '+WidgetVideo.sampleVideoList()[i].user+'</small></div>'+
							'</div><!--.media-body-->'+
							'</div><!--.media-->'+
							'</li>';
		};
		$('ul.video-playlist').empty().html(html);
		$('ul.video-playlist').find('li:first').addClass('active');
	},

	videoPlaylist: function () {
		playlistPlayer = videojs('playlist').ready(function(){
			// Store the video object
			var myPlayer = this, id = myPlayer.id();

			// Initialize resizeVideoJS()
			WidgetVideo.resizeVideoJS(myPlayer, id, WidgetVideo.settings.defaultAspectRatio, 1);

			// Add/Attach the event on resize
			if (window.addEventListener) {
				window.addEventListener('resize', function(){
					WidgetVideo.resizeVideoJS(myPlayer, id, WidgetVideo.settings.defaultAspectRatio, 1);
				}, false);
			} else if (window.attachEvent)  {
				window.attachEvent('onresize', function(){
					WidgetVideo.resizeVideoJS(myPlayer, id, WidgetVideo.settings.defaultAspectRatio, 1);
				});
			}

			// Automatically jump to the next video
			this.on('next', function(e) {
				handleActiveVideo();
			});

			// Create html list elements
			WidgetVideo.createList();
		});

		playlistPlayer.playList(WidgetVideo.sampleVideoList(), {
			getVideoSource: function(vid, cb) {
				cb(vid.src, vid.poster);
			}
		});

		function handleActiveVideo() {
			var activeIndex = playlistPlayer.pl.current;
			$('ul.video-playlist').find('li').removeClass('active');
			$('ul.video-playlist').find('li[data-video-index="' + activeIndex +'"]').addClass('active');
			playlistPlayer.play();
		}

		// Events
		$('[data-action=prev]').on('click', function(e) {
			playlistPlayer.prev();
			handleActiveVideo();
		});
		$('[data-action=next]').on('click', function(e) {
			playlistPlayer.next();
			handleActiveVideo();
		});
		$('ul.video-playlist li').on('click', function(e) {
			playlistPlayer.playList($(this).data('video-index'));
			handleActiveVideo();
		});

	},

	init: function () {
		var playlistPlayer;
		videojs.options.flash.swf = 'assets/plugins/video-js/video-js.swf';

		this.responsiveVideo();
		this.listenModalEvents();
		this.videoPlaylist();

	}
}

