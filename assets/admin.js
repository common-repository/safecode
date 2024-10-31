( function() {
	let settings = wp.codeEditor.defaultSettings;
	settings.codemirror.mode = 'php';
	settings.codemirror.viewportMargin = Infinity;
	editor = wp.codeEditor.initialize( document.getElementById( 'custom-functions' ), settings );
} )();
