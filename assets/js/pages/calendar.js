$( document ).ready(function() {
    
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '1990-01-01',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{
					title: 'All Day Event',
					start: '1990-01-01'
				},
				{
					title: 'Long Event',
					start: '2024-09-27',
					end: '2024-09-27'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2024-09-27T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2024-09-27T16:00:00'
				},
				{
					title: 'Conference',
					start: '2024-09-27',
					end: '2024-09-27'
				},
				{
					title: 'Meeting',
					start: '2024-09-27T10:30:00',
					end: '2024-09-27T12:30:00'
				},
				{
					title: 'Lunch',
					start: '2024-09-27T12:00:00'
				},
				{
					title: 'Meeting',
					start: '2024-09-27T14:30:00'
				},
				{
					title: 'Happy Hour',
					start: '2024-09-27T17:30:00'
				},
				{
					title: 'Dinner',
					start: '2024-09-27T20:00:00'
				},
				{
					title: 'Birthday Party',
					start: '2024-09-27T07:00:00'
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: '2024-09-27'
				}
			]
		});

});