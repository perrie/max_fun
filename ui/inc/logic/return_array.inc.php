<?php
// SOURCE: http://php.net/manual/en/mysqli-stmt.get-result.php
// AUTHOR: Anonymous
class iimysqli_result
{
    public $stmt, $nCols, $nRows, $values, $useVal;
}    

function iimysqli_stmt_get_result($stmt)
{
    /**    EXPLANATION:
     * We are creating a fake "result" structure to enable us to have
     * source-level equivalent syntax to a query executed via
     * mysqli_query().
     *
     *    $stmt = mysqli_prepare($conn, "");
     *    mysqli_bind_param($stmt, "types", ...);
     *
     *    $param1 = 0;
     *    $param2 = 'foo';
     *    $param3 = 'bar';
     *    mysqli_execute($stmt);
     *    $result _mysqli_stmt_get_result($stmt);
     *        [ $arr = _mysqli_result_fetch_array($result);
     *            || $assoc = _mysqli_result_fetch_assoc($result); ]
     *    mysqli_stmt_close($stmt);
     *    mysqli_close($conn);
     *
     * At the source level, there is no difference between this and mysqlnd.
     **/
    $metadata = mysqli_stmt_result_metadata($stmt);
    $ret = new iimysqli_result;
    if (!$ret) return NULL;

    $ret->nCols = mysqli_num_fields($metadata);
	$ret->nRows = mysqli_stmt_num_rows ($stmt);
    $ret->stmt = $stmt;
	$ret->values = [];
	$ret->useVal = false;

    mysqli_free_result($metadata);
    return $ret;
}

function iimysqli_result_fetch_array(&$result)
{
	if ($result->useVal) {
		$ret = current($result->values);
		next($result->values);
		if ($ret == NULL) {
			reset($result->values);
		}
		return $ret;
	}

    $ret = array();
    $code = "return mysqli_stmt_bind_result(\$result->stmt ";

    for ($i=0; $i<$result->nCols; $i++)
    {
        $ret[$i] = NULL;
        $code .= ", \$ret['" .$i ."']";
    };

    $code .= ");";
    if (!eval($code)) { return NULL; };

    // This should advance the "$stmt" cursor.
    if (!mysqli_stmt_fetch($result->stmt)) { 
		$result->useVal = true;
		iimysqli_stmt_free_result($result);
		return NULL; 
	}

    // Return the array we built.
	array_push($result->values, $ret);
    return $ret;
}

function iimysqli_stmt_free_result(&$result) {
	if ($result->stmt) {
		mysqli_stmt_free_result($result->stmt);
		$result->stmt = NULL;
	}
}

function iimysqli_reset(&$result) {
	iimysqli_stmt_free_result($result);
	reset($result->values);
}
?>