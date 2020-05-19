//Echo
//	.channel('p_hello')
//	.listen('SomethingHappens', (e) => {
//		alert(e.whatHappens);
//		$.notify(e.whatHappens);
//	});
Echo
	.private('admin')
	.listen('AdminNotify', (e) => {
		$.notify(e.message);
	});
