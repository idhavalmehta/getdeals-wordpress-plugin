( function( $ ) {

	'use strict';

	var search = {
		
		form: '.gd-search-form',
		results: '.gd-search-results',
		status: '.gd-search-status',

	};

	var credentials = {
		
		'GD-API-Email': '',
		'GD-API-Token': '',

	};

	var serializeData = function() {
		
		var data = $( search.form ).serialize();
		return data.replace( /&?[^=&]+=(&|$)/g, '' );

	};

	var getResults = function() {
		
		$.ajax( {
			method: 'GET',
			url: 'https://getdeals.co.in/api/v1/search',
			data: serializeData(),
			headers: credentials,
			beforeSend: function() {
				apiState( 'load' );
			}, success: function( results ) {
				apiState( 'done' );
				var html = '', n = results.length, data;
				for ( var i = 0; i < n; i++ ) {
					data = results[ i ][ 'data' ];
					html += appendResult( data );
				}
				$( search.results ).append( html );
				if ( n ) { apiState( 'more' ); } // n > 0
				else if ( $( '.gd-result' ).length ) { apiState( 'last' ); }
				else { apiState( 'none' ); } // no results found
			}, error: function() {
				apiState( 'error' );
			},
		} );

	};

	var getNextPage = function() {
		var input = $( search.form ).find( '.gd-search-field-page' ),
			page = parseInt( $( input ).val() ) + 1;
		$( input ).val( page ); // update input
		getResults(); // call GetDeals API
	};

	var apiState = function( state ) {
		
		$( search.status ).find( '[class^="gd-api-status-"]' ).hide();
		$( search.status ).find( '[class="gd-api-status-' + state + '"]' ).show();

	};

	var appendResult = function( data ) {
		
		var html = '';
		html += '<div class="gd-result">';
		html += '<div class="gd-result-image"><img src="' + data[ 'image' ] + '" alt="' + data[ 'title' ] + '" /></div>';
		html += '<div class="gd-result-info">';
		html += '<h3 class="gd-result-info-title">' + data[ 'title' ] + '</h3>';
		html += '<p class="gd-result-info-store">' + data[ 'store' ] + '</p>';
		if ( data[ 'price' ] ) {
			var price = data[ 'price' ];
			html += '<p class="gd-result-prices">';
			if ( price[ 'sale' ] ) {
				html += '<span class="gd-result-new-price">&#8377;' + price[ 'sale' ] + '</span>';
			}
			if ( price[ 'mrp' ] ) {
				html += '<span class="gd-result-old-price"><s>&#8377;' + price[ 'mrp' ] + '</s></span><span class="gd-result-discount">' + price[ 'discount' ] + '%</span>';
			}	
			html += '</p>';
		}
		html += '<p class="gd-result-action"><a href="' + data[ 'link' ] + '" target="_blank" class="gd-buy-button">BUY NOW</a></p>';
		html += '</div>'; // .gd-result-info
		html += '</div>'; // .gd-result
		return html;

	};

	$( function() {
		
		var search_query = window.location.search;
		
		search_query = $.unserialize( search_query.substring( 1 ) );
		$( search.form ).unserialize( search_query );
		
		if ( search_query[ 'q' ] ) { getResults(); }

		$( search.status ).on( 'click', '.gd-load-more-button', function() {
			getNextPage();
		} );

		$( search.status ).on( 'click', '.gd-try-again-button', function() {
			getResults();
		} );

		$( search.form ).on( 'submit', function( event ) {
			event.preventDefault();
			var action = $( search.form ).attr( 'action' ),
				query = $( search.form ).find( '.gd-search-field-q' ).serialize();
			if ( query.length ) { query = '?' + query; }
			window.location = action + query;
		} );

	} );

	window.getdeals = function( email, token ) {
		
		credentials[ 'GD-API-Email' ] = email;
		credentials[ 'GD-API-Token' ] = token;

	};

} )( jQuery );
