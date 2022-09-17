function add_to_cart(good_id) {
	$.post( "https://solfb.ru/ud/backend/add_to_cart.php", {good_id: good_id}, update_cart); 
	//alert(good_id);
	alert('Товар добавлен в корзину');
}
function update_cart() {
	$.post( "https://solfb.ru/ud/backend/update_cart.php", {}, on_success); 
	function on_success(data)
	{
		$('#small_cart').html(data);
	}
}
function remove_from_cart(good_id) {
	$.post( "https://solfb.ru/ud/backend/remove_from_cart.php", {good_id:good_id}, update_cart_interface); 
	alert('Товар добавлен в корзину');
}
function update_product_count(good_id, count) {
	$.post( "https://solfb.ru/ud/backend/update_product_count.php", {good_id:good_id, count:count}, update_cart_interface); 
	
	
}
function update_cart_interface() {
	$.post( "https://solfb.ru/ud/backend/cart_interface.php", {}, on_success); 
	function on_success(data)
	{
		$('#cart_interface').html(data);
	}
}

