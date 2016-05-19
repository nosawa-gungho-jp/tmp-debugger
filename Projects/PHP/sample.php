<?php
// 日本語UTF-8, LF



// include
require_once( "tmp_debugger.php" );


// define
define( "DEBUG_TYPE__TEST__FILE", "file" );
define( "DEBUG_TYPE__TEST__WEB", "web" );


// global



// ===================================================================
// main
// -------------------------------------------------------------------

{
	Main();

	exit;
}
function Main()
{
	$eString_HTML = "";

	TMP_DEBUGGER__DebugOutput( "Test.Default" );
	TMP_DEBUGGER__DebugOutput( "Test.Default-Error", STRING__TMP_DEBUGGER__DEBUG_TYPE__DEFAULT_ERROR );

	TMP_DEBUGGER__SetDebugFileName( "./test-file.txt", DEBUG_TYPE__TEST__FILE );
	TMP_DEBUGGER__SetOutputType( STRING__TMP_DEBUGGER__OUTPUT_TYPE__BROWSER, DEBUG_TYPE__TEST__WEB );

	$aaOptions = array();
	$aaOptions["RealTime"] = FALSE;
	TMP_DEBUGGER__DebugOutput( "Test.File.Buffer.1", DEBUG_TYPE__TEST__FILE, $aaOptions );
	TMP_DEBUGGER__DebugOutput( "Test.File.Buffer.2", DEBUG_TYPE__TEST__FILE, $aaOptions );
	TMP_DEBUGGER__DebugOutput( "Test.File.Buffer.3", DEBUG_TYPE__TEST__FILE, $aaOptions );

	TMP_DEBUGGER__DebugOutput( "Test.Web.Buffer.1", DEBUG_TYPE__TEST__WEB, $aaOptions );
	TMP_DEBUGGER__DebugOutput( "Test.Web.Buffer.2", DEBUG_TYPE__TEST__WEB, $aaOptions );
	TMP_DEBUGGER__DebugOutput( "Test.Web.Buffer.3", DEBUG_TYPE__TEST__WEB, $aaOptions );

	TMP_DEBUGGER__DebugOutput( "Test.File", DEBUG_TYPE__TEST__FILE );
	TMP_DEBUGGER__DebugOutput( "Test.Web", DEBUG_TYPE__TEST__WEB );

	TMP_DEBUGGER__DebugOutput_OutputBuffer( DEBUG_TYPE__TEST__FILE );
	TMP_DEBUGGER__DebugOutput_OutputBuffer( DEBUG_TYPE__TEST__WEB );
}


?>