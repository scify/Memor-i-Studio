var WidgetAudio = {

	playerOptions: function () {
		var createPlayerOptions = {
			markup: '\
			<div class="play-pause"> \
				<p class="play"><i class="fa fa-play fa-2x"></i></p> \
				<p class="pause"><i class="fa fa-pause fa-2x"></i></p> \
				<p class="loading"><i class="fa fa-refresh fa-spin fa-2x"></i></p> \
				<p class="error"></p> \
			</div> \
			<div class="scrubber"> \
				<div class="progress"></div> \
				<div class="loaded"></div> \
			</div> \
			<div class="time"> \
				<em class="played">00:00</em>/<strong class="duration">00:00</strong> \
			</div> \
			<div class="error-message"></div>',
			playPauseClass: 'play-pause',
			scrubberClass: 'scrubber',
			progressClass: 'progress',
			loaderClass: 'loaded',
			timeClass: 'time',
			durationClass: 'duration',
			playedClass: 'played',
			errorMessageClass: 'error-message',
			playingClass: 'playing',
			loadingClass: 'loading',
			errorClass: 'error'
		}
		return createPlayerOptions;
	},

	single: function () {
		var audiojsSingle = audiojs.create( $('.audiojs-single') ,{
			css: false,
			createPlayer: WidgetAudio.playerOptions()
		});
	},

	audioList: function () {
		var audioJsList = audiojs.create( $('.audiojs-list') ,{
			css: false,
			createPlayer: WidgetAudio.playerOptions(),
			trackEnded: function() {
				var current = $('#'+this.wrapper.id).parents('.card').find('ul.list-songs .fa-play');
				var next = current.parents('li').next();
				var next_song = next.find('a');
				if (!next.length) next = $('#'+this.wrapper.id).parents('.card').find('ul.list-songs li').first();

				$('#'+this.wrapper.id).parents('.card').find('ul.list-songs .fa-play').removeClass('fa-play');
				next.find('i').addClass('fa-play');

				this.load( next_song.data('src') );
				this.play();

				$('#'+this.wrapper.id).parents('.card').find('.song-container .artist').text( next_song.data('artist') );
				$('#'+this.wrapper.id).parents('.card').find('.song-container .song').text( next_song.data('song') );
			}
		});

		for (var i = audioJsList.length - 1; i >= 0; i--) {
			$('#'+audioJsList[i].wrapper.id).parents('.card').data('audiojs', i);

			var firstItem = $('#'+audioJsList[i].wrapper.id).parents('.card').find('ul.list-songs li').first();
			firstItem.find('i').addClass('fa-play');
			audioJsList[i].load( firstItem.find('a').data('src') );
			$('#'+audioJsList[i].wrapper.id).parents('.card').find('.song-container .artist').text( firstItem.find('a').data('artist') );
			$('#'+audioJsList[i].wrapper.id).parents('.card').find('.song-container .song').text( firstItem.find('a').data('song') );
		};

		$('ul.list-songs a').click(function(e) {
			e.preventDefault();
			var i = $(this).parents('.card').data('audiojs');

			$(this).parents('.card').find('.song-container .artist').text( $(this).data('artist') );
			$(this).parents('.card').find('.song-container .song').text( $(this).data('song') );

			$(this).parents('ul:first').find('.fa-play').removeClass('fa-play');
			$(this).find('i').addClass('fa-play');
			audioJsList[i].load($(this).data('src'));
			audioJsList[i].play();
		});
	},

	init: function () {

		audiojs.events.ready(function() {
			WidgetAudio.single();
			WidgetAudio.audioList();
		});

		$('.card [data-toggle="list"]').on('click',function() {
			$(this).parents('.card').toggleClass('collapsed');
		});
	}
}




