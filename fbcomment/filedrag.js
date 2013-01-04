// some misc script things 
function toggleDiv($id) {
	var ele = document.getElementById($id);
	if(ele.style.display == "block") {
		ele.style.display = "none";
	}
	else {

		ele.style.position="absolute";
		if(typeof(window.pageYOffset)=='number') {
			ele.style.top = window.pageYOffset+"px";
			ele.style.left = window.pageXOffset+"px";
		}
		else {
			ele.style.top = document.documentElement.scrollTop+"px";
			ele.style.left = document.documentElement.scrollLeft+"px";
		}
		ele.style.display = "block";
	}
} 
function hideDiv($id) {
	var ele = document.getElementById($id);
	ele.style.display = "none";
} 

function getTitle($id) {
	var ele = document.getElementById($id);
	ele.value=document.title;
}

function getDescription($id) {
	var ele = document.getElementById($id);
	var description;
	var metas = document.getElementsByTagName('meta');
	for (var x=0,y=metas.length; x<y; x++) {
	   if (metas[x].name.toLowerCase() == "description") {
	      description = metas[x];
	   }
	}
	if (!(typeof description === 'undefined')) {
	    ele.value=description.content;
	}

}

/*
taken from
filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/

(function() {

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("fbc_dd_filedrag");
		m.innerHTML = msg + m.innerHTML;
	}


	function ClearOutput() {
		var m = $id("fbc_dd_filedrag");
		m.innerHTML = '';
		m = $id("fbc_dd_progress");
		m.innerHTML = '';
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			ParseFile(f);
			UploadFile(f);
		}

	}


	// output file information
	function ParseFile(file) {
		ClearOutput();
		Output(
			"<p><strong>" + file.name +
			"</strong> : <strong>" + file.type +
			"</strong> : <strong>" + file.size +
			"</strong> bytes</p>"
		);

		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong><br />" +
					'<img class="fbc_dd_image" src="' + e.target.result + '" /></p>'
				);
			}
			reader.readAsDataURL(file);
		}

	}

	// upload JPEG files
	function UploadFile(file) {
		// create progress bar
		var o = $id("fbc_dd_progress")
		var progress = o.appendChild(document.createElement("p"));
		progress.appendChild(document.createTextNode(file.name));
		
		if (file.size > $id("MAX_FILE_SIZE").value) {
			progress.className = "toobig";
			return;
		}
		var x_ogurl = $id("X_OGURL").value;
		
		var xhr = new XMLHttpRequest();
		if (xhr.upload && (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/gif")) {

			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);

			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failed");
				}
			};

			// start upload
			xhr.open("POST", $id("fbc_dd_upload").action, true);
			xhr.setRequestHeader("X_FILENAME", file.name);
			xhr.setRequestHeader("X_OGURL", x_ogurl);
			xhr.send(file);

		}

	}

	// Title changed
	function InputTitleHandler(e) {
		if(e.type == "focus") {
		  $id("fbc_dd_ogtitle").className = 'fbc_input_active';
		  return;
		}
		// fetch FileList object
		var x_ogtitle = $id("fbc_dd_ogtitle").value;
		var x_ogurl = $id("X_OGURL").value;
		
		var xhr = new XMLHttpRequest();
		// send data
		xhr.open("GET", $id("fbc_dd_upload").action, true);
		xhr.setRequestHeader("X_OGTITLE", x_ogtitle);
		xhr.setRequestHeader("X_OGURL", x_ogurl);
		xhr.send();
		$id("fbc_dd_ogtitle").className = 'fbc_input_ok';
	}

	function InputDescriptionHandler(e) {
	  
		if(e.type == "focus") {
		  $id("fbc_dd_ogdesc").className = 'fbc_input_active';
		  return;
		}
		// fetch FileList object
		var x_ogdesc = $id("fbc_dd_ogdesc").value;
		var x_ogurl = $id("X_OGURL").value;
		
		var xhr = new XMLHttpRequest();
		// send data
		xhr.open("GET", $id("fbc_dd_upload").action, true);
		xhr.setRequestHeader("X_OGDESC", x_ogdesc);
		xhr.setRequestHeader("X_OGURL", x_ogurl);
		xhr.send();
		$id("fbc_dd_ogdesc").className = 'fbc_input_ok';
		
	}

	// initialize
	function Init() {

		var fileselect = $id("fbc_dd_fileselect"),
			filedrag = $id("fbc_dd_filedrag"),
			submitbutton = $id("fbc_dd_submitbutton"),
			formtitle = $id("fbc_dd_ogtitle"),
			formdesc = $id("fbc_dd_ogdesc");

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";

			// remove submit button
			submitbutton.style.display = "none";
			$id("fbc_dd_nofiledrag").style.display="none";
			formtitle.addEventListener("change", InputTitleHandler, false);
			formtitle.addEventListener("focus", InputTitleHandler, false);
			formdesc.addEventListener("change", InputDescriptionHandler, false);
			formdesc.addEventListener("focus", InputDescriptionHandler, false);

		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


})();