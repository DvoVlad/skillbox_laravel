Echo
	.channel('laravel_database_hello')
	.listen('SomethingHappens', (e) => {
		//alert(e.whatHappens);
		$.notify(e.whatHappens);
	});
Echo
	.channel('laravel_database_admin')
	.listen('AdminNotify', (e) => {
		//alert(e.whatHappens);
		$.notify(e.message);
	});
