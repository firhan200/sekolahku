
<script>document.body.classList.remove("no-js");</script>	<script>
	if ( -1 !== navigator.userAgent.indexOf( 'MSIE' ) || -1 !== navigator.appVersion.indexOf( 'Trident/' ) ) {
		document.body.classList.add( 'is-IE' );
	}
	</script>
	
		<script type="text/html" id="tmpl-media-frame">
		<div class="media-frame-title" id="media-frame-title"></div>
		<h2 class="media-frame-menu-heading">Actions</h2>
		<button type="button" class="button button-link media-frame-menu-toggle" aria-expanded="false">
			Menu			<span class="dashicons dashicons-arrow-down" aria-hidden="true"></span>
		</button>
		<div class="media-frame-menu"></div>
		<div class="media-frame-tab-panel">
			<div class="media-frame-router"></div>
			<div class="media-frame-content"></div>
		</div>
		<h2 class="media-frame-actions-heading screen-reader-text">
		Selected media actions		</h2>
		<div class="media-frame-toolbar"></div>
		<div class="media-frame-uploader"></div>
	</script>

		<script type="text/html" id="tmpl-media-modal">
		<div tabindex="0" class="media-modal wp-core-ui" role="dialog" aria-labelledby="media-frame-title">
			<# if ( data.hasCloseButton ) { #>
				<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close dialog</span></span></button>
			<# } #>
			<div class="media-modal-content" role="document"></div>
		</div>
		<div class="media-modal-backdrop"></div>
	</script>

		<script type="text/html" id="tmpl-uploader-window">
		<div class="uploader-window-content">
			<div class="uploader-editor-title">Drop files to upload</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-uploader-editor">
		<div class="uploader-editor-content">
			<div class="uploader-editor-title">Drop files to upload</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-uploader-inline">
		<# var messageClass = data.message ? 'has-upload-message' : 'no-upload-message'; #>
		<# if ( data.canClose ) { #>
		<button class="close dashicons dashicons-no"><span class="screen-reader-text">Close uploader</span></button>
		<# } #>
		<div class="uploader-inline-content {{ messageClass }}">
		<# if ( data.message ) { #>
			<h2 class="upload-message">{{ data.message }}</h2>
		<# } #>
					<div class="upload-ui">
				<h2 class="upload-instructions drop-instructions">Drop files to upload</h2>
				<p class="upload-instructions drop-instructions">or</p>
				<button type="button" class="browser button button-hero" aria-labelledby="post-upload-info">Select Files</button>
			</div>

			<div class="upload-inline-status"></div>

			<div class="post-upload-ui" id="post-upload-info">
				
				<p class="max-upload-size">
				Maximum upload file size: 40 MB.				</p>

				<# if ( data.suggestedWidth && data.suggestedHeight ) { #>
					<p class="suggested-dimensions">
						Suggested image dimensions: {{data.suggestedWidth}} by {{data.suggestedHeight}} pixels.					</p>
				<# } #>

							</div>
				</div>
	</script>

		<script type="text/html" id="tmpl-media-library-view-switcher">
		<a href="http://localhost/sekolahku/wp-admin/upload.php?mode=list" class="view-list">
			<span class="screen-reader-text">List view</span>
		</a>
		<a href="http://localhost/sekolahku/wp-admin/upload.php?mode=grid" class="view-grid current" aria-current="page">
			<span class="screen-reader-text">Grid view</span>
		</a>
	</script>

		<script type="text/html" id="tmpl-uploader-status">
		<h2>Uploading</h2>
		<button type="button" class="button-link upload-dismiss-errors"><span class="screen-reader-text">Dismiss Errors</span></button>

		<div class="media-progress-bar"><div></div></div>
		<div class="upload-details">
			<span class="upload-count">
				<span class="upload-index"></span> / <span class="upload-total"></span>
			</span>
			<span class="upload-detail-separator">&ndash;</span>
			<span class="upload-filename"></span>
		</div>
		<div class="upload-errors"></div>
	</script>

		<script type="text/html" id="tmpl-uploader-status-error">
		<span class="upload-error-filename">{{{ data.filename }}}</span>
		<span class="upload-error-message">{{ data.message }}</span>
	</script>

		<script type="text/html" id="tmpl-edit-attachment-frame">
		<div class="edit-media-header">
			<button class="left dashicons"<# if ( ! data.hasPrevious ) { #> disabled<# } #>><span class="screen-reader-text">Edit previous media item</span></button>
			<button class="right dashicons"<# if ( ! data.hasNext ) { #> disabled<# } #>><span class="screen-reader-text">Edit next media item</span></button>
			<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close dialog</span></span></button>
		</div>
		<div class="media-frame-title"></div>
		<div class="media-frame-content"></div>
	</script>

		<script type="text/html" id="tmpl-attachment-details-two-column">
		<div class="attachment-media-view {{ data.orientation }}">
			<h2 class="screen-reader-text">Attachment Preview</h2>
			<div class="thumbnail thumbnail-{{ data.type }}">
				<# if ( data.uploading ) { #>
					<div class="media-progress-bar"><div></div></div>
				<# } else if ( data.sizes && data.sizes.large ) { #>
					<img class="details-image" src="{{ data.sizes.large.url }}" draggable="false" alt="" />
				<# } else if ( data.sizes && data.sizes.full ) { #>
					<img class="details-image" src="{{ data.sizes.full.url }}" draggable="false" alt="" />
				<# } else if ( -1 === jQuery.inArray( data.type, [ 'audio', 'video' ] ) ) { #>
					<img class="details-image icon" src="{{ data.icon }}" draggable="false" alt="" />
				<# } #>

				<# if ( 'audio' === data.type ) { #>
				<div class="wp-media-wrapper wp-audio">
					<audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
						<source type="{{ data.mime }}" src="{{ data.url }}" />
					</audio>
				</div>
				<# } else if ( 'video' === data.type ) {
					var w_rule = '';
					if ( data.width ) {
						w_rule = 'width: ' + data.width + 'px;';
					} else if ( wp.media.view.settings.contentWidth ) {
						w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
					}
				#>
				<div style="{{ w_rule }}" class="wp-media-wrapper wp-video">
					<video controls="controls" class="wp-video-shortcode" preload="metadata"
						<# if ( data.width ) { #>width="{{ data.width }}"<# } #>
						<# if ( data.height ) { #>height="{{ data.height }}"<# } #>
						<# if ( data.image && data.image.src !== data.icon ) { #>poster="{{ data.image.src }}"<# } #>>
						<source type="{{ data.mime }}" src="{{ data.url }}" />
					</video>
				</div>
				<# } #>

				<div class="attachment-actions">
					<# if ( 'image' === data.type && ! data.uploading && data.sizes && data.can.save ) { #>
					<button type="button" class="button edit-attachment">Edit Image</button>
					<# } else if ( 'pdf' === data.subtype && data.sizes ) { #>
					<p>Document Preview</p>
					<# } #>
				</div>
			</div>
		</div>
		<div class="attachment-info">
			<span class="settings-save-status" role="status">
				<span class="spinner"></span>
				<span class="saved">Saved.</span>
			</span>
			<div class="details">
				<h2 class="screen-reader-text">Details</h2>
				<div class="uploaded"><strong>Uploaded on:</strong> {{ data.dateFormatted }}</div>
				<div class="uploaded-by">
					<strong>Uploaded by:</strong>
						<# if ( data.authorLink ) { #>
							<a href="{{ data.authorLink }}">{{ data.authorName }}</a>
						<# } else { #>
							{{ data.authorName }}
						<# } #>
				</div>
				<# if ( data.uploadedToTitle ) { #>
					<div class="uploaded-to">
						<strong>Uploaded to:</strong>
						<# if ( data.uploadedToLink ) { #>
							<a href="{{ data.uploadedToLink }}">{{ data.uploadedToTitle }}</a>
						<# } else { #>
							{{ data.uploadedToTitle }}
						<# } #>
					</div>
				<# } #>
				<div class="filename"><strong>File name:</strong> {{ data.filename }}</div>
				<div class="file-type"><strong>File type:</strong> {{ data.mime }}</div>
				<div class="file-size"><strong>File size:</strong> {{ data.filesizeHumanReadable }}</div>
				<# if ( 'image' === data.type && ! data.uploading ) { #>
					<# if ( data.width && data.height ) { #>
						<div class="dimensions"><strong>Dimensions:</strong>
							{{ data.width }} by {{ data.height }} pixels						</div>
					<# } #>

					<# if ( data.originalImageURL && data.originalImageName ) { #>
						Original image:						<a href="{{ data.originalImageURL }}">{{data.originalImageName}}</a>
					<# } #>
				<# } #>

				<# if ( data.fileLength && data.fileLengthHumanReadable ) { #>
					<div class="file-length"><strong>Length:</strong>
						<span aria-hidden="true">{{ data.fileLength }}</span>
						<span class="screen-reader-text">{{ data.fileLengthHumanReadable }}</span>
					</div>
				<# } #>

				<# if ( 'audio' === data.type && data.meta.bitrate ) { #>
					<div class="bitrate">
						<strong>Bitrate:</strong> {{ Math.round( data.meta.bitrate / 1000 ) }}kb/s
						<# if ( data.meta.bitrate_mode ) { #>
						{{ ' ' + data.meta.bitrate_mode.toUpperCase() }}
						<# } #>
					</div>
				<# } #>

				<# if ( data.mediaStates ) { #>
					<div class="media-states"><strong>Used as:</strong> {{ data.mediaStates }}</div>
				<# } #>

				<div class="compat-meta">
					<# if ( data.compat && data.compat.meta ) { #>
						{{{ data.compat.meta }}}
					<# } #>
				</div>
			</div>

			<div class="settings">
				<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
				<# if ( 'image' === data.type ) { #>
					<span class="setting has-description" data-setting="alt">
						<label for="attachment-details-two-column-alt-text" class="name">Alternative Text</label>
						<input type="text" id="attachment-details-two-column-alt-text" value="{{ data.alt }}" aria-describedby="alt-text-description" {{ maybeReadOnly }} />
					</span>
					<p class="description" id="alt-text-description"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener">Describe the purpose of the image<span class="screen-reader-text"> (opens in a new tab)</span></a>. Leave empty if the image is purely decorative.</p>
				<# } #>
								<span class="setting" data-setting="title">
					<label for="attachment-details-two-column-title" class="name">Title</label>
					<input type="text" id="attachment-details-two-column-title" value="{{ data.title }}" {{ maybeReadOnly }} />
				</span>
								<# if ( 'audio' === data.type ) { #>
								<span class="setting" data-setting="artist">
					<label for="attachment-details-two-column-artist" class="name">Artist</label>
					<input type="text" id="attachment-details-two-column-artist" value="{{ data.artist || data.meta.artist || '' }}" />
				</span>
								<span class="setting" data-setting="album">
					<label for="attachment-details-two-column-album" class="name">Album</label>
					<input type="text" id="attachment-details-two-column-album" value="{{ data.album || data.meta.album || '' }}" />
				</span>
								<# } #>
				<span class="setting" data-setting="caption">
					<label for="attachment-details-two-column-caption" class="name">Caption</label>
					<textarea id="attachment-details-two-column-caption" {{ maybeReadOnly }}>{{ data.caption }}</textarea>
				</span>
				<span class="setting" data-setting="description">
					<label for="attachment-details-two-column-description" class="name">Description</label>
					<textarea id="attachment-details-two-column-description" {{ maybeReadOnly }}>{{ data.description }}</textarea>
				</span>
				<span class="setting" data-setting="url">
					<label for="attachment-details-two-column-copy-link" class="name">File URL:</label>
					<input type="text" class="attachment-details-copy-link" id="attachment-details-two-column-copy-link" value="{{ data.url }}" readonly />
					<span class="copy-to-clipboard-container">
						<button type="button" class="button button-small copy-attachment-url" data-clipboard-target="#attachment-details-two-column-copy-link">Copy URL to clipboard</button>
						<span class="success hidden" aria-hidden="true">Copied!</span>
					</span>
				</span>
				<div class="attachment-compat"></div>
			</div>

			<div class="actions">
				<# if ( data.link ) { #>
					<a class="view-attachment" href="{{ data.link }}">View attachment page</a>
				<# } #>
				<# if ( data.can.save ) { #>
					<# if ( data.link ) { #>
						<span class="links-separator">|</span>
					<# } #>
					<a href="{{ data.editLink }}">Edit more details</a>
				<# } #>
				<# if ( ! data.uploading && data.can.remove ) { #>
					<# if ( data.link || data.can.save ) { #>
						<span class="links-separator">|</span>
					<# } #>
											<button type="button" class="button-link delete-attachment">Delete permanently</button>
									<# } #>
			</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-attachment">
		<div class="attachment-preview js--select-attachment type-{{ data.type }} subtype-{{ data.subtype }} {{ data.orientation }}">
			<div class="thumbnail">
				<# if ( data.uploading ) { #>
					<div class="media-progress-bar"><div style="width: {{ data.percent }}%"></div></div>
				<# } else if ( 'image' === data.type && data.size && data.size.url ) { #>
					<div class="centered">
						<img src="{{ data.size.url }}" draggable="false" alt="" />
					</div>
				<# } else { #>
					<div class="centered">
						<# if ( data.image && data.image.src && data.image.src !== data.icon ) { #>
							<img src="{{ data.image.src }}" class="thumbnail" draggable="false" alt="" />
						<# } else if ( data.sizes && data.sizes.medium ) { #>
							<img src="{{ data.sizes.medium.url }}" class="thumbnail" draggable="false" alt="" />
						<# } else { #>
							<img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
						<# } #>
					</div>
					<div class="filename">
						<div>{{ data.filename }}</div>
					</div>
				<# } #>
			</div>
			<# if ( data.buttons.close ) { #>
				<button type="button" class="button-link attachment-close media-modal-icon"><span class="screen-reader-text">Remove</span></button>
			<# } #>
		</div>
		<# if ( data.buttons.check ) { #>
			<button type="button" class="check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>
		<# } #>
		<#
		var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
		if ( data.describe ) {
			if ( 'image' === data.type ) { #>
				<input type="text" value="{{ data.caption }}" class="describe" data-setting="caption"
					aria-label="Caption"
					placeholder="Caption&hellip;" {{ maybeReadOnly }} />
			<# } else { #>
				<input type="text" value="{{ data.title }}" class="describe" data-setting="title"
					<# if ( 'video' === data.type ) { #>
						aria-label="Video title"
						placeholder="Video title&hellip;"
					<# } else if ( 'audio' === data.type ) { #>
						aria-label="Audio title"
						placeholder="Audio title&hellip;"
					<# } else { #>
						aria-label="Media title"
						placeholder="Media title&hellip;"
					<# } #> {{ maybeReadOnly }} />
			<# }
		} #>
	</script>

		<script type="text/html" id="tmpl-attachment-details">
		<h2>
			Attachment Details			<span class="settings-save-status" role="status">
				<span class="spinner"></span>
				<span class="saved">Saved.</span>
			</span>
		</h2>
		<div class="attachment-info">

			<# if ( 'audio' === data.type ) { #>
				<div class="wp-media-wrapper wp-audio">
					<audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
						<source type="{{ data.mime }}" src="{{ data.url }}" />
					</audio>
				</div>
			<# } else if ( 'video' === data.type ) {
				var w_rule = '';
				if ( data.width ) {
					w_rule = 'width: ' + data.width + 'px;';
				} else if ( wp.media.view.settings.contentWidth ) {
					w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
				}
			#>
				<div style="{{ w_rule }}" class="wp-media-wrapper wp-video">
					<video controls="controls" class="wp-video-shortcode" preload="metadata"
						<# if ( data.width ) { #>width="{{ data.width }}"<# } #>
						<# if ( data.height ) { #>height="{{ data.height }}"<# } #>
						<# if ( data.image && data.image.src !== data.icon ) { #>poster="{{ data.image.src }}"<# } #>>
						<source type="{{ data.mime }}" src="{{ data.url }}" />
					</video>
				</div>
			<# } else { #>
				<div class="thumbnail thumbnail-{{ data.type }}">
					<# if ( data.uploading ) { #>
						<div class="media-progress-bar"><div></div></div>
					<# } else if ( 'image' === data.type && data.size && data.size.url ) { #>
						<img src="{{ data.size.url }}" draggable="false" alt="" />
					<# } else { #>
						<img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
					<# } #>
				</div>
			<# } #>

			<div class="details">
				<div class="filename">{{ data.filename }}</div>
				<div class="uploaded">{{ data.dateFormatted }}</div>

				<div class="file-size">{{ data.filesizeHumanReadable }}</div>
				<# if ( 'image' === data.type && ! data.uploading ) { #>
					<# if ( data.width && data.height ) { #>
						<div class="dimensions">
							{{ data.width }} by {{ data.height }} pixels						</div>
					<# } #>

					<# if ( data.originalImageURL && data.originalImageName ) { #>
						Original image:						<a href="{{ data.originalImageURL }}">{{data.originalImageName}}</a>
					<# } #>

					<# if ( data.can.save && data.sizes ) { #>
						<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank">Edit Image</a>
					<# } #>
				<# } #>

				<# if ( data.fileLength && data.fileLengthHumanReadable ) { #>
					<div class="file-length">Length:						<span aria-hidden="true">{{ data.fileLength }}</span>
						<span class="screen-reader-text">{{ data.fileLengthHumanReadable }}</span>
					</div>
				<# } #>

				<# if ( data.mediaStates ) { #>
					<div class="media-states"><strong>Used as:</strong> {{ data.mediaStates }}</div>
				<# } #>

				<# if ( ! data.uploading && data.can.remove ) { #>
											<button type="button" class="button-link delete-attachment">Delete permanently</button>
									<# } #>

				<div class="compat-meta">
					<# if ( data.compat && data.compat.meta ) { #>
						{{{ data.compat.meta }}}
					<# } #>
				</div>
			</div>
		</div>
		<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
		<# if ( 'image' === data.type ) { #>
			<span class="setting has-description" data-setting="alt">
				<label for="attachment-details-alt-text" class="name">Alt Text</label>
				<input type="text" id="attachment-details-alt-text" value="{{ data.alt }}" aria-describedby="alt-text-description" {{ maybeReadOnly }} />
			</span>
			<p class="description" id="alt-text-description"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener">Describe the purpose of the image<span class="screen-reader-text"> (opens in a new tab)</span></a>. Leave empty if the image is purely decorative.</p>
		<# } #>
				<span class="setting" data-setting="title">
			<label for="attachment-details-title" class="name">Title</label>
			<input type="text" id="attachment-details-title" value="{{ data.title }}" {{ maybeReadOnly }} />
		</span>
				<# if ( 'audio' === data.type ) { #>
				<span class="setting" data-setting="artist">
			<label for="attachment-details-artist" class="name">Artist</label>
			<input type="text" id="attachment-details-artist" value="{{ data.artist || data.meta.artist || '' }}" />
		</span>
				<span class="setting" data-setting="album">
			<label for="attachment-details-album" class="name">Album</label>
			<input type="text" id="attachment-details-album" value="{{ data.album || data.meta.album || '' }}" />
		</span>
				<# } #>
		<span class="setting" data-setting="caption">
			<label for="attachment-details-caption" class="name">Caption</label>
			<textarea id="attachment-details-caption" {{ maybeReadOnly }}>{{ data.caption }}</textarea>
		</span>
		<span class="setting" data-setting="description">
			<label for="attachment-details-description" class="name">Description</label>
			<textarea id="attachment-details-description" {{ maybeReadOnly }}>{{ data.description }}</textarea>
		</span>
		<span class="setting" data-setting="url">
			<label for="attachment-details-copy-link" class="name">File URL:</label>
			<input type="text" class="attachment-details-copy-link" id="attachment-details-copy-link" value="{{ data.url }}" readonly />
			<div class="copy-to-clipboard-container">
				<button type="button" class="button button-small copy-attachment-url" data-clipboard-target="#attachment-details-copy-link">Copy URL to clipboard</button>
				<span class="success hidden" aria-hidden="true">Copied!</span>
			</div>
		</span>
	</script>

		<script type="text/html" id="tmpl-media-selection">
		<div class="selection-info">
			<span class="count"></span>
			<# if ( data.editable ) { #>
				<button type="button" class="button-link edit-selection">Edit Selection</button>
			<# } #>
			<# if ( data.clearable ) { #>
				<button type="button" class="button-link clear-selection">Clear</button>
			<# } #>
		</div>
		<div class="selection-view"></div>
	</script>

		<script type="text/html" id="tmpl-attachment-display-settings">
		<h2>Attachment Display Settings</h2>

		<# if ( 'image' === data.type ) { #>
			<span class="setting align">
				<label for="attachment-display-settings-alignment" class="name">Alignment</label>
				<select id="attachment-display-settings-alignment" class="alignment"
					data-setting="align"
					<# if ( data.userSettings ) { #>
						data-user-setting="align"
					<# } #>>

					<option value="left">
						Left					</option>
					<option value="center">
						Center					</option>
					<option value="right">
						Right					</option>
					<option value="none" selected>
						None					</option>
				</select>
			</span>
		<# } #>

		<span class="setting">
			<label for="attachment-display-settings-link-to" class="name">
				<# if ( data.model.canEmbed ) { #>
					Embed or Link				<# } else { #>
					Link To				<# } #>
			</label>
			<select id="attachment-display-settings-link-to" class="link-to"
				data-setting="link"
				<# if ( data.userSettings && ! data.model.canEmbed ) { #>
					data-user-setting="urlbutton"
				<# } #>>

			<# if ( data.model.canEmbed ) { #>
				<option value="embed" selected>
					Embed Media Player				</option>
				<option value="file">
			<# } else { #>
				<option value="none" selected>
					None				</option>
				<option value="file">
			<# } #>
				<# if ( data.model.canEmbed ) { #>
					Link to Media File				<# } else { #>
					Media File				<# } #>
				</option>
				<option value="post">
				<# if ( data.model.canEmbed ) { #>
					Link to Attachment Page				<# } else { #>
					Attachment Page				<# } #>
				</option>
			<# if ( 'image' === data.type ) { #>
				<option value="custom">
					Custom URL				</option>
			<# } #>
			</select>
		</span>
		<span class="setting">
			<label for="attachment-display-settings-link-to-custom" class="name">URL</label>
			<input type="text" id="attachment-display-settings-link-to-custom" class="link-to-custom" data-setting="linkUrl" />
		</span>

		<# if ( 'undefined' !== typeof data.sizes ) { #>
			<span class="setting">
				<label for="attachment-display-settings-size" class="name">Size</label>
				<select id="attachment-display-settings-size" class="size" name="size"
					data-setting="size"
					<# if ( data.userSettings ) { #>
						data-user-setting="imgsize"
					<# } #>>
											<#
						var size = data.sizes['thumbnail'];
						if ( size ) { #>
							<option value="thumbnail" >
								Thumbnail &ndash; {{ size.width }} &times; {{ size.height }}
							</option>
						<# } #>
											<#
						var size = data.sizes['medium'];
						if ( size ) { #>
							<option value="medium" >
								Medium &ndash; {{ size.width }} &times; {{ size.height }}
							</option>
						<# } #>
											<#
						var size = data.sizes['large'];
						if ( size ) { #>
							<option value="large" >
								Large &ndash; {{ size.width }} &times; {{ size.height }}
							</option>
						<# } #>
											<#
						var size = data.sizes['full'];
						if ( size ) { #>
							<option value="full"  selected='selected'>
								Full Size &ndash; {{ size.width }} &times; {{ size.height }}
							</option>
						<# } #>
									</select>
			</span>
		<# } #>
	</script>

		<script type="text/html" id="tmpl-gallery-settings">
		<h2>Gallery Settings</h2>

		<span class="setting">
			<label for="gallery-settings-link-to" class="name">Link To</label>
			<select id="gallery-settings-link-to" class="link-to"
				data-setting="link"
				<# if ( data.userSettings ) { #>
					data-user-setting="urlbutton"
				<# } #>>

				<option value="post" <# if ( ! wp.media.galleryDefaults.link || 'post' === wp.media.galleryDefaults.link ) {
					#>selected="selected"<# }
				#>>
					Attachment Page				</option>
				<option value="file" <# if ( 'file' === wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
					Media File				</option>
				<option value="none" <# if ( 'none' === wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
					None				</option>
			</select>
		</span>

		<span class="setting">
			<label for="gallery-settings-columns" class="name select-label-inline">Columns</label>
			<select id="gallery-settings-columns" class="columns" name="columns"
				data-setting="columns">
									<option value="1" <#
						if ( 1 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						1					</option>
									<option value="2" <#
						if ( 2 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						2					</option>
									<option value="3" <#
						if ( 3 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						3					</option>
									<option value="4" <#
						if ( 4 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						4					</option>
									<option value="5" <#
						if ( 5 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						5					</option>
									<option value="6" <#
						if ( 6 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						6					</option>
									<option value="7" <#
						if ( 7 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						7					</option>
									<option value="8" <#
						if ( 8 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						8					</option>
									<option value="9" <#
						if ( 9 == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
					#>>
						9					</option>
							</select>
		</span>

		<span class="setting">
			<input type="checkbox" id="gallery-settings-random-order" data-setting="_orderbyRandom" />
			<label for="gallery-settings-random-order" class="checkbox-label-inline">Random Order</label>
		</span>

		<span class="setting size">
			<label for="gallery-settings-size" class="name">Size</label>
			<select id="gallery-settings-size" class="size" name="size"
				data-setting="size"
				<# if ( data.userSettings ) { #>
					data-user-setting="imgsize"
				<# } #>
				>
									<option value="thumbnail">
						Thumbnail					</option>
									<option value="medium">
						Medium					</option>
									<option value="large">
						Large					</option>
									<option value="full">
						Full Size					</option>
							</select>
		</span>
	</script>

		<script type="text/html" id="tmpl-playlist-settings">
		<h2>Playlist Settings</h2>

		<# var emptyModel = _.isEmpty( data.model ),
			isVideo = 'video' === data.controller.get('library').props.get('type'); #>

		<span class="setting">
			<input type="checkbox" id="playlist-settings-show-list" data-setting="tracklist" <# if ( emptyModel ) { #>
				checked="checked"
			<# } #> />
			<label for="playlist-settings-show-list" class="checkbox-label-inline">
				<# if ( isVideo ) { #>
				Show Video List				<# } else { #>
				Show Tracklist				<# } #>
			</label>
		</span>

		<# if ( ! isVideo ) { #>
		<span class="setting">
			<input type="checkbox" id="playlist-settings-show-artist" data-setting="artists" <# if ( emptyModel ) { #>
				checked="checked"
			<# } #> />
			<label for="playlist-settings-show-artist" class="checkbox-label-inline">
				Show Artist Name in Tracklist			</label>
		</span>
		<# } #>

		<span class="setting">
			<input type="checkbox" id="playlist-settings-show-images" data-setting="images" <# if ( emptyModel ) { #>
				checked="checked"
			<# } #> />
			<label for="playlist-settings-show-images" class="checkbox-label-inline">
				Show Images			</label>
		</span>
	</script>

		<script type="text/html" id="tmpl-embed-link-settings">
		<span class="setting link-text">
			<label for="embed-link-settings-link-text" class="name">Link Text</label>
			<input type="text" id="embed-link-settings-link-text" class="alignment" data-setting="linkText" />
		</span>
		<div class="embed-container" style="display: none;">
			<div class="embed-preview"></div>
		</div>
	</script>

		<script type="text/html" id="tmpl-embed-image-settings">
		<div class="wp-clearfix">
			<div class="thumbnail">
				<img src="{{ data.model.url }}" draggable="false" alt="" />
			</div>
		</div>

		<span class="setting alt-text has-description">
			<label for="embed-image-settings-alt-text" class="name">Alternative Text</label>
			<input type="text" id="embed-image-settings-alt-text" data-setting="alt" aria-describedby="alt-text-description" />
		</span>
		<p class="description" id="alt-text-description"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener">Describe the purpose of the image<span class="screen-reader-text"> (opens in a new tab)</span></a>. Leave empty if the image is purely decorative.</p>

					<span class="setting caption">
				<label for="embed-image-settings-caption" class="name">Caption</label>
				<textarea id="embed-image-settings-caption" data-setting="caption"></textarea>
			</span>
		
		<fieldset class="setting-group">
			<legend class="name">Align</legend>
			<span class="setting align">
				<span class="button-group button-large" data-setting="align">
					<button class="button" value="left">
						Left					</button>
					<button class="button" value="center">
						Center					</button>
					<button class="button" value="right">
						Right					</button>
					<button class="button active" value="none">
						None					</button>
				</span>
			</span>
		</fieldset>

		<fieldset class="setting-group">
			<legend class="name">Link To</legend>
			<span class="setting link-to">
				<span class="button-group button-large" data-setting="link">
					<button class="button" value="file">
						Image URL					</button>
					<button class="button" value="custom">
						Custom URL					</button>
					<button class="button active" value="none">
						None					</button>
				</span>
			</span>
			<span class="setting">
				<label for="embed-image-settings-link-to-custom" class="name">URL</label>
				<input type="text" id="embed-image-settings-link-to-custom" class="link-to-custom" data-setting="linkUrl" />
			</span>
		</fieldset>
	</script>

		<script type="text/html" id="tmpl-image-details">
		<div class="media-embed">
			<div class="embed-media-settings">
				<div class="column-settings">
					<span class="setting alt-text has-description">
						<label for="image-details-alt-text" class="name">Alternative Text</label>
						<input type="text" id="image-details-alt-text" data-setting="alt" value="{{ data.model.alt }}" aria-describedby="alt-text-description" />
					</span>
					<p class="description" id="alt-text-description"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener">Describe the purpose of the image<span class="screen-reader-text"> (opens in a new tab)</span></a>. Leave empty if the image is purely decorative.</p>

											<span class="setting caption">
							<label for="image-details-caption" class="name">Caption</label>
							<textarea id="image-details-caption" data-setting="caption">{{ data.model.caption }}</textarea>
						</span>
					
					<h2>Display Settings</h2>
					<fieldset class="setting-group">
						<legend class="legend-inline">Align</legend>
						<span class="setting align">
							<span class="button-group button-large" data-setting="align">
								<button class="button" value="left">
									Left								</button>
								<button class="button" value="center">
									Center								</button>
								<button class="button" value="right">
									Right								</button>
								<button class="button active" value="none">
									None								</button>
							</span>
						</span>
					</fieldset>

					<# if ( data.attachment ) { #>
						<# if ( 'undefined' !== typeof data.attachment.sizes ) { #>
							<span class="setting size">
								<label for="image-details-size" class="name">Size</label>
								<select id="image-details-size" class="size" name="size"
									data-setting="size"
									<# if ( data.userSettings ) { #>
										data-user-setting="imgsize"
									<# } #>>
																			<#
										var size = data.sizes['thumbnail'];
										if ( size ) { #>
											<option value="thumbnail">
												Thumbnail &ndash; {{ size.width }} &times; {{ size.height }}
											</option>
										<# } #>
																			<#
										var size = data.sizes['medium'];
										if ( size ) { #>
											<option value="medium">
												Medium &ndash; {{ size.width }} &times; {{ size.height }}
											</option>
										<# } #>
																			<#
										var size = data.sizes['large'];
										if ( size ) { #>
											<option value="large">
												Large &ndash; {{ size.width }} &times; {{ size.height }}
											</option>
										<# } #>
																			<#
										var size = data.sizes['full'];
										if ( size ) { #>
											<option value="full">
												Full Size &ndash; {{ size.width }} &times; {{ size.height }}
											</option>
										<# } #>
																		<option value="custom">
										Custom Size									</option>
								</select>
							</span>
						<# } #>
							<div class="custom-size wp-clearfix<# if ( data.model.size !== 'custom' ) { #> hidden<# } #>">
								<span class="custom-size-setting">
									<label for="image-details-size-width">Width</label>
									<input type="number" id="image-details-size-width" aria-describedby="image-size-desc" data-setting="customWidth" step="1" value="{{ data.model.customWidth }}" />
								</span>
								<span class="sep" aria-hidden="true">&times;</span>
								<span class="custom-size-setting">
									<label for="image-details-size-height">Height</label>
									<input type="number" id="image-details-size-height" aria-describedby="image-size-desc" data-setting="customHeight" step="1" value="{{ data.model.customHeight }}" />
								</span>
								<p id="image-size-desc" class="description">Image size in pixels</p>
							</div>
					<# } #>

					<span class="setting link-to">
						<label for="image-details-link-to" class="name">Link To</label>
						<select id="image-details-link-to" data-setting="link">
						<# if ( data.attachment ) { #>
							<option value="file">
								Media File							</option>
							<option value="post">
								Attachment Page							</option>
						<# } else { #>
							<option value="file">
								Image URL							</option>
						<# } #>
							<option value="custom">
								Custom URL							</option>
							<option value="none">
								None							</option>
						</select>
					</span>
					<span class="setting">
						<label for="image-details-link-to-custom" class="name">URL</label>
						<input type="text" id="image-details-link-to-custom" class="link-to-custom" data-setting="linkUrl" />
					</span>

					<div class="advanced-section">
						<h2><button type="button" class="button-link advanced-toggle">Advanced Options</button></h2>
						<div class="advanced-settings hidden">
							<div class="advanced-image">
								<span class="setting title-text">
									<label for="image-details-title-attribute" class="name">Image Title Attribute</label>
									<input type="text" id="image-details-title-attribute" data-setting="title" value="{{ data.model.title }}" />
								</span>
								<span class="setting extra-classes">
									<label for="image-details-css-class" class="name">Image CSS Class</label>
									<input type="text" id="image-details-css-class" data-setting="extraClasses" value="{{ data.model.extraClasses }}" />
								</span>
							</div>
							<div class="advanced-link">
								<span class="setting link-target">
									<input type="checkbox" id="image-details-link-target" data-setting="linkTargetBlank" value="_blank" <# if ( data.model.linkTargetBlank ) { #>checked="checked"<# } #>>
									<label for="image-details-link-target" class="checkbox-label">Open link in a new tab</label>
								</span>
								<span class="setting link-rel">
									<label for="image-details-link-rel" class="name">Link Rel</label>
									<input type="text" id="image-details-link-rel" data-setting="linkRel" value="{{ data.model.linkRel }}" />
								</span>
								<span class="setting link-class-name">
									<label for="image-details-link-css-class" class="name">Link CSS Class</label>
									<input type="text" id="image-details-link-css-class" data-setting="linkClassName" value="{{ data.model.linkClassName }}" />
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="column-image">
					<div class="image">
						<img src="{{ data.model.url }}" draggable="false" alt="" />
						<# if ( data.attachment && window.imageEdit ) { #>
							<div class="actions">
								<input type="button" class="edit-attachment button" value="Edit Original" />
								<input type="button" class="replace-attachment button" value="Replace" />
							</div>
						<# } #>
					</div>
				</div>
			</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-image-editor">
		<div id="media-head-{{ data.id }}"></div>
		<div id="image-editor-{{ data.id }}"></div>
	</script>

		<script type="text/html" id="tmpl-audio-details">
		<# var ext, html5types = {
			mp3: wp.media.view.settings.embedMimes.mp3,
			ogg: wp.media.view.settings.embedMimes.ogg
		}; #>

				<div class="media-embed media-embed-details">
			<div class="embed-media-settings embed-audio-settings">
				<audio style="visibility: hidden"
	controls
	class="wp-audio-shortcode"
	width="{{ _.isUndefined( data.model.width ) ? 400 : data.model.width }}"
	preload="{{ _.isUndefined( data.model.preload ) ? 'none' : data.model.preload }}"
	<#
		if ( ! _.isUndefined( data.model.autoplay ) && data.model.autoplay ) {
		#> autoplay<#
	}
		if ( ! _.isUndefined( data.model.loop ) && data.model.loop ) {
		#> loop<#
	}
	#>
>
	<# if ( ! _.isEmpty( data.model.src ) ) { #>
	<source src="{{ data.model.src }}" type="{{ wp.media.view.settings.embedMimes[ data.model.src.split('.').pop() ] }}" />
	<# } #>

		<# if ( ! _.isEmpty( data.model.mp3 ) ) { #>
	<source src="{{ data.model.mp3 }}" type="{{ wp.media.view.settings.embedMimes[ 'mp3' ] }}" />
	<# } #>
			<# if ( ! _.isEmpty( data.model.ogg ) ) { #>
	<source src="{{ data.model.ogg }}" type="{{ wp.media.view.settings.embedMimes[ 'ogg' ] }}" />
	<# } #>
			<# if ( ! _.isEmpty( data.model.flac ) ) { #>
	<source src="{{ data.model.flac }}" type="{{ wp.media.view.settings.embedMimes[ 'flac' ] }}" />
	<# } #>
			<# if ( ! _.isEmpty( data.model.m4a ) ) { #>
	<source src="{{ data.model.m4a }}" type="{{ wp.media.view.settings.embedMimes[ 'm4a' ] }}" />
	<# } #>
			<# if ( ! _.isEmpty( data.model.wav ) ) { #>
	<source src="{{ data.model.wav }}" type="{{ wp.media.view.settings.embedMimes[ 'wav' ] }}" />
	<# } #>
		</audio>
	
				<# if ( ! _.isEmpty( data.model.src ) ) {
					ext = data.model.src.split('.').pop();
					if ( html5types[ ext ] ) {
						delete html5types[ ext ];
					}
				#>
				<span class="setting">
					<label for="audio-details-source" class="name">URL</label>
					<input type="text" id="audio-details-source" readonly data-setting="src" value="{{ data.model.src }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.mp3 ) ) {
					if ( ! _.isUndefined( html5types.mp3 ) ) {
						delete html5types.mp3;
					}
				#>
				<span class="setting">
					<label for="audio-details-mp3-source" class="name">MP3</label>
					<input type="text" id="audio-details-mp3-source" readonly data-setting="mp3" value="{{ data.model.mp3 }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.ogg ) ) {
					if ( ! _.isUndefined( html5types.ogg ) ) {
						delete html5types.ogg;
					}
				#>
				<span class="setting">
					<label for="audio-details-ogg-source" class="name">OGG</label>
					<input type="text" id="audio-details-ogg-source" readonly data-setting="ogg" value="{{ data.model.ogg }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.flac ) ) {
					if ( ! _.isUndefined( html5types.flac ) ) {
						delete html5types.flac;
					}
				#>
				<span class="setting">
					<label for="audio-details-flac-source" class="name">FLAC</label>
					<input type="text" id="audio-details-flac-source" readonly data-setting="flac" value="{{ data.model.flac }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.m4a ) ) {
					if ( ! _.isUndefined( html5types.m4a ) ) {
						delete html5types.m4a;
					}
				#>
				<span class="setting">
					<label for="audio-details-m4a-source" class="name">M4A</label>
					<input type="text" id="audio-details-m4a-source" readonly data-setting="m4a" value="{{ data.model.m4a }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.wav ) ) {
					if ( ! _.isUndefined( html5types.wav ) ) {
						delete html5types.wav;
					}
				#>
				<span class="setting">
					<label for="audio-details-wav-source" class="name">WAV</label>
					<input type="text" id="audio-details-wav-source" readonly data-setting="wav" value="{{ data.model.wav }}" />
					<button type="button" class="button-link remove-setting">Remove audio source</button>
				</span>
				<# } #>
				
				<# if ( ! _.isEmpty( html5types ) ) { #>
				<fieldset class="setting-group">
					<legend class="name">Add alternate sources for maximum HTML5 playback</legend>
					<span class="setting">
						<span class="button-large">
						<# _.each( html5types, function (mime, type) { #>
							<button class="button add-media-source" data-mime="{{ mime }}">{{ type }}</button>
						<# } ) #>
						</span>
					</span>
				</fieldset>
				<# } #>

				<fieldset class="setting-group">
					<legend class="name">Preload</legend>
					<span class="setting preload">
						<span class="button-group button-large" data-setting="preload">
							<button class="button" value="auto">Auto</button>
							<button class="button" value="metadata">Metadata</button>
							<button class="button active" value="none">None</button>
						</span>
					</span>
				</fieldset>

				<span class="setting-group">
					<span class="setting checkbox-setting autoplay">
						<input type="checkbox" id="audio-details-autoplay" data-setting="autoplay" />
						<label for="audio-details-autoplay" class="checkbox-label">Autoplay</label>
					</span>

					<span class="setting checkbox-setting">
						<input type="checkbox" id="audio-details-loop" data-setting="loop" />
						<label for="audio-details-loop" class="checkbox-label">Loop</label>
					</span>
				</span>
			</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-video-details">
		<# var ext, html5types = {
			mp4: wp.media.view.settings.embedMimes.mp4,
			ogv: wp.media.view.settings.embedMimes.ogv,
			webm: wp.media.view.settings.embedMimes.webm
		}; #>

				<div class="media-embed media-embed-details">
			<div class="embed-media-settings embed-video-settings">
				<div class="wp-video-holder">
				<#
				var w = ! data.model.width || data.model.width > 640 ? 640 : data.model.width,
					h = ! data.model.height ? 360 : data.model.height;

				if ( data.model.width && w !== data.model.width ) {
					h = Math.ceil( ( h * w ) / data.model.width );
				}
				#>

				<#  var w_rule = '', classes = [],
		w, h, settings = wp.media.view.settings,
		isYouTube = isVimeo = false;

	if ( ! _.isEmpty( data.model.src ) ) {
		isYouTube = data.model.src.match(/youtube|youtu\.be/);
		isVimeo = -1 !== data.model.src.indexOf('vimeo');
	}

	if ( settings.contentWidth && data.model.width >= settings.contentWidth ) {
		w = settings.contentWidth;
	} else {
		w = data.model.width;
	}

	if ( w !== data.model.width ) {
		h = Math.ceil( ( data.model.height * w ) / data.model.width );
	} else {
		h = data.model.height;
	}

	if ( w ) {
		w_rule = 'width: ' + w + 'px; ';
	}

	if ( isYouTube ) {
		classes.push( 'youtube-video' );
	}

	if ( isVimeo ) {
		classes.push( 'vimeo-video' );
	}

#>
<div style="{{ w_rule }}" class="wp-video">
<video controls
	class="wp-video-shortcode {{ classes.join( ' ' ) }}"
	<# if ( w ) { #>width="{{ w }}"<# } #>
	<# if ( h ) { #>height="{{ h }}"<# } #>
			<#
		if ( ! _.isUndefined( data.model.poster ) && data.model.poster ) {
			#> poster="{{ data.model.poster }}"<#
		} #>
			preload			="{{ _.isUndefined( data.model.preload ) ? 'metadata' : data.model.preload }}"
				<#
		if ( ! _.isUndefined( data.model.autoplay ) && data.model.autoplay ) {
		#> autoplay<#
	}
		if ( ! _.isUndefined( data.model.loop ) && data.model.loop ) {
		#> loop<#
	}
	#>
>
	<# if ( ! _.isEmpty( data.model.src ) ) {
		if ( isYouTube ) { #>
		<source src="{{ data.model.src }}" type="video/youtube" />
		<# } else if ( isVimeo ) { #>
		<source src="{{ data.model.src }}" type="video/vimeo" />
		<# } else { #>
		<source src="{{ data.model.src }}" type="{{ settings.embedMimes[ data.model.src.split('.').pop() ] }}" />
		<# }
	} #>

		<# if ( data.model.mp4 ) { #>
	<source src="{{ data.model.mp4 }}" type="{{ settings.embedMimes[ 'mp4' ] }}" />
	<# } #>
		<# if ( data.model.m4v ) { #>
	<source src="{{ data.model.m4v }}" type="{{ settings.embedMimes[ 'm4v' ] }}" />
	<# } #>
		<# if ( data.model.webm ) { #>
	<source src="{{ data.model.webm }}" type="{{ settings.embedMimes[ 'webm' ] }}" />
	<# } #>
		<# if ( data.model.ogv ) { #>
	<source src="{{ data.model.ogv }}" type="{{ settings.embedMimes[ 'ogv' ] }}" />
	<# } #>
		<# if ( data.model.flv ) { #>
	<source src="{{ data.model.flv }}" type="{{ settings.embedMimes[ 'flv' ] }}" />
	<# } #>
		{{{ data.model.content }}}
</video>
</div>
	
				<# if ( ! _.isEmpty( data.model.src ) ) {
					ext = data.model.src.split('.').pop();
					if ( html5types[ ext ] ) {
						delete html5types[ ext ];
					}
				#>
				<span class="setting">
					<label for="video-details-source" class="name">URL</label>
					<input type="text" id="video-details-source" readonly data-setting="src" value="{{ data.model.src }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.mp4 ) ) {
					if ( ! _.isUndefined( html5types.mp4 ) ) {
						delete html5types.mp4;
					}
				#>
				<span class="setting">
					<label for="video-details-mp4-source" class="name">MP4</label>
					<input type="text" id="video-details-mp4-source" readonly data-setting="mp4" value="{{ data.model.mp4 }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.m4v ) ) {
					if ( ! _.isUndefined( html5types.m4v ) ) {
						delete html5types.m4v;
					}
				#>
				<span class="setting">
					<label for="video-details-m4v-source" class="name">M4V</label>
					<input type="text" id="video-details-m4v-source" readonly data-setting="m4v" value="{{ data.model.m4v }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.webm ) ) {
					if ( ! _.isUndefined( html5types.webm ) ) {
						delete html5types.webm;
					}
				#>
				<span class="setting">
					<label for="video-details-webm-source" class="name">WEBM</label>
					<input type="text" id="video-details-webm-source" readonly data-setting="webm" value="{{ data.model.webm }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.ogv ) ) {
					if ( ! _.isUndefined( html5types.ogv ) ) {
						delete html5types.ogv;
					}
				#>
				<span class="setting">
					<label for="video-details-ogv-source" class="name">OGV</label>
					<input type="text" id="video-details-ogv-source" readonly data-setting="ogv" value="{{ data.model.ogv }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								<# if ( ! _.isEmpty( data.model.flv ) ) {
					if ( ! _.isUndefined( html5types.flv ) ) {
						delete html5types.flv;
					}
				#>
				<span class="setting">
					<label for="video-details-flv-source" class="name">FLV</label>
					<input type="text" id="video-details-flv-source" readonly data-setting="flv" value="{{ data.model.flv }}" />
					<button type="button" class="button-link remove-setting">Remove video source</button>
				</span>
				<# } #>
								</div>

				<# if ( ! _.isEmpty( html5types ) ) { #>
				<fieldset class="setting-group">
					<legend class="name">Add alternate sources for maximum HTML5 playback</legend>
					<span class="setting">
						<span class="button-large">
						<# _.each( html5types, function (mime, type) { #>
							<button class="button add-media-source" data-mime="{{ mime }}">{{ type }}</button>
						<# } ) #>
						</span>
					</span>
				</fieldset>
				<# } #>

				<# if ( ! _.isEmpty( data.model.poster ) ) { #>
				<span class="setting">
					<label for="video-details-poster-image" class="name">Poster Image</label>
					<input type="text" id="video-details-poster-image" readonly data-setting="poster" value="{{ data.model.poster }}" />
					<button type="button" class="button-link remove-setting">Remove poster image</button>
				</span>
				<# } #>

				<fieldset class="setting-group">
					<legend class="name">Preload</legend>
					<span class="setting preload">
						<span class="button-group button-large" data-setting="preload">
							<button class="button" value="auto">Auto</button>
							<button class="button" value="metadata">Metadata</button>
							<button class="button active" value="none">None</button>
						</span>
					</span>
				</fieldset>

				<span class="setting-group">
					<span class="setting checkbox-setting autoplay">
						<input type="checkbox" id="video-details-autoplay" data-setting="autoplay" />
						<label for="video-details-autoplay" class="checkbox-label">Autoplay</label>
					</span>

					<span class="setting checkbox-setting">
						<input type="checkbox" id="video-details-loop" data-setting="loop" />
						<label for="video-details-loop" class="checkbox-label">Loop</label>
					</span>
				</span>

				<span class="setting" data-setting="content">
					<#
					var content = '';
					if ( ! _.isEmpty( data.model.content ) ) {
						var tracks = jQuery( data.model.content ).filter( 'track' );
						_.each( tracks.toArray(), function( track, index ) {
							content += track.outerHTML; #>
						<label for="video-details-track-{{ index }}" class="name">Tracks (subtitles, captions, descriptions, chapters, or metadata)</label>
						<input class="content-track" type="text" id="video-details-track-{{ index }}" aria-describedby="video-details-track-desc-{{ index }}" value="{{ track.outerHTML }}" />
						<span class="description" id="video-details-track-desc-{{ index }}">
						The srclang, label, and kind values can be edited to set the video track language and kind.						</span>
						<button type="button" class="button-link remove-setting remove-track">Remove video track</button><br/>
						<# } ); #>
					<# } else { #>
					<span class="name">Tracks (subtitles, captions, descriptions, chapters, or metadata)</span><br />
					<em>There are no associated subtitles.</em>
					<# } #>
					<textarea class="hidden content-setting">{{ content }}</textarea>
				</span>
			</div>
		</div>
	</script>

		<script type="text/html" id="tmpl-editor-gallery">
		<# if ( data.attachments.length ) { #>
			<div class="gallery gallery-columns-{{ data.columns }}">
				<# _.each( data.attachments, function( attachment, index ) { #>
					<dl class="gallery-item">
						<dt class="gallery-icon">
							<# if ( attachment.thumbnail ) { #>
								<img src="{{ attachment.thumbnail.url }}" width="{{ attachment.thumbnail.width }}" height="{{ attachment.thumbnail.height }}" alt="{{ attachment.alt }}" />
							<# } else { #>
								<img src="{{ attachment.url }}" alt="{{ attachment.alt }}" />
							<# } #>
						</dt>
						<# if ( attachment.caption ) { #>
							<dd class="wp-caption-text gallery-caption">
								{{{ data.verifyHTML( attachment.caption ) }}}
							</dd>
						<# } #>
					</dl>
					<# if ( index % data.columns === data.columns - 1 ) { #>
						<br style="clear: both;">
					<# } #>
				<# } ); #>
			</div>
		<# } else { #>
			<div class="wpview-error">
				<div class="dashicons dashicons-format-gallery"></div><p>No items found.</p>
			</div>
		<# } #>
	</script>

		<script type="text/html" id="tmpl-crop-content">
		<img class="crop-image" src="{{ data.url }}" alt="Image crop area preview. Requires mouse interaction.">
		<div class="upload-errors"></div>
	</script>

		<script type="text/html" id="tmpl-site-icon-preview">
		<h2>Preview</h2>
		<strong aria-hidden="true">As a browser icon</strong>
		<div class="favicon-preview">
			<img src="http://localhost/sekolahku/wp-admin/images/browser.png" class="browser-preview" width="182" height="" alt="" />

			<div class="favicon">
				<img id="preview-favicon" src="{{ data.url }}" alt="Preview as a browser icon" />
			</div>
			<span class="browser-title" aria-hidden="true"><# print( 'Sekolahku' ) #></span>
		</div>

		<strong aria-hidden="true">As an app icon</strong>
		<div class="app-icon-preview">
			<img id="preview-app-icon" src="{{ data.url }}" alt="Preview as an app icon" />
		</div>
	</script>

	<link rel='stylesheet' id='dashicons-css'  href='http://localhost/sekolahku/wp-includes/css/dashicons.min.css?ver=5.8.1' media='all' />
<link rel='stylesheet' id='admin-bar-css'  href='http://localhost/sekolahku/wp-includes/css/admin-bar.min.css?ver=5.8.1' media='all' />
<link rel='stylesheet' id='buttons-css'  href='http://localhost/sekolahku/wp-includes/css/buttons.min.css?ver=5.8.1' media='all' />
<link rel='stylesheet' id='mediaelement-css'  href='http://localhost/sekolahku/wp-includes/js/mediaelement/mediaelementplayer-legacy.min.css?ver=4.2.16' media='all' />
<link rel='stylesheet' id='wp-mediaelement-css'  href='http://localhost/sekolahku/wp-includes/js/mediaelement/wp-mediaelement.min.css?ver=5.8.1' media='all' />
<link rel='stylesheet' id='media-views-css'  href='http://localhost/sekolahku/wp-includes/css/media-views.min.css?ver=5.8.1' media='all' />
<link rel='stylesheet' id='imgareaselect-css'  href='http://localhost/sekolahku/wp-includes/js/imgareaselect/imgareaselect.css?ver=0.9.8' media='all' />
<script src='http://localhost/sekolahku/wp-includes/js/hoverintent-js.min.js?ver=2.2.1' id='hoverintent-js-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/admin-bar.min.js?ver=5.8.1' id='admin-bar-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/underscore.min.js?ver=1.13.1' id='underscore-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/shortcode.min.js?ver=5.8.1' id='shortcode-js'></script>
<script id='utils-js-extra'>
var userSettings = {"url":"\/sekolahku\/","uid":"1","time":"1641364969","secure":""};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/utils.min.js?ver=5.8.1' id='utils-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/jquery/jquery.min.js?ver=3.6.0' id='jquery-core-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/backbone.min.js?ver=1.4.0' id='backbone-js'></script>
<script id='wp-util-js-extra'>
var _wpUtilSettings = {"ajax":{"url":"\/sekolahku\/wp-admin\/admin-ajax.php"}};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/wp-util.min.js?ver=5.8.1' id='wp-util-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/wp-backbone.min.js?ver=5.8.1' id='wp-backbone-js'></script>
<script id='media-models-js-extra'>
var _wpMediaModelsL10n = {"settings":{"ajaxurl":"\/sekolahku\/wp-admin\/admin-ajax.php","post":{"id":0}}};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/media-models.min.js?ver=5.8.1' id='media-models-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/plupload/moxie.min.js?ver=1.3.5' id='moxiejs-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/plupload/plupload.min.js?ver=2.1.9' id='plupload-js'></script>
<!--[if lt IE 8]>
<script src='http://localhost/sekolahku/wp-includes/js/json2.min.js?ver=2015-05-03' id='json2-js'></script>
<![endif]-->
<script id='wp-plupload-js-extra'>
var pluploadL10n = {"queue_limit_exceeded":"You have attempted to queue too many files.","file_exceeds_size_limit":"%s exceeds the maximum upload size for this site.","zero_byte_file":"This file is empty. Please try another.","invalid_filetype":"Sorry, this file type is not permitted for security reasons.","not_an_image":"This file is not an image. Please try another.","image_memory_exceeded":"Memory exceeded. Please try another smaller file.","image_dimensions_exceeded":"This is larger than the maximum size. Please try another.","default_error":"An error occurred in the upload. Please try again later.","missing_upload_url":"There was a configuration error. Please contact the server administrator.","upload_limit_exceeded":"You may only upload 1 file.","http_error":"Unexpected response from the server. The file may have been uploaded successfully. Check in the Media Library or reload the page.","http_error_image":"Post-processing of the image failed likely because the server is busy or does not have enough resources. Uploading a smaller image may help. Suggested maximum size is 2500 pixels.","upload_failed":"Upload failed.","big_upload_failed":"Please try uploading this file with the %1$sbrowser uploader%2$s.","big_upload_queued":"%s exceeds the maximum upload size for the multi-file uploader when used in your browser.","io_error":"IO error.","security_error":"Security error.","file_cancelled":"File canceled.","upload_stopped":"Upload stopped.","dismiss":"Dismiss","crunching":"Crunching\u2026","deleted":"moved to the Trash.","error_uploading":"\u201c%s\u201d has failed to upload.","unsupported_image":"This image cannot be displayed in a web browser. For best results convert it to JPEG before uploading.","noneditable_image":"This image cannot be processed by the web server. Convert it to JPEG or PNG before uploading.","file_url_copied":"The file URL has been copied to your clipboard"};
var _wpPluploadSettings = {"defaults":{"file_data_name":"async-upload","url":"\/sekolahku\/wp-admin\/async-upload.php","filters":{"max_file_size":"41943040b","mime_types":[{"extensions":"jpg,jpeg,jpe,gif,png,bmp,tiff,tif,webp,ico,heic,asf,asx,wmv,wmx,wm,avi,divx,flv,mov,qt,mpeg,mpg,mpe,mp4,m4v,ogv,webm,mkv,3gp,3gpp,3g2,3gp2,txt,asc,c,cc,h,srt,csv,tsv,ics,rtx,css,htm,html,vtt,dfxp,mp3,m4a,m4b,aac,ra,ram,wav,ogg,oga,flac,mid,midi,wma,wax,mka,rtf,js,pdf,class,tar,zip,gz,gzip,rar,7z,psd,xcf,doc,pot,pps,ppt,wri,xla,xls,xlt,xlw,mdb,mpp,docx,docm,dotx,dotm,xlsx,xlsm,xlsb,xltx,xltm,xlam,pptx,pptm,ppsx,ppsm,potx,potm,ppam,sldx,sldm,onetoc,onetoc2,onetmp,onepkg,oxps,xps,odt,odp,ods,odg,odc,odb,odf,wp,wpd,key,numbers,pages"}]},"heic_upload_error":true,"multipart_params":{"action":"upload-attachment","_wpnonce":"5ebc357d2e"}},"browser":{"mobile":false,"supported":true},"limitExceeded":false};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/plupload/wp-plupload.min.js?ver=5.8.1' id='wp-plupload-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/jquery/ui/core.min.js?ver=1.12.1' id='jquery-ui-core-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/jquery/ui/mouse.min.js?ver=1.12.1' id='jquery-ui-mouse-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/jquery/ui/sortable.min.js?ver=1.12.1' id='jquery-ui-sortable-js'></script>
<script id='mediaelement-core-js-before'>
var mejsL10n = {"language":"en","strings":{"mejs.download-file":"Download File","mejs.install-flash":"You are using a browser that does not have Flash player enabled or installed. Please turn on your Flash player plugin or download the latest version from https:\/\/get.adobe.com\/flashplayer\/","mejs.fullscreen":"Fullscreen","mejs.play":"Play","mejs.pause":"Pause","mejs.time-slider":"Time Slider","mejs.time-help-text":"Use Left\/Right Arrow keys to advance one second, Up\/Down arrows to advance ten seconds.","mejs.live-broadcast":"Live Broadcast","mejs.volume-help-text":"Use Up\/Down Arrow keys to increase or decrease volume.","mejs.unmute":"Unmute","mejs.mute":"Mute","mejs.volume-slider":"Volume Slider","mejs.video-player":"Video Player","mejs.audio-player":"Audio Player","mejs.captions-subtitles":"Captions\/Subtitles","mejs.captions-chapters":"Chapters","mejs.none":"None","mejs.afrikaans":"Afrikaans","mejs.albanian":"Albanian","mejs.arabic":"Arabic","mejs.belarusian":"Belarusian","mejs.bulgarian":"Bulgarian","mejs.catalan":"Catalan","mejs.chinese":"Chinese","mejs.chinese-simplified":"Chinese (Simplified)","mejs.chinese-traditional":"Chinese (Traditional)","mejs.croatian":"Croatian","mejs.czech":"Czech","mejs.danish":"Danish","mejs.dutch":"Dutch","mejs.english":"English","mejs.estonian":"Estonian","mejs.filipino":"Filipino","mejs.finnish":"Finnish","mejs.french":"French","mejs.galician":"Galician","mejs.german":"German","mejs.greek":"Greek","mejs.haitian-creole":"Haitian Creole","mejs.hebrew":"Hebrew","mejs.hindi":"Hindi","mejs.hungarian":"Hungarian","mejs.icelandic":"Icelandic","mejs.indonesian":"Indonesian","mejs.irish":"Irish","mejs.italian":"Italian","mejs.japanese":"Japanese","mejs.korean":"Korean","mejs.latvian":"Latvian","mejs.lithuanian":"Lithuanian","mejs.macedonian":"Macedonian","mejs.malay":"Malay","mejs.maltese":"Maltese","mejs.norwegian":"Norwegian","mejs.persian":"Persian","mejs.polish":"Polish","mejs.portuguese":"Portuguese","mejs.romanian":"Romanian","mejs.russian":"Russian","mejs.serbian":"Serbian","mejs.slovak":"Slovak","mejs.slovenian":"Slovenian","mejs.spanish":"Spanish","mejs.swahili":"Swahili","mejs.swedish":"Swedish","mejs.tagalog":"Tagalog","mejs.thai":"Thai","mejs.turkish":"Turkish","mejs.ukrainian":"Ukrainian","mejs.vietnamese":"Vietnamese","mejs.welsh":"Welsh","mejs.yiddish":"Yiddish"}};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/mediaelement/mediaelement-and-player.min.js?ver=4.2.16' id='mediaelement-core-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/mediaelement/mediaelement-migrate.min.js?ver=5.8.1' id='mediaelement-migrate-js'></script>
<script id='mediaelement-js-extra'>
var _wpmejsSettings = {"pluginPath":"\/sekolahku\/wp-includes\/js\/mediaelement\/","classPrefix":"mejs-","stretching":"responsive"};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/mediaelement/wp-mediaelement.min.js?ver=5.8.1' id='wp-mediaelement-js'></script>
<script id='wp-api-request-js-extra'>
var wpApiSettings = {"root":"http:\/\/localhost\/sekolahku\/wp-json\/","nonce":"f03096833b","versionString":"wp\/v2\/"};
</script>
<script src='http://localhost/sekolahku/wp-includes/js/api-request.min.js?ver=5.8.1' id='wp-api-request-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/vendor/regenerator-runtime.min.js?ver=0.13.7' id='regenerator-runtime-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/vendor/wp-polyfill.min.js?ver=3.15.0' id='wp-polyfill-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/dom-ready.min.js?ver=71883072590656bf22c74c7b887df3dd' id='wp-dom-ready-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/hooks.min.js?ver=a7edae857aab69d69fa10d5aef23a5de' id='wp-hooks-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/i18n.min.js?ver=5f1269854226b4dd90450db411a12b79' id='wp-i18n-js'></script>
<script id='wp-i18n-js-after'>
wp.i18n.setLocaleData( { 'text direction\u0004ltr': [ 'ltr' ] } );
</script>
<script id='wp-a11y-js-translations'>
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	wp.i18n.setLocaleData( localeData, domain );
} )( "default", { "locale_data": { "messages": { "": {} } } } );
</script>
<script src='http://localhost/sekolahku/wp-includes/js/dist/a11y.min.js?ver=0ac8327cc1c40dcfdf29716affd7ac63' id='wp-a11y-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/clipboard.min.js?ver=5.8.1' id='clipboard-js'></script>
<script id='media-views-js-extra'>
var _wpMediaViewsL10n = {"mediaFrameDefaultTitle":"Media","url":"URL","addMedia":"Add media","search":"Search","select":"Select","cancel":"Cancel","update":"Update","replace":"Replace","remove":"Remove","back":"Back","selected":"%d selected","dragInfo":"Drag and drop to reorder media files.","uploadFilesTitle":"Upload files","uploadImagesTitle":"Upload images","mediaLibraryTitle":"Media Library","insertMediaTitle":"Add media","createNewGallery":"Create a new gallery","createNewPlaylist":"Create a new playlist","createNewVideoPlaylist":"Create a new video playlist","returnToLibrary":"\u2190 Go to library","allMediaItems":"All media items","allDates":"All dates","noItemsFound":"No items found.","insertIntoPost":"Insert into post","unattached":"Unattached","mine":"Mine","trash":"Trash","uploadedToThisPost":"Uploaded to this post","warnDelete":"You are about to permanently delete this item from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete.","warnBulkDelete":"You are about to permanently delete these items from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete.","warnBulkTrash":"You are about to trash these items.\n  'Cancel' to stop, 'OK' to delete.","bulkSelect":"Bulk select","trashSelected":"Move to Trash","restoreSelected":"Restore from Trash","deletePermanently":"Delete permanently","apply":"Apply","filterByDate":"Filter by date","filterByType":"Filter by type","searchLabel":"Search","searchMediaLabel":"Search media","searchMediaPlaceholder":"Search media items...","mediaFound":"Number of media items found: %d","noMedia":"No media items found.","noMediaTryNewSearch":"No media items found. Try a different search.","attachmentDetails":"Attachment details","insertFromUrlTitle":"Insert from URL","setFeaturedImageTitle":"Featured image","setFeaturedImage":"Set featured image","createGalleryTitle":"Create gallery","editGalleryTitle":"Edit gallery","cancelGalleryTitle":"\u2190 Cancel gallery","insertGallery":"Insert gallery","updateGallery":"Update gallery","addToGallery":"Add to gallery","addToGalleryTitle":"Add to gallery","reverseOrder":"Reverse order","imageDetailsTitle":"Image details","imageReplaceTitle":"Replace image","imageDetailsCancel":"Cancel edit","editImage":"Edit image","chooseImage":"Choose image","selectAndCrop":"Select and crop","skipCropping":"Skip cropping","cropImage":"Crop image","cropYourImage":"Crop your image","cropping":"Cropping\u2026","suggestedDimensions":"Suggested image dimensions: %1$s by %2$s pixels.","cropError":"There has been an error cropping your image.","audioDetailsTitle":"Audio details","audioReplaceTitle":"Replace audio","audioAddSourceTitle":"Add audio source","audioDetailsCancel":"Cancel edit","videoDetailsTitle":"Video details","videoReplaceTitle":"Replace video","videoAddSourceTitle":"Add video source","videoDetailsCancel":"Cancel edit","videoSelectPosterImageTitle":"Select poster image","videoAddTrackTitle":"Add subtitles","playlistDragInfo":"Drag and drop to reorder tracks.","createPlaylistTitle":"Create audio playlist","editPlaylistTitle":"Edit audio playlist","cancelPlaylistTitle":"\u2190 Cancel audio playlist","insertPlaylist":"Insert audio playlist","updatePlaylist":"Update audio playlist","addToPlaylist":"Add to audio playlist","addToPlaylistTitle":"Add to Audio Playlist","videoPlaylistDragInfo":"Drag and drop to reorder videos.","createVideoPlaylistTitle":"Create video playlist","editVideoPlaylistTitle":"Edit video playlist","cancelVideoPlaylistTitle":"\u2190 Cancel video playlist","insertVideoPlaylist":"Insert video playlist","updateVideoPlaylist":"Update video playlist","addToVideoPlaylist":"Add to video playlist","addToVideoPlaylistTitle":"Add to video Playlist","filterAttachments":"Filter media","attachmentsList":"Media list","settings":{"tabs":[],"tabUrl":"http:\/\/localhost\/sekolahku\/wp-admin\/media-upload.php?chromeless=1","mimeTypes":{"image":"Images","audio":"Audio","video":"Video","application\/msword,application\/vnd.openxmlformats-officedocument.wordprocessingml.document,application\/vnd.ms-word.document.macroEnabled.12,application\/vnd.ms-word.template.macroEnabled.12,application\/vnd.oasis.opendocument.text,application\/vnd.apple.pages,application\/pdf,application\/vnd.ms-xpsdocument,application\/oxps,application\/rtf,application\/wordperfect,application\/octet-stream":"Documents","application\/vnd.apple.numbers,application\/vnd.oasis.opendocument.spreadsheet,application\/vnd.ms-excel,application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application\/vnd.ms-excel.sheet.macroEnabled.12,application\/vnd.ms-excel.sheet.binary.macroEnabled.12":"Spreadsheets","application\/x-gzip,application\/rar,application\/x-tar,application\/zip,application\/x-7z-compressed":"Archives"},"captions":true,"nonce":{"sendToEditor":"0bd73b063b"},"post":{"id":0},"defaultProps":{"link":"none","align":"","size":""},"attachmentCounts":{"audio":1,"video":1},"oEmbedProxyUrl":"http:\/\/localhost\/sekolahku\/wp-json\/oembed\/1.0\/proxy","embedExts":["mp3","ogg","flac","m4a","wav","mp4","m4v","webm","ogv","flv"],"embedMimes":{"mp3":"audio\/mpeg","ogg":"audio\/ogg","flac":"audio\/flac","m4a":"audio\/mpeg","wav":"audio\/wav","mp4":"video\/mp4","m4v":"video\/mp4","webm":"video\/webm","ogv":"video\/ogg","flv":"video\/x-flv"},"contentWidth":750,"months":[],"mediaTrash":0,"infiniteScrolling":0}};
</script>
<script id='media-views-js-translations'>
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	wp.i18n.setLocaleData( localeData, domain );
} )( "default", { "locale_data": { "messages": { "": {} } } } );
</script>
<script src='http://localhost/sekolahku/wp-includes/js/media-views.min.js?ver=5.8.1' id='media-views-js'></script>
<script id='media-editor-js-translations'>
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	wp.i18n.setLocaleData( localeData, domain );
} )( "default", { "locale_data": { "messages": { "": {} } } } );
</script>
<script src='http://localhost/sekolahku/wp-includes/js/media-editor.min.js?ver=5.8.1' id='media-editor-js'></script>
<script src='http://localhost/sekolahku/wp-includes/js/media-audiovideo.min.js?ver=5.8.1' id='media-audiovideo-js'></script>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",(function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())}),!1);
	</script>
		<script>
		(function() {
			var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');

				request = true;
	
			b[c] = b[c].replace( rcs, ' ' );
			// The customizer requires postMessage and CORS (if the site is cross domain).
			b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;
		}());
	</script>