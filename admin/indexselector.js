document.addEventListener('DOMContentLoaded', function() {
	var modlistLink = document.getElementById('modlistLink');
	var operationsLink = document.getElementById('operationsLink');
	var condition = <?php echo ($condition ? 'true' : 'false'); ?>;

	modlistLink.addEventListener('click', function() {
			condition = true;
			loadContent();
	});

	operationsLink.addEventListener('click', function() {
			condition = false;
			loadContent();
	});

	function loadContent() {
			var container = document.querySelector('.container');

			while (container.firstChild) {
					container.removeChild(container.firstChild);
			}

			var fileToLoad = condition ? 'modlist.php' : 'operations.php';
			fetch(fileToLoad)
					.then(function(response) {
							return response.text();
					})
					.then(function(content) {
							container.innerHTML = content;
					});
	}
});
