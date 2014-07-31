/*$('#pdfGenerator').click(function() {
	console.log('pdfGenerator');
	PdfGeneratorTask.add();
})*/

var PdfGeneratorTask = {
	id: 0,
	taskId: 0,
	status: -1,
	statuses: {
		unstarted: 0,
		running: 2,
		finished: 3
	},
	code: -1,
	codes: {
		ok: 0,
		error: -1
	},
	cacheDir: '/cache/pdf/',
	ext: '.pdf',
	name: '',
	curl: encodeURIComponent(document.location.pathname + document.location.search),
	add: function() {
		if (this.id != 0) {
			return false;
		}
		this.setStyles();
		this.request('generateTask?curl=' + this.curl);
		this.checkStatus();
	},
	checkStatus: function() {
		if (this.status == this.statuses.unstarted) {
			this.setStyles();
			this.id = setInterval(this.request, 15000, 'getGenerateStatus?taskId=' + this.taskId);
		}
	},
	clearInterval: function() {
		if (this.id == 0) {
			return false;
		}
		clearInterval(this.id);
		this.id = 0;
		return true;
	},
	request: function(method) {
		$.ajax({
			type: 'GET',
			async: false,
			url: '/PdfGenerator/' + method,
			success: function(data) {
				PdfGeneratorTask.processResponse(data);
			}
		});
	},
	processResponse: function(data) {
		data = JSON.parse(data);
		if (typeof data.name != 'undefined') {
			this.name = data.name;
		}
		if (typeof data.taskId != 'undefined') {
			this.taskId = data.taskId;
		}
		this.changeStatusAndCode(data.status, data.code);
	},
	changeStatusAndCode: function(status, code) {
		if (this.status == status) {
			return false;
		}
		this.code = code;
		this.status = status;
		this.setStyles();
	},
	setStyles: function() {
		if (this.status == this.statuses.unstarted) {
			$('#pdfLoadingLed').css('background', '#468847');
			$('#pdfLoadingLed').find('.status')
				.html(this.getStatusMessage('added'))
				.removeClass('finished')
				.removeClass('error')
				.addClass('loading-new').end()
				.show();
			$('.generate-pdf-link').css('border', '1px solid #468847');
		} else if (this.status == this.statuses.running) {
			$('#pdfLoadingLed').css('background', '#f89406');
			$('#pdfLoadingLed').find('.status')
				.html(this.getStatusMessage('running'))
				.removeClass('loading-new')
				.addClass('loading-running').end()
				.show();
			$('.generate-pdf-link').css('border', '1px solid #f89406');
		} else if (this.status == this.statuses.finished && this.code == this.codes.ok) {
			this.clearInterval();
			$('#pdfLoadingLed').css('background', '#333');
			$('#pdfLoadingLed').find('.status')
				.html(this.getStatusMessage('finished'))
				.removeClass('loading-new')
				.removeClass('loading-running')
				.addClass('finished');
			$('.generate-pdf-link').css('border', '1px solid #333');
		} else {
			this.clearInterval();
			$('#pdfLoadingLed').css('background', 'red');
			$('#pdfLoadingLed').find('.status')
				.html(this.getStatusMessage('error'))
				.removeClass('loading-new')
				.removeClass('loading-running')
				.addClass('error');
			$('.generate-pdf-link').css('border', '1px solid red');
		}
	},
	getUrlToPdf: function() {
		return this.cacheDir + this.name + this.ext;
	},
	getUrlToTask: function() {
		return '/tasks/view/' + this.taskId;
	},
	getMessages: function() {
		return {
			urlToTask: '(<a href="' + this.getUrlToTask() + '" target="_blank">#' + this.taskId + '</a>)',
			urlToPdf: '<a href="' + this.getUrlToPdf() + '" target="_blank">Pdf file</a>',
			wait: 'please wait...',
			error: 'error'
		};
	},
	getStatusMessage: function(id) {
		var messages = this.getMessages();
		var statusesMessages = {
			added: 'Task added ' + messages.urlToTask + ', ' + messages.wait,
			running: 'Task running ' + messages.urlToTask + ', ' + messages.wait,
			finished: 'Task finished ' + messages.urlToTask + ', ' + messages.urlToPdf,
			error: 'Task finished ' + messages.urlToTask + ', ' + messages.error
		};
		return statusesMessages[id];
	}
};