(function( $ ) {
	'use strict';

	 $(function () {
		 var frame;
		 var $attachmentElems = $('.asset-attachment');
		 var $attachmentList = $('.asset-attachment-list');
		 var reattachRemoveActions = function () {
			 $attachmentList.find('.asset-attachment-list-item-remove').off().on('click', function () {
				 event.preventDefault();
				 event.stopPropagation();
				 var $elem = $(this);
				 $elem.parent().off();
				 $elem.parent().remove();
			 });
		 };

		 reattachRemoveActions();
		 $('.add-asset-attachment-button').click(function (event) {
			 event.preventDefault();
			 event.stopPropagation();

			 if (frame) {
				 frame.open();
				 return;
			 }

			 frame = wp.media({
				 title: 'Select asset',
				 multiple: false,
				 button: {
					 text: 'Add selected asset'
				 }
			 });

			 frame.on('select', function() {
				 var attachment = frame.state().get('selection').first();
				 if (!attachment) {
					 return;
				 }

				 $attachmentList.append(
					 '<li class="asset-attachment-list-item">' +
					 	'<input type="hidden" name="asset_attachement_ids[]" value="' + attachment.id + '">' +
					 	'<span>' + attachment.get('url') + '</span>' +
						'<button class="button asset-attachment-list-item-remove" data-attachment-id="' + attachment.id + '">Remove</button>' +
					 '</li>'
				 );
				 reattachRemoveActions();
			 });

			 frame.open();
		 });
	 });

})( jQuery );
