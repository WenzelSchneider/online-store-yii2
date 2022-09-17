
    $("#myForm").validate({
	rules: {
		email: {
			required: true,
			email: true,
		}
	},
	messages: {
		email: {
		    required: 'Мама миа, сделай слово',
			email: 'true',
		}
	}
});