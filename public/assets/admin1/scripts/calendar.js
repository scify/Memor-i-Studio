var Calendar = {

	createCalendar: function () {
		var date = new Date(),
				m = date.getMonth(),
				d = date.getDate(),
				y = date.getFullYear();

		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			firstDay: 0, // 1 for Monday, 2 for Tuesday
			isRTL: Pleasure.settings.rtl,
			eventLimit: true,
			editable: true,
			droppable: true,
			events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, d-2)
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d+1),
					end: new Date(y, m, d+3)
				},
				{
					id: 999,
					title: 'Wake Up',
					start: new Date(y, m, d, 6, 30),
					allDay: false
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+10, 22, 0),
					end: new Date(y, m, d+11, 2, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, 17, 16, 15),
					end: new Date(y, m, 17, 17, 0),
					allDay: false
				},
				{
					title: 'Watching News',
					start: new Date(y, m, 17, 20, 0),
					end: new Date(y, m, 17, 21, 0),
					allDay: false
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: new Date(y, m, 17),
					end: new Date(y, m, 17)
				}
			],
			drop: function(date) {

				var originalEventObject = $(this).data('eventObject');
				var extendedEventObject = $.extend({}, originalEventObject);

				extendedEventObject.start = date;
				$('#calendar').fullCalendar('renderEvent', extendedEventObject, true);

				if ($('#drop-remove').is(':checked')) {
					$(this).remove();
				}

			}
		});

		$('#external-events .fc-event').each(function() {
			var eventObject = {
				title: $.trim($(this).find('span').text())
			};
			$(this).data('eventObject', eventObject);
			$(this).draggable({
				revert: true,
				revertDuration: 0,
				zIndex: 1999
			});

		});

		$('.draggable-events').on('click', 'button.close', function() {
			$(this).parents('.fc-event').remove();
		});

		$('#add-event').on('click', function() {
			var title = $('#event-title').val();
			Calendar.addEvent(title);
			$('#event-title').val('').focus();
		});

		$('#create-event').submit(function () {
			$('#event-title').val('').focus();
			return false;
		});
	},

	handleEventDragging: function (obj) {
		var eventObject = {
			title: $.trim(obj.find('span').text())
		};
		obj.data('eventObject', eventObject);
		obj.draggable({
			revert: true,
			revertDuration: 0,
			zIndex: 1999
		});
	},

	addEvent: function (title) {
		title = title.length === 0 ? "Missing Event Title" : title;
		var html = $('<div class="fc-event"><span>' + title + '</span> <button type="button" class="close">&times;</button></div>');
		$('.draggable-events').append(html);
		Calendar.handleEventDragging(html);
	},

	init: function () {
		this.createCalendar();
	}
}




