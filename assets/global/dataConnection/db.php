<?php
/**
 * THIS file is made for MS SQL server by Libaro
 * @since 0.71
 */
define( 'OBJECT', 'OBJECT' );
define( 'object', 'OBJECT' ); // Back compat.

/**
 * @since 2.5.0
 */
define( 'OBJECT_K', 'OBJECT_K' );

class db {

    /**
     * The ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     *
     * @since 0.71
     * @access public
     * @var int
     */
    var $insert_id = 0;

    /**
     * A textual description of the last query/get_row/get_var call
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    var $func_call;

    /**
     * MySQL result, which is either a resource or boolean.
     *
     * @since 0.71
     * @access protected
     * @var mixed
     */
    protected $result;

    /**
     * Database Handle
     *
     * @since 0.71
     * @access protected
     * @var string
     */
    protected $dbh;

    /**
     * Results of the last query made
     *
     * @since 0.71
     * @access private
     * @var array|null
     */
    var $last_result;


    function __construct( ) {
//
//        $this->dbuser = $dbuser;
//        $this->dbpassword = $dbpassword;
//        $this->dbname = $dbname;
//        $this->dbhost = $dbhost;

        $this->db_connect();
    }
    function db_connect() {
        $serverName = HOST;
        $connectionInfo = array( "Database"=>DATABASE, "UID"=>USER, "PWD"=>PASSWORD);

        /* Connect using Windows Authentication. */
        $this->dbh = sqlsrv_connect( $serverName, $connectionInfo);

        if( $this->dbh === false )
        {
            echo "Could not connect.\n";
            die( print_r( sqlsrv_errors(), true));
        }
        //uncomment to debug
//        if( $client_info = sqlsrv_client_info( $this->dbh)) {
//            foreach( $client_info as $key => $value) {
//                echo $key.": ".$value."<br />";
//            }
//        } else {
//            echo "Error in retrieving client info.<br />";
//        }
        return $this->dbh;
    }

    /**
     * Kill cached query results.
     *
     * @since 0.71
     * @return void
     */
    function flush() {
        $this->last_result = array();
        $this->col_info    = null;
        $this->last_query  = null;
        $this->rows_affected = $this->num_rows = 0;
        $this->last_error  = '';

        if ( is_resource( $this->result ) ) {
            sqlsrv_free_stmt( $this->result );
        }
    }

    function query($query) {
        /* Get the product picture for a given product ID. */
        //$tsql = "SELECT LargePhoto
        //         FROM Production.ProductPhoto AS p
        //         JOIN Production.ProductProductPhoto AS q
        //         ON p.ProductPhotoID = q.ProductPhotoID
        //         WHERE ProductID = ?";
        $tsql = $query;
        $return_val = 0;
        $params = array();
        $this->flush();

        // Log how the function was called
        $this->func_call = "\$db->query(\"$query\")";

        // Keep track of the last query for debug..
        $this->last_query = $query;

        /* Execute the query. */
        $this->result = sqlsrv_query($this->dbh , $tsql, $params);
        if( $this->result === false )
        {
            echo "Error in statement execution.</br>";
            echo $this->last_query ;
            echo "</br>";
            die( print_r( sqlsrv_errors(), true));
        }

        if ( preg_match( '/^\s*(create|alter|truncate|drop)\s/i', $query ) ) {
            $return_val = $this->result;
        } elseif ( preg_match( '/^\s*(insert|delete|update|replace)\s/i', $query ) ) {
            $this->rows_affected = sqlsrv_rows_affected( $this->result );
            // Take note of the insert_id
            if ( preg_match( '/^\s*(insert|replace)\s/i', $query ) ) {
                $this->insert_id = $this->getInsertedID();
            }
            // Return number of rows affected
            if($this->rows_affected != 0){
                $return_val = $this->rows_affected;
            }else{
                $return_val = $this->insert_id;
            }
            
        } else {
            $num_rows = 0;
            while ( $row = @sqlsrv_fetch_object( $this->result ) ) {
                $this->last_result[$num_rows] = $row;
                $num_rows++;
            }

            // Log number of rows the query returned
            // and return number of rows selected
            $this->num_rows = $num_rows;
            $return_val     = $num_rows;
        }

        /* Free the statement and connection resources. */
        //sqlsrv_free_stmt( $this->result );
        //sqlsrv_close( $this->dbh );
        return $return_val;
    }


    /**
     * Insert a row into a table.
     *
     * <code>
     * wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
     * wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
     * </code>
     *
     * @since 2.5.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string $table table name
     * @param array $data Data to insert (in column => value pairs). Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data. If string, that format will be used for all of the values in $data.
     * 	A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows inserted, or false on error.
     */
    function insert( $table, $data, $format = null ) {
        return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );
    }

    /**
     * Replace a row into a table.
     *
     * <code>
     * wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
     * wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
     * </code>
     *
     * @since 3.0.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string $table table name
     * @param array $data Data to insert (in column => value pairs). Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data. If string, that format will be used for all of the values in $data.
     * 	A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows affected, or false on error.
     */
    function replace( $table, $data, $format = null ) {
        return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
    }

    /**
     * Helper function for insert and replace.
     *
     * Runs an insert or replace query based on $type argument.
     *
     * @access private
     * @since 3.0.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string $table table name
     * @param array $data Data to insert (in column => value pairs). Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data. If string, that format will be used for all of the values in $data.
     * 	A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @param string $type Optional. What type of operation is this? INSERT or REPLACE. Defaults to INSERT.
     * @return int|false The number of rows affected, or false on error.
     */
    function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
        if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) )
            return false;
        $this->insert_id = 0;
        $formats = $format = (array) $format;
        $fields = array_keys( $data );
        $formatted_fields = array();
        foreach ( $fields as $field ) {
            if ( !empty( $format ) )
                $form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
            elseif ( isset( $this->field_types[$field] ) )
                $form = $this->field_types[$field];
            else
                $form = '%s';
            $formatted_fields[] = $form;
        }
        $sql = "{$type} INTO $table (" . implode( ',', $fields ) . ") VALUES (" . implode( ",", $formatted_fields ) . ")";
        return $this->query( $this->prepare( $sql, $data ) );
    }

    /**
     * Update a row in the table
     *
     * <code>
     * wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
     * wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
     * </code>
     *
     * @since 2.5.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string $table table name
     * @param array $data Data to update (in column => value pairs). Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     * @param array $where A named array of WHERE clauses (in column => value pairs). Multiple clauses will be joined with ANDs. Both $where columns and $where values should be "raw".
     * @param array|string $format Optional. An array of formats to be mapped to each of the values in $data. If string, that format will be used for all of the values in $data.
     * 	A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where. If string, that format will be used for all of the items in $where. A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $where will be treated as strings.
     * @return int|false The number of rows updated, or false on error.
     */
    function update( $table, $data, $where, $format = null, $where_format = null ) {
        if ( ! is_array( $data ) || ! is_array( $where ) )
            return false;

        $formats = $format = (array) $format;
        $bits = $wheres = array();
        foreach ( (array) array_keys( $data ) as $field ) {
            if ( !empty( $format ) )
                $form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
            elseif ( isset($this->field_types[$field]) )
                $form = $this->field_types[$field];
            else
                $form = '%s';
            $bits[] = "$field = {$form}";
        }

        $where_formats = $where_format = (array) $where_format;
        foreach ( (array) array_keys( $where ) as $field ) {
            if ( !empty( $where_format ) )
                $form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
            elseif ( isset( $this->field_types[$field] ) )
                $form = $this->field_types[$field];
            else
                $form = '%s';
            $wheres[] = "$field = {$form}";
        }

        $sql = "UPDATE $table SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
        return $this->query( $this->prepare( $sql, array_merge( array_values( $data ), array_values( $where ) ) ) );
    }

    /**
     * Delete a row in the table
     *
     * <code>
     * wpdb::delete( 'table', array( 'ID' => 1 ) )
     * wpdb::delete( 'table', array( 'ID' => 1 ), array( '%d' ) )
     * </code>
     *
     * @since 3.4.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string $table table name
     * @param array $where A named array of WHERE clauses (in column => value pairs). Multiple clauses will be joined with ANDs. Both $where columns and $where values should be "raw".
     * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where. If string, that format will be used for all of the items in $where. A format is one of '%d', '%f', '%s' (integer, float, string). If omitted, all values in $where will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows updated, or false on error.
     */
    function delete( $table, $where, $where_format = null ) {
        if ( ! is_array( $where ) )
            return false;

        $bits = $wheres = array();

        $where_formats = $where_format = (array) $where_format;

        foreach ( array_keys( $where ) as $field ) {
            if ( !empty( $where_format ) ) {
                $form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
            } elseif ( isset( $this->field_types[ $field ] ) ) {
                $form = $this->field_types[ $field ];
            } else {
                $form = '%s';
            }

            $wheres[] = "$field = $form";
        }

        $sql = "DELETE FROM $table WHERE " . implode( ' AND ', $wheres );
        return $this->query( $this->prepare( $sql, $where ) );
    }


    /**
     * Retrieve one variable from the database.
     *
     * Executes a SQL query and returns the value from the SQL result.
     * If the SQL result contains more than one column and/or more than one row, this function returns the value in the column and row specified.
     * If $query is null, this function returns the value in the specified column and row from the previous SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query Optional. SQL query. Defaults to null, use the result from the previous query.
     * @param int $x Optional. Column of value to return. Indexed from 0.
     * @param int $y Optional. Row of value to return. Indexed from 0.
     * @return string|null Database query result (as string), or null on failure
     */
    function get_var( $query = null, $x = 0, $y = 0 ) {
        $this->func_call = "\$db->get_var(\"$query\", $x, $y)";
        if ( $query )
            $this->query( $query );

        // Extract var out of cached results based x,y vals
        if ( !empty( $this->last_result[$y] ) ) {
            $values = array_values( get_object_vars( $this->last_result[$y] ) );
        }

        // If there is a value return it else return null
        return ( isset( $values[$x] ) && $values[$x] !== '' ) ? $values[$x] : null;
    }

    /**
     * Retrieve one row from the database.
     *
     * Executes a SQL query and returns the row from the SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query SQL query.
     * @param string $output Optional. one of ARRAY_A | ARRAY_N | OBJECT constants. Return an associative array (column => value, ...),
     * 	a numerically indexed array (0 => value, ...) or an object ( ->column = value ), respectively.
     * @param int $y Optional. Row to return. Indexed from 0.
     * @return mixed Database query result in format specified by $output or null on failure
     */
    function get_row( $query = null, $output = OBJECT, $y = 0 ) {
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";
        if ( $query )
            $this->query( $query );
        else
            return null;

        if ( !isset( $this->last_result[$y] ) )
            return null;

        if ( $output == OBJECT ) {
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        } elseif ( $output == ARRAY_A ) {
            return $this->last_result[$y] ? get_object_vars( $this->last_result[$y] ) : null;
        } elseif ( $output == ARRAY_N ) {
            return $this->last_result[$y] ? array_values( get_object_vars( $this->last_result[$y] ) ) : null;
        } elseif ( strtoupper( $output ) === OBJECT ) {
            // Back compat for OBJECT being previously case insensitive.
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        } else {
            $this->print_error( " \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N" );
        }
    }

    /**
     * Retrieve one column from the database.
     *
     * Executes a SQL query and returns the column from the SQL result.
     * If the SQL result contains more than one column, this function returns the column specified.
     * If $query is null, this function returns the specified column from the previous SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query Optional. SQL query. Defaults to previous query.
     * @param int $x Optional. Column to return. Indexed from 0.
     * @return array Database query result. Array indexed from 0 by SQL result row number.
     */
    function get_col( $query = null , $x = 0 ) {
        if ( $query )
            $this->query( $query );

        $new_array = array();
        // Extract the column values
        for ( $i = 0, $j = count( $this->last_result ); $i < $j; $i++ ) {
            $new_array[$i] = $this->get_var( null, $x, $i );
        }
        return $new_array;
    }

    /**
     * Retrieve an entire SQL result set from the database (i.e., many rows)
     *
     * Executes a SQL query and returns the entire SQL result.
     *
     * @since 0.71
     *
     * @param string $query SQL query.
     * @param string $output Optional. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants. With one of the first three, return an array of rows indexed from 0 by SQL result row number.
     * 	Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.
     * 	With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value. Duplicate keys are discarded.
     * @return mixed Database query results
     */
    function get_results( $query = null, $output = OBJECT ) {
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        if ( $query )
            $this->query( $query );
        else
            return null;

        $new_array = array();
        if ( $output == OBJECT ) {
            // Return an integer-keyed array of row objects
            return $this->last_result;
        } elseif ( $output == OBJECT_K ) {
            // Return an array of row objects with keys from column 1
            // (Duplicates are discarded)
            foreach ( $this->last_result as $row ) {
                $var_by_ref = get_object_vars( $row );
                $key = array_shift( $var_by_ref );
                if ( ! isset( $new_array[ $key ] ) )
                    $new_array[ $key ] = $row;
            }
            return $new_array;
        } elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
            // Return an integer-keyed array of...
            if ( $this->last_result ) {
                foreach( (array) $this->last_result as $row ) {
                    if ( $output == ARRAY_N ) {
                        // ...integer-keyed row arrays
                        $new_array[] = array_values( get_object_vars( $row ) );
                    } else {
                        // ...column name-keyed row arrays
                        $new_array[] = get_object_vars( $row );
                    }
                }
            }
            return $new_array;
        } elseif ( strtoupper( $output ) === OBJECT ) {
            // Back compat for OBJECT being previously case insensitive.
            return $this->last_result;
        }
        return null;
    }
    /**
     * Starts the timer, for debugging purposes.
     *
     * @since 1.5.0
     *
     * @return true
     */
    function timer_start() {
        $this->time_start = microtime( true );
        return true;
    }

    /**
     * Stops the debugging timer.
     *
     * @since 1.5.0
     *
     * @return float Total time spent on the query, in seconds
     */
    function timer_stop() {
        return ( microtime( true ) - $this->time_start );
    }

    /**
     * Prepares a SQL query for safe execution. Uses sprintf()-like syntax.
     *
     * The following directives can be used in the query format string:
     *   %d (integer)
     *   %f (float)
     *   %s (string)
     *   %% (literal percentage sign - no argument needed)
     *
     * All of %d, %f, and %s are to be left unquoted in the query string and they need an argument passed for them.
     * Literals (%) as parts of the query must be properly written as %%.
     *
     * This function only supports a small subset of the sprintf syntax; it only supports %d (integer), %f (float), and %s (string).
     * Does not support sign, padding, alignment, width or precision specifiers.
     * Does not support argument numbering/swapping.
     *
     * May be called like {@link http://php.net/sprintf sprintf()} or like {@link http://php.net/vsprintf vsprintf()}.
     *
     * Both %d and %s should be left unquoted in the query string.
     *
     * <code>
     * wpdb::prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", 'foo', 1337 )
     * wpdb::prepare( "SELECT DATE_FORMAT(`field`, '%%c') FROM `table` WHERE `column` = %s", 'foo' );
     * </code>
     *
     * @link http://php.net/sprintf Description of syntax.
     * @since 2.3.0
     *
     * @param string $query Query statement with sprintf()-like placeholders
     * @param array|mixed $args The array of variables to substitute into the query's placeholders if being called like
     * 	{@link http://php.net/vsprintf vsprintf()}, or the first variable to substitute into the query's placeholders if
     * 	being called like {@link http://php.net/sprintf sprintf()}.
     * @param mixed $args,... further variables to substitute into the query's placeholders if being called like
     * 	{@link http://php.net/sprintf sprintf()}.
     * @return null|false|string Sanitized query string, null if there is no query, false if there is an error and string
     * 	if there was something to prepare
     */
    function prepare( $query, $args ) {
        if ( is_null( $query ) )
            return;

        // This is not meant to be foolproof -- but it will catch obviously incorrect usage.
        if ( strpos( $query, '%' ) === false ) {
            _doing_it_wrong( 'wpdb::prepare', sprintf( __( 'The query argument of %s must have a placeholder.' ), 'wpdb::prepare()' ), '3.9' );
        }

        $args = func_get_args();
        array_shift( $args );
        // If args were passed as an array (as in vsprintf), move them up
        if ( isset( $args[0] ) && is_array($args[0]) )
            $args = $args[0];
        $query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
        $query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
        $query = preg_replace( '|(?<!%)%f|' , '%F', $query ); // Force floats to be locale unaware
        $query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
//        array_walk( $args, array( $this, 'escape_by_ref' ) );
        return @vsprintf( $query, $args );
    }

    function getInsertedID() {
        return $this->get_var('SELECT SCOPE_IDENTITY() AS ins_id');
    }

    function logEvent($type, $description, $userID = -1, $uri = ''){
        $success = $this->insert(
            'tblChangeLogs',
            array(
                'DateTime' => date('Y-m-d H:i:s'),
                'Type' => $type,
                'UserID' => $userID,
                'Description' => $description,
                'URI' => $uri
            )
        );
        return $success;
    }

}
?>