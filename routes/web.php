<?php

Route::get('/', 'FrontEndController@index');

Auth::routes(['register' => false]);


Route::permanentRedirect('/public', '/admin');


/* ADMIN */
Route::group(["prefix" => "admin", 'middleware' => ['auth']], function () {

	Route::get('bag', ['as' => 'bag', 'uses' => 'PedidosController@bag']);
	Route::get('public/bag', ['as' => 'bag', 'uses' => 'PedidosController@bag']);

	Route::post('mostra', ['as' => 'mostra', 'uses' => 'PedidosController@mostra']);
	Route::post('alojamento', ['as' => 'alojamento', 'uses' => 'PedidosController@alojamento'])->middleware('auth');


	Route::post('/enviar-transfer/transfergest-api',  ['as' => 'sendtransfer.transfergest', 'uses' => 'ProfilesController@enviarTransferTransfergest']);
	Route::post('/enviar-servicos/pedido-geral/api-transfergest',  ['as' => 'enviarservico.transfergest', 'uses' => 'ProfilesController@enviarPedidoGeralTransfergest']);
	Route::post('/buscar-pagamentos/api-transfergest',  ['as' => 'buscarpagamentos.transfergest', 'uses' => 'ProfilesController@buscarPagamentosTransfergest']);
	Route::post('/add-deposito/api-transfergest',  ['as' => 'adddeposito.transfergest', 'uses' => 'ProfilesController@addDepositoTransfergest']);


	Route::get('/', 'HomeController@index')->name("admin.index");



	Route::group(['prefix' => 'pedidos'], function () {

		Route::get('new/reports', ['as' => 'pedidos.reports.index', 'uses' => 'PedidosReportsController@newIndex']);
		Route::get('new/reports/ats', ['as' => 'pedidos.reports.index.ats', 'uses' => 'PedidosReportsController@newIndex']);
		Route::post('new/reports', ['as' => 'pedidos.reports.buscar', 'uses' => 'PedidosReportsController@applyFilter']);
		Route::post('new/reports/ats', ['as' => 'pedidos.reports.buscar.ats', 'uses' => 'PedidosReportsController@applyFilter']);
		Route::get('reports/pedidosreports/print/dados', ['as' => 'reports.pedidosreports.print.new', 'uses' => 'PedidosReportsController@PrintPedido']);
	});

	Route::group(['prefix' => 'pedidos/v2'], function () {
		Route::get('reports', ['as' => 'pedidos.v2.reports.index', 'uses' => 'PedidosReportsV2Controller@index']);
		Route::get('reports/ats', ['as' => 'pedidos.v2.reports.index.ats', 'uses' => 'PedidosReportsV2Controller@index']);
		Route::post('reports', ['as' => 'pedidos.v2.reports.buscar', 'uses' => 'PedidosReportsV2Controller@applyFilter']);
		Route::post('reports/ats', ['as' => 'pedidos.v2.reports.buscar.ats', 'uses' => 'PedidosReportsV2Controller@applyFilter']);
		Route::get('reports/pedidosreports/pdf', ['as' => 'pedidos.v2.reports.export.pdf', 'uses' => 'PedidosReportsV2Controller@reportPDF']);
		Route::get('reports/pedidosreports/excel', ['as' => 'pedidos.v2.reports.export.excel', 'uses' => 'PedidosReportsV2Controller@reportExcel']);
	});


	Route::group(['prefix' => 'profile'], function () {

		Route::get('', ['as' => 'profile', 'uses' => 'ProfilesController@index']);
		Route::get('search', ['as' => 'profile.search', 'uses' => 'ProfilesController@search']);
		Route::match(["get", "post"], 'create', ['as' => 'profile.create', 'uses' => 'ProfilesController@create']);
		Route::get('confirm', ['as' => 'profile.confirm', 'uses' => 'ProfilesController@confirm']);
		Route::post('confirm', ['as' => 'profile.confirm', 'uses' => 'ProfilesController@confirm']);
		Route::post('cancelar_booking_api', ['as' => 'profile.cancelar_booking_api', 'uses' => 'ProfilesController@apagarBookingApi']);

		Route::post('salvar/quartos', ['as' => ' new.profile.confirm', 'uses' => 'ProfilesController@confirm']);
		Route::match(['get', 'post'], 'createProduct', ['as' => 'profile.createProduct', 'uses' => 'ProfilesController@createProduct']);
		Route::match(['get', 'post'], 'createProductRooms', ['as' => 'profile.createProductRooms', 'uses' => 'ProfilesController@createProductRooms']);
		Route::match(['get', 'post'], 'createProductRoomsEsp', ['as' => 'profile.createProductRoomsEsp', 'uses' => 'ProfilesController@createProductRoomsEsp']);
		Route::match(['get', 'post'], 'createProductGolfs', ['as' => 'profile.createProductGolfs', 'uses' => 'ProfilesController@createProductGolfs']);
		Route::match(['get', 'post'], 'createProductGolfsEsp', ['as' => 'profile.createProductGolfsEsp', 'uses' => 'ProfilesController@createProductGolfsEsp']);
		Route::match(['get', 'post'], 'createProductTransfers', ['as' => 'profile.createProductTransfers', 'uses' => 'ProfilesController@createProductTransfers']);
		Route::match(['get', 'post'], 'createProductTransfersEsp', ['as' => 'profile.createProductTransfersEsp', 'uses' => 'ProfilesController@createProductTransfersEsp']);
		Route::match(['get', 'post'], 'createProductCars', ['as' => 'profile.createProductCars', 'uses' => 'ProfilesController@createProductCars']);
		Route::match(['get', 'post'], 'createProductCarsEsp', ['as' => 'profile.createProductCarsEsp', 'uses' => 'ProfilesController@createProductCarsEsp']);
		Route::match(['get', 'post'], 'createProductTickets', ['as' => 'profile.createProductTickets', 'uses' => 'ProfilesController@createProductTickets']);
		Route::match(['get', 'post'], 'createProductTicketsEsp', ['as' => 'profile.createProductTicketsEsp', 'uses' => 'ProfilesController@createProductTicketsEsp']);
		Route::match(['get', 'post'], 'createProductExtra', ['as' => 'profile.createProductExtra', 'uses' => 'ProfilesController@createProductExtra']);

		Route::match(['get', 'post'], 'mail', ['as' => 'profile.mail', 'uses' => 'ProfilesController@mail']);

		Route::match(['get', 'post'], 'mailConf', ['as' => 'profile.mailConf', 'uses' => 'ProfilesController@mailConf']);
		Route::match(['get', 'post'], '{pedido_id}/{pedido_produto_id}/voucher', ['as' => 'profile.voucher', 'uses' => 'ProfilesController@voucher']);
		Route::post('delete', ['as' => 'profile.destroy', 'uses' => 'ProfilesController@destroy']);
		Route::post('getroomnames', ['as' => 'profile.getroomnames', 'uses' => 'ProfilesController@getRoomNames']);

		Route::post('get/rooms', ['as' => 'profile.getroomnames.new', 'uses' => 'ProfilesController@getNewRoomData']);

		/* felix */
		Route::post('editroomnames', ['as' => 'profile.editroomnames', 'uses' => 'ProfilesController@editRoomNames']);
		Route::post('editar/rooms', ['as' => 'profile.editroomnew', 'uses' => 'ProfilesController@newEditRoomNames']);
		Route::post('delete/empty/rooms', ['as' => 'profile.delete.empty.rooms', 'uses' => 'ProfilesController@removeEmptyRooms']);
		Route::match(['get', 'post'], 'download/excel/roomslist/{id}', ['as' => 'profile.download.excel.roomlist', 'uses' => 'ProfilesController@export']);
		Route::post('new/update/rooms', ['as' => 'profile.update.room.qtds', 'uses' => 'ProfilesController@updateRoomQtdAndPaxQtd']);
		/* felix */

		Route::post('sendremark', ['as' => 'profile.sendremark', 'uses' => 'ProfilesController@sendRemark']);
		Route::post('editremark', ['as' => 'profile.editremark', 'uses' => 'ProfilesController@editRemark']);
		Route::post('sendpayment', ['as' => 'profile.sendpayment', 'uses' => 'ProfilesController@sendPayment']);
		Route::post('removepayment', ['as' => 'profile.removepayment', 'uses' => 'ProfilesController@removePayment']);
		Route::post('getproducts', ['as' => 'profile.getproducts', 'uses' => 'ProfilesController@getProducts']);
		Route::post('createproducts', ['as' => 'profile.createproducts', 'uses' => 'ProfilesController@createProducts']);
		Route::post('editpedidogeral', ['as' => 'profile.editpedidogeral', 'uses' => 'ProfilesController@editPedidoGeral']);
		Route::post('removeproduct', ['as' => 'profile.removeproduct', 'uses' => 'ProfilesController@removeProducts']);
		Route::get('printpedido/{id}', ['as' => 'profile.printpedido', 'uses' => 'ProfilesController@PrintPedido']);
		Route::get('printpedido/{id}/{ats}', ['as' => 'profile.printpedido.ats', 'uses' => 'ProfilesController@PrintPedido']);
		Route::get('printpedidomarkup/{id}', ['as' => 'profile.printpedidomarkup', 'uses' => 'ProfilesController@PrintPedidoMarkup']);

		Route::group(['prefix' => 'remarks'], function () {
			Route::post('interno/salvar', ['as' => 'profile.remarks.interno.salvar', 'uses' => 'ProfilesController@salvarRemarkInterno']);
		});
	});

	Route::group(['prefix' => 'groups'], function () {
		Route::get('', ['as' => 'groups', 'uses' => 'RolesController@index']);
		Route::get('create', ['as' => 'groups.create', 'uses' => 'RolesController@create']);
		Route::post('store', ['as' => 'groups.store', 'uses' => 'RolesController@store']);
		Route::get('destroy/{id}', ['as' => 'groups.destroy', 'uses' => 'RolesController@destroy']);
		Route::get('linka/{role}/{user}', ['as' => 'groups.linka', 'uses' => 'RolesController@linka']);
		Route::get('delinka/{role}/{user}', ['as' => 'groups.delinka', 'uses' => 'RolesController@delinka']);
		Route::get('edit/{id}', ['as' => 'groups.edit', 'uses' => 'RolesController@edit']);
		Route::put('update/{id}', ['as' => 'groups.update', 'uses' => 'RolesController@update']);
	});

	Route::group(['prefix' => 'users'], function () {
		Route::get('', ['as' => 'auth', 'uses' => 'LoginController@index']);
		Route::post('create', ['as' => 'users.create', 'uses' => 'LoginController@create']);
		Route::post('edit', ['as' => 'users.edit', 'uses' => 'LoginController@edit']);
		Route::get('{id}/edit', ['as' => 'users.edit', 'uses' => 'LoginController@edit']);
		Route::put('{id}/update', ['as' => 'users.update', 'uses' => 'LoginController@update']);
		Route::get('{id}/destroy', ['as' => 'users.destroy', 'uses' => 'LoginController@destroy']);
		Route::get('bank', ['as' => 'users.bank', 'uses' => 'LoginController@bank']);

		Route::post('bankcreate', ['as' => 'users.bankcreate', 'uses' => 'LoginController@bankcreate']);
		Route::get('bankedit', ['as' => 'users.bankedit', 'uses' => 'LoginController@bankedit']);
		Route::get('bankdestroy', ['as' => 'users.bankdestroy', 'uses' => 'LoginController@bankdestroy']);
		Route::get('bankaccountdestroy', ['as' => 'users.bankaccountdestroy', 'uses' => 'LoginController@bankaccountdestroy']);
		Route::post('bankupdate', ['as' => 'users.bankupdate', 'uses' => 'LoginController@bankupdate']);
		Route::post('bankcreateaccount', ['as' => 'users.bankcreateaccount', 'uses' => 'LoginController@bankcreateaccount']);
		Route::get('contact', ['as' => 'users.contact', 'uses' => 'LoginController@contact']);
		Route::post('createcontact', ['as' => 'users.createcontact', 'uses' => 'LoginController@createcontact']);
		Route::post('createlocal', ['as' => 'users.createlocal', 'uses' => 'LoginController@createlocal']);
		Route::get('destroycontact', ['as' => 'users.destroycontact', 'uses' => 'LoginController@destroycontact']);
		Route::get('contactedit', ['as' => 'users.contactedit', 'uses' => 'LoginController@contactedit']);
		Route::get('localtedit', ['as' => 'users.localtedit', 'uses' => 'LoginController@localtedit']);
		Route::get('destroylocal', ['as' => 'users.destroylocal', 'uses' => 'LoginController@destroylocal']);
		Route::post('localupdt', ['as' => 'users.localupdt', 'uses' => 'LoginController@localupdt']);
		Route::post('contactupdate', ['as' => 'users.contactupdate', 'uses' => 'LoginController@contactupdate']);
		Route::get('location', ['as' => 'users.location', 'uses' => 'LoginController@location']);
		Route::get('search', ['as' => 'users.search', 'uses' => 'LoginController@search']);
	});

	Route::group(['prefix' => 'extras'], function () {
		Route::get('', ['as' => 'extras', 'uses' => 'ExtrasController@index']);
		Route::get('create', ['as' => 'extras.create', 'uses' => 'ExtrasController@create']);
		Route::post('store', ['as' => 'extras.store', 'uses' => 'ExtrasController@store']);
		Route::get('{id}/destroy', ['as' => 'extras.destroy', 'uses' => 'ExtrasController@destroy']);
		Route::get('{id}/edit', ['as' => 'extras.edit', 'uses' => 'ExtrasController@edit']);
		Route::put('{id}/update', ['as' => 'extras.update', 'uses' => 'ExtrasController@update']);
	});

	Route::group(['prefix' => 'locais'], function () {
		Route::get('', ['as' => 'locais', 'uses' => 'LocaisController@index']);
		Route::get('create', ['as' => 'locais.create', 'uses' => 'LocaisController@create']);
		Route::post('store', ['as' => 'locais.store', 'uses' => 'LocaisController@store']);
		Route::get('{id}/destroy', ['as' => 'locais.destroy', 'uses' => 'LocaisController@destroy']);
		Route::get('{id}/edit', ['as' => 'locais.edit', 'uses' => 'LocaisController@edit']);
		Route::put('{id}/update', ['as' => 'locais.update', 'uses' => 'LocaisController@update']);
	});

	Route::group(['prefix' => 'categories'], function () {
		Route::get('', ['as' => 'categories', 'uses' => 'CategoriesController@index']);
		Route::get('create', ['as' => 'categories.create', 'uses' => 'CategoriesController@create']);
		Route::post('store', ['as' => 'categories.store', 'uses' => 'CategoriesController@store']);
		Route::get('{id}/destroy', ['as' => 'categories.destroy', 'uses' => 'CategoriesController@destroy']);
		Route::get('{id}/edit', ['as' => 'categories.edit', 'uses' => 'CategoriesController@edit']);
		Route::put('{id}/update', ['as' => 'categories.update', 'uses' => 'CategoriesController@update']);
	});

	Route::group(['prefix' => 'suppliers'], function () {
		Route::get('', ['as' => 'suppliers', 'uses' => 'SuppliersController@index']);
		Route::post('create', ['as' => 'suppliers.create', 'uses' => 'SuppliersController@create']);
		Route::post('edit', ['as' => 'suppliers.edit', 'uses' => 'SuppliersController@edit']);
		Route::get('{id}/edit', ['as' => 'suppliers.edit', 'uses' => 'SuppliersController@edit']);
		Route::put('{id}/update', ['as' => 'suppliers.update', 'uses' => 'SuppliersController@update']);
		Route::get('{id}/destroy', ['as' => 'suppliers.destroy', 'uses' => 'SuppliersController@destroy']);
		Route::get('bank', ['as' => 'suppliers.bank', 'uses' => 'SuppliersController@bank']);
		Route::post('bankcreate', ['as' => 'suppliers.bankcreate', 'uses' => 'SuppliersController@bankcreate']);
		Route::get('bankedit', ['as' => 'suppliers.bankedit', 'uses' => 'SuppliersController@bankedit']);
		Route::get('bankdestroy', ['as' => 'suppliers.bankdestroy', 'uses' => 'SuppliersController@bankdestroy']);
		Route::get('bankaccountdestroy', ['as' => 'suppliers.bankaccountdestroy', 'uses' => 'SuppliersController@bankaccountdestroy']);
		Route::post('bankupdate', ['as' => 'suppliers.bankupdate', 'uses' => 'SuppliersController@bankupdate']);
		Route::post('bankcreateaccount', ['as' => 'suppliers.bankcreateaccount', 'uses' => 'SuppliersController@bankcreateaccount']);
		Route::get('contact', ['as' => 'suppliers.contact', 'uses' => 'SuppliersController@contact']);
		Route::post('createcontact', ['as' => 'suppliers.createcontact', 'uses' => 'SuppliersController@createcontact']);
		Route::post('createlocal', ['as' => 'suppliers.createlocal', 'uses' => 'SuppliersController@createlocal']);
		Route::get('destroycontact', ['as' => 'suppliers.destroycontact', 'uses' => 'SuppliersController@destroycontact']);
		Route::get('contactedit', ['as' => 'suppliers.contactedit', 'uses' => 'SuppliersController@contactedit']);
		Route::get('localtedit', ['as' => 'suppliers.localtedit', 'uses' => 'SuppliersController@localtedit']);
		Route::get('destroylocal', ['as' => 'suppliers.destroylocal', 'uses' => 'SuppliersController@destroylocal']);
		Route::post('localupdt', ['as' => 'suppliers.localupdt', 'uses' => 'SuppliersController@localupdt']);
		Route::post('contactupdate', ['as' => 'suppliers.contactupdate', 'uses' => 'SuppliersController@contactupdate']);
		Route::get('location', ['as' => 'suppliers.location', 'uses' => 'SuppliersController@location']);
		Route::get('search', ['as' => 'suppliers.search', 'uses' => 'SuppliersController@search']);
	});

	Route::group(['prefix' => 'produtos'], function () {
		Route::get('', ['as' => 'produtos', 'uses' => 'ProdutosController@index']);
		Route::post('', ['as' => 'produtos', 'uses' => 'ProdutosController@index']);
		Route::get('create', ['as' => 'produtos.create', 'uses' => 'ProdutosController@create']);
		Route::post('store', ['as' => 'produtos.store', 'uses' => 'ProdutosController@store']);
		Route::get('{id}/destroy', ['as' => 'produtos.destroy', 'uses' => 'ProdutosController@destroy']);
		Route::get('coment', ['as' => 'produtos.coment', 'uses' => 'ProdutosController@coment']);
		Route::post('grava', ['as' => 'produtos.grava', 'uses' => 'ProdutosController@grava']);
		Route::get('{id}/edit', ['as' => 'produtos.edit', 'uses' => 'ProdutosController@edit']);
		Route::put('{id}/update', ['as' => 'produtos.update', 'uses' => 'ProdutosController@update']);
		Route::post('foto', ['as' => 'produtos.foto', 'uses' => 'ProdutosController@foto']);
		Route::get('fotodel', ['as' => 'produtos.fotodel', 'uses' => 'ProdutosController@fotodel']);
		Route::get('pdfdel', ['as' => 'produtos.pdfdel', 'uses' => 'ProdutosController@pdfdel']);
		Route::get('fotoprincipal', ['as' => 'produtos.fotoprincipal', 'uses' => 'ProdutosController@fotoprincipal']);
		Route::post('fotoedit', ['as' => 'produtos.fotoedit', 'uses' => 'ProdutosController@fotoedit']);
		Route::post('pdf', ['as' => 'produtos.pdf', 'uses' => 'ProdutosController@pdf']);
		Route::post('pdfedit', ['as' => 'produtos.pdfedit', 'uses' => 'ProdutosController@pdfedit']);
		Route::post('addextra', ['as' => 'produtos.addextra', 'uses' => 'ProdutosController@addextra']);
		Route::post('editextra', ['as' => 'produtos.editextra', 'uses' => 'ProdutosController@editextra']);
		Route::get('extradel', ['as' => 'produtos.extradel', 'uses' => 'ProdutosController@extradel']);
		Route::post('changestate', ['as' => 'produtos.changestate', 'uses' => 'ProdutosController@changeState']);
	});

	Route::group(['prefix' => 'main'], function () {
		Route::get('', ['as' => 'main', 'uses' => 'MainController@index']);
		Route::get('create', ['as' => 'main.create', 'uses' => 'MainController@create']);
		Route::post('store', ['as' => 'main.store', 'uses' => 'MainController@store']);
		Route::get('{id}/destroy', ['as' => 'main.destroy', 'uses' => 'MainController@destroy']);
		Route::get('coment', ['as' => 'main.coment', 'uses' => 'MainController@coment']);
		Route::get('form/{id}', ['as' => 'main.form', 'uses' => 'MainController@form']);
		Route::post('grava', ['as' => 'main.grava', 'uses' => 'MainController@grava']);
		Route::get('product/{id}/{cat}/{dest}', ['as' => 'main.product', 'uses' => 'MainController@product']);
		Route::put('{id}/update', ['as' => 'main.update', 'uses' => 'MainController@update']);
		Route::post('alojamento', ['as' => 'main.alojamento', 'uses' => 'MainController@alojamento']);
		Route::post('pdf', ['as' => 'main.pdf', 'uses' => 'MainController@pdf']);
		Route::post('search', ['as' => 'main.search', 'uses' => 'MainController@search']);
	});
});
