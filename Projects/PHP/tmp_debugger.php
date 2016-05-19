<?php
// 日本語UTF-8, LF



// include


// define
define( "STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT", "default" );
define( "STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT_ERROR", "default" );
define( "STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE", "file" );
define( "STRING__TMP_DEBUGGER__OUTPUT_TYPE__BROWSER", "browser" );
define( "INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__OUTPUT_TYPE", 0 );
define( "INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__FILE_NAME", 1 );


// global
$g_TMP_DEBUGGER__aaStrings_DebugTypeData = array();
$g_TMP_DEBUGGER__aaStrings_DebugTypeData[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, "./debug.txt" );
$g_TMP_DEBUGGER__aaStrings_DebugTypeData[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT_ERROR] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, "./error.txt" );
$g_TMP_DEBUGGER__aaStrings_OutputBuffer = array();
$g_TMP_DEBUGGER__aaStrings_OutputBuffer[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT] = "";
$g_TMP_DEBUGGER__aaStrings_OutputBuffer[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT_ERROR] = "";



// ===================================================================
// public
// -------------------------------------------------------------------

// subject  : set debug file name
// argument : string, filename
// argument : string, debug type
// argument : bool, force clear flag
function TMP_DEBUGGER__SetDebugFileName( $eString_FileName, $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT, $bFlag_Force = FALSE )
{
	global $g_TMP_DEBUGGER__aaStrings_DebugTypeData;

	$g_TMP_DEBUGGER__aaStrings_DebugTypeData[$eString_DebugType] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, $eString_FileName );
	local_TMP_DEBUGGER__InitializeOutputBuffer( $eString_DebugType, $bFlag_Force );
}


// subject  : set output type
// argument : string, output type
// argument : string, debug type
// argument : bool, force clear flag
function TMP_DEBUGGER__SetOutputType( $eString_OutputType, $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT, $bFlag_Force = FALSE )
{
	global $g_TMP_DEBUGGER__aaStrings_DebugTypeData;

	$g_TMP_DEBUGGER__aaStrings_DebugTypeData[$eString_DebugType] = array( $eString_OutputType );
	local_TMP_DEBUGGER__InitializeOutputBuffer( $eString_DebugType, $bFlag_Force );
}


// subject  : debug output
// argument : string, text
// argument : string, debug type
// argument : hash, options
function TMP_DEBUGGER__DebugOutput( $eString_Message, $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT, $aaOptions = NULL )
{
	$arStrings_DebugType = local_TMP_DEBUGGER__GetDebugTypeData( $eString_DebugType );
	switch ( $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__OUTPUT_TYPE] )
	{
		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__BROWSER:
			local_TMP_DEBUGGER__AddBrowser( $eString_DebugType, $eString_Message, $aaOptions );
			break;

		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE:
			local_TMP_DEBUGGER__AddFile( $eString_DebugType, $eString_Message, $aaOptions );
			break;
	}
}


// subject  : debug output ( output buffer )
// argument : string, debug type
function TMP_DEBUGGER__DebugOutput_OutputBuffer( $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT )
{
	$arStrings_DebugType = local_TMP_DEBUGGER__GetDebugTypeData( $eString_DebugType );
	switch ( $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__OUTPUT_TYPE] )
	{
		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__BROWSER:
			local_TMP_DEBUGGER__AddBrowser_OutputBuffer( $eString_DebugType );
			break;

		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE:
			local_TMP_DEBUGGER__AddFile_OutputBuffer( $eString_DebugType );
			break;
	}
}



// ===================================================================
// local
// -------------------------------------------------------------------

// -------------------------------------------------------------------
// browser

// subject  : add browser
// argument : string, debug type
// argument : string, text
// argument : hash, options
function local_TMP_DEBUGGER__AddBrowser( $eString_DebugType, $eString_Data, $aaOptions )
{
	if ( $aaOptions === NULL )
	{
		$aaOptions = array();
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "Add-LineFeed", TRUE, TRUE ) === TRUE )
	{
		$eString_Data .= "\n";
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "Replace-LineFeed", TRUE, TRUE ) === TRUE )
	{
		$eString_Data = str_replace( "\n", "<br />\n", $eString_Data );
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "RealTime", TRUE, TRUE ) === TRUE )
	{
		echo $eString_Data;
	}
	else
	{
		local_TMP_DEBUGGER__AddOutputBuffer( $eString_DebugType, $eString_Data );
	}
}


// subject  : add browser
// argument : string, debug type
function local_TMP_DEBUGGER__AddBrowser_OutputBuffer( $eString_DebugType )
{
	echo local_TMP_DEBUGGER__GetOutputBuffer( $eString_DebugType );
}


// -------------------------------------------------------------------
// file

// subject  : add file
// argument : string, debug type
// argument : string, text
// return   : bool, state
function local_TMP_DEBUGGER__AddFile( $eString_DebugType, $eString_Data, $aaOptions )
{
	$bResult = FALSE;

	if ( $aaOptions === NULL )
	{
		$aaOptions = array();
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "Add-DateTime", TRUE, TRUE ) === TRUE )
	{
		$eString_Data = date( "Y-m-d H:i:s ( O )" ) . "\t" . $eString_Data;
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "Add-LineFeed", TRUE, TRUE ) === TRUE )
	{
		$eString_Data .= "\n";
	}

	if ( local_TMP_DEBUGGER__CheckData( $aaOptions, "RealTime", TRUE, TRUE ) === TRUE )
	{
		$arStrings_DebugType = local_TMP_DEBUGGER__GetDebugTypeData( $eString_DebugType );
		$eString_FullName = $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__FILE_NAME];
		$bResult = local_TMP_DEBUGGER__AddFile_Direct( $eString_FullName, $eString_Data );
	}
	else
	{
		local_TMP_DEBUGGER__AddOutputBuffer( $eString_DebugType, $eString_Data );

		$bResult = TRUE;
	}

	return $bResult;
}


// subject  : add file
// argument : string, file name
// argument : string, text
// return   : bool, state
function local_TMP_DEBUGGER__AddFile_Direct( $eString_FullName, $eString_Data )
{
	$bResult = FALSE;
	$hFile = fopen( $eString_FullName, "a+" );
	if ( $hFile )
	{
		if ( flock( $hFile, LOCK_EX ) )
		{
			fwrite( $hFile, $eString_Data );
			flock( $hFile, LOCK_UN );

			$bResult = TRUE;
		}

		fclose( $hFile );
	}

	return $bResult;
}


// subject  : add file
// argument : string, debug type
function local_TMP_DEBUGGER__AddFile_OutputBuffer( $eString_DebugType )
{
	$arStrings_DebugType = local_TMP_DEBUGGER__GetDebugTypeData( $eString_DebugType );
	$eString_FullName = $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__FILE_NAME];
	$bResult = local_TMP_DEBUGGER__AddFile_Direct( $eString_FullName, local_TMP_DEBUGGER__GetOutputBuffer( $eString_DebugType ) );
}


// -------------------------------------------------------------------
// output buffer

// subject  : add output buffer
// argument : string, debug type
// argument : string, text
function local_TMP_DEBUGGER__AddOutputBuffer( $eString_DebugType, $eString_Data )
{
	global $g_TMP_DEBUGGER__aaStrings_OutputBuffer;

	$g_TMP_DEBUGGER__aaStrings_OutputBuffer[$eString_DebugType] .= $eString_Data;
}


// subject  : get output buffer
// argument : string, debug type
// return   : string, output buffer
function local_TMP_DEBUGGER__GetOutputBuffer( $eString_DebugType )
{
	global $g_TMP_DEBUGGER__aaStrings_OutputBuffer;

	return $g_TMP_DEBUGGER__aaStrings_OutputBuffer[$eString_DebugType];
}


// subject  : initialize output buffer
// argument : string, debug type
// argument : bool, force clear flag
function local_TMP_DEBUGGER__InitializeOutputBuffer( $eString_DebugType, $bFlag_Force = FALSE )
{
	global $g_TMP_DEBUGGER__aaStrings_OutputBuffer;

	if ( !array_key_exists( $eString_DebugType, $g_TMP_DEBUGGER__aaStrings_OutputBuffer ) )
	{
		$g_TMP_DEBUGGER__aaStrings_OutputBuffer[$eString_DebugType] = "";
	}
	else
	{
		if ( $bFlag_Force === TRUE )
		{
			$g_TMP_DEBUGGER__aaStrings_OutputBuffer[$eString_DebugType] = "";
		}
	}
}


// -------------------------------------------------------------------
// etc.

// subject  : check data
// argument : hash, data
// argument : string, key
// argument : object, value
// argument : bool, default result
// argument : bool, TRUE or not
function local_TMP_DEBUGGER__CheckData( $aaData, $eString_Key, $eValue = TRUE, $bResult_Default = FALSE )
{
	$bResult = FALSE;

	if ( array_key_exists( $eString_Key, $aaData ) )
	{
		if ( $aaData[$eString_Key] === $eValue )
		{
			$bResult = TRUE;
		}
	}
	else
	{
		$bResult = $bResult_Default;
	}

	return $bResult;
}


// subject  : get debug type data
// argument : string, debug type
// return   : array, debug type data
function local_TMP_DEBUGGER__GetDebugTypeData( $eString_DebugType )
{
	global $g_TMP_DEBUGGER__aaStrings_DebugTypeData;

	if ( !array_key_exists( $eString_DebugType, $g_TMP_DEBUGGER__aaStrings_DebugTypeData ) )
	{
		$eString_DebugType = "default_error";
	}

	return $g_TMP_DEBUGGER__aaStrings_DebugTypeData[$eString_DebugType];
}



?>