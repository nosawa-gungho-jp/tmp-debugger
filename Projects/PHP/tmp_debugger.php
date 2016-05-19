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
$g_TMP_DEBUGGER__aaStrings_DebugType = array();
$g_TMP_DEBUGGER__aaStrings_DebugType[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, "./debug.txt" );
$g_TMP_DEBUGGER__aaStrings_DebugType[STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT_ERROR] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, "./error.txt" );
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
	global $g_TMP_DEBUGGER__aaStrings_DebugType;

	$g_TMP_DEBUGGER__aaStrings_DebugType[$eString_DebugType] = array( STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE, $eString_FileName );
	local_TMP_DEBUGGER__InitializeOutputBuffer( $eString_DebugType, $bFlag_Force );
}


// subject  : set output type
// argument : string, output type
// argument : string, debug type
// argument : bool, force clear flag
function TMP_DEBUGGER__SetOutputType( $eString_OutputType, $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT, $bFlag_Force = FALSE )
{
	global $g_TMP_DEBUGGER__aaStrings_DebugType;

	$g_TMP_DEBUGGER__aaStrings_DebugType[$eString_DebugType] = array( $eString_OutputType );
	local_TMP_DEBUGGER__InitializeOutputBuffer( $eString_DebugType, $bFlag_Force );
}


// subject  : debug output
// argument : string, text
// argument : string, debug type
// argument : hash, options
function TMP_DEBUGGER__DebugOutput( $eString_Message, $eString_DebugType = STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT, $aaOptions = NULL )
{
	global $g_TMP_DEBUGGER__aaStrings_DebugType;

	if ( !array_key_exists( $eString_DebugType, $g_TMP_DEBUGGER__aaStrings_DebugType ) )
	{
		$eString_DebugType = "default_error";
	}

	$arStrings_DebugType = $g_TMP_DEBUGGER__aaStrings_DebugType[$eString_DebugType];
	switch ( $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__OUTPUT_TYPE] )
	{
		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__BROWSER:
			local_TMP_DEBUGGER__AddBrowser( $eString_DebugType, $eString_Message, $aaOptions );
			break;

		case STRING__TMP_DEBUGGER__OUTPUT_TYPE__FILE:
			local_TMP_DEBUGGER__AddFile( $arStrings_DebugType[INTEGER__TMP_DEBUGGER__DEBUG_TYPE__COLUMN__FILE_NAME], date( "Y-m-d H:i:s ( O )" ) . "\t" . $eString_Message . "\n" );
			break;
	}
}



// ===================================================================
// local
// -------------------------------------------------------------------

// subject  : add file
// argument : string, debug type
// argument : string, text
// argument : hash, options
function local_TMP_DEBUGGER__AddBrowser( $eString_DebugType, $eString_Data, $aaOptions )
{
	if ( $aaOptions === NULL )
	{
		$aaOptions = array();
		$aaOptions["RealTime"] = TRUE;
		$aaOptions["Replace-LineFeed"] = TRUE;
		$aaOptions["Add-LineFeed"] = TRUE;
	}

	if ( $aaOptions["Add-LineFeed"] === TRUE )
	{
		$eString_Data .= "\n";
	}

	if ( $aaOptions["Replace-LineFeed"] === TRUE )
	{
		$eString_Data = str_replace( "\n", "<br />\n", $eString_Data );
	}

	if ( $aaOptions["RealTime"] === TRUE )
	{
		echo $eString_Data;
	}
	else
	{
		local_TMP_DEBUGGER__AddOutputBuffer( $eString_DebugType, $eString_Data );
	}
}


// subject  : add file
// argument : string, filename
// argument : string, text
// return   : bool, state
function local_TMP_DEBUGGER__AddFile( $eString_FileName, $eString_Data )
{
	$bResult = FALSE;
	$hFile = fopen( $eString_FileName, "a+" );
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


// subject  : add output buffer
// argument : string, debug type
// argument : string, text
function local_TMP_DEBUGGER__AddOutputBuffer( $eString_DebugType, $eString_Data )
{
	global $g_TMP_DEBUGGER__aaStrings_OutputBuffer;

	$g_TMP_DEBUGGER__aaStrings_OutputBuffer[$eString_DebugType] .= $eString_Data;
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



?>