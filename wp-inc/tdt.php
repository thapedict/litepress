<?php
/**
 *	@package tdt
 *	@author	Moeti T
 *	@version 1.0.0
 *	@license MIT
 *	@description
 *		those miscellaneous function you wished php had and/or those custom ways you wished php implemented some of their functions
 */
 
 
/**
 *	getting file content without the php code. a sort of merge between file_get_contents & require all-in-one
 *	files are buffered to prevent unwanted outputs
 *
 *	@since 1.0.0
 *
 *	@param filename to require
 *	@param [optional] array with local variables you want the file to have
 *	@return string with buffered file content
 */
if( ! function_exists( 'tdt_fgc' ) ) {
	function tdt_fgc( $file, array $data = null ) {
		ob_start();
		
		if( is_array( $data ) )
			extract( $data );
		
		require $file; // we require to throw an error if file not found. maybe I should look to prevent this???
		
		return ob_get_clean();
	}
}

/**
 *	searches for an value in a 2D array
 *
 *	@since 1.0.0
 *	
 *	@param array to search through
 *	@param key that is to be checked
 *	@param value to find
 *	@param [optional] return first found or not?
 *	@param [optional] to use strict search?
 *	@return 1. false if nothing matches
 *			2. the corresponding key if one is set to true
 *			2. an array with keys that match (might be just one)
 */
if( ! function_exists( 'tdt_in_array' ) ) {
	function tdt_array_search( array $array, $key, $value, $one = false, $strict = false ) {
		$found = array();
		
		foreach( $array as $k => $v ) {
			if( ! is_array( $v ) )
				continue;
			
			if( $strict ) {
				if( $v[ $key ] === $value ) {
					$found[] = $k;

					if( $one )
						break;
				}			
			} else {
				if( $v[ $key ] == $value ) {
					$found[] = $k;

					if( $one )
						break;			
				}			
			}
		}
		
		if( empty( $found ) )
			return false;
		
		if( $one )
			return $found[ 0 ];
		
		return $found;
	}	
}

/**
 *	filters (flattens) a 2D array to a array with only values with certain key
 *
 *	@since 1.0.0
 *
 *	@param array to get values from
 *	@param string or int as key
 *	@return array with values
 */
if( ! function_exists( 'tdt_array_get' ) ) {
	function tdt_array_get( array $array, $key ) {
		$filter = array();
		
		foreach( $array as $v ) {
			$temp = (array) $v;
			
			if( isset( $temp[ $key ] ) )
				$filter[] = $temp[ $key ];
		}
		
		return $filter;
	}
}

/**
 *	filters array with certain key & value pair
 *
 *	@since 1.0.0
 *
 *	@uses tdt_array_match
 *
 *	@param array to filter
 *	@param array with key/value pairs. i.e. key => value
 *	@return array with only elements that have the key/value pairs
 */
function tdt_array_where( array $array, array $where, $operator = '==' ) {
	foreach( $where as $k => $v ) {
		$array = tdt_array_match( $array, $k, $v, $operator );
	}
	
	return $array;
}

/**
 *	filters array with certain key and value
 *
 *	@since 1.0.0
 *
 *	@param array to filter
 *	@param the key to match
 *	@param the value to match
 *	@param [optional] the comparison operator to use
 *	@return array with only elements that have matched
 */
function tdt_array_match( array $array, $key, $value, $comparison = '==' ){
	$matched = array();
	
	foreach( $array as $a ) {
		if( ! is_array( $a ) && ! is_object( $a ) )
			continue; // we should maybe throw an error ????
		
		// because we expecting an array or an object
		// we will temporarily convert to array (if it already isn't) and run comparison
		$temp = (array) $a;
		
		if( isset( $temp[ $key ] ) ) {
			if( $comparison == '==' && $temp[ $key ] == $value )
				$matched[] = $a;
			elseif( $comparison == '===' && $temp[ $key ] === $value )
				$matched[] = $a;
			elseif( $comparison == '!=' && $temp[ $key ] != $value )
				$matched[] = $a;
			elseif( $comparison == '!==' && $temp[ $key ] !== $value )
				$matched[] = $a;
			elseif( $comparison == '>=' && $temp[ $key ] >= $value )
				$matched[] = $a;
			elseif( $comparison == '<=' && $temp[ $key ] <= $value )
				$matched[] = $a;
			elseif( $comparison == '>' && $temp[ $key ] > $value )
				$matched[] = $a;
			elseif( $comparison == '<' && $temp[ $key ] < $value )
				$matched[] = $a;
			elseif( $comparison == 'like' && stripos( $temp[ $key ], $value ) !== false )
				$matched[] = $a;
			elseif( $comparison == 'in' && is_array( $value ) && in_array( $temp[ $key ], $value ) )
				$matched[] = $a;
		}
	}
	
	return $matched;
}

// because php's array_unshift passes by reference, and sometimes we don't want that
function tdt_array_unshift( array $array, $value ) {
	$args = func_get_args();
	array_shift( $args );
	
	return array_merge( $args, $array );
}

// because php's array_unique is only good for string values
function tdt_array_unique( $array ) {
	$filtered = array();
	
	foreach( $array as $a ) {
		// if its not in filtered array, add it
		if( ! in_array( $a, $filtered ) )
			$filtered[] = $a;
	}
	
	return $filtered;
}


