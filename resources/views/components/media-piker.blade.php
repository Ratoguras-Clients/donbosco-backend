@push('styles')
    <style>
        .media-item {
            position: relative;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            background: white;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 140px;
        }

        .media-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            transform: translateY(-1px);
        }

        .media-item.selected {
            border-color: #3b82f6 !important;
            background-color: #eff6ff !important;
            transform: translateY(-2px);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .media-item.selected::before {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 24px;
            height: 24px;
            background-color: #3b82f6;
            border-radius: 50%;
            z-index: 10;
        }

        .media-item.selected::after {
            content: '✓';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 24px;
            height: 24px;
            color: white;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            z-index: 11;
        }

        .media-item img {
            transition: all 0.2s ease;
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .media-item.selected img {
            opacity: 0.9;
        }

        .media-upload-area.drag-over {
            border-color: #2563eb;
            background-color: #eff6ff;
        }

        .media-item.selected.multiple-selection::after {
            content: attr(data-selection-order);
            background-color: #059669;
        }

        .media-item .document-thumbnail {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .media-item .file-icon {
            width: 32px;
            height: 32px;
        }

        .media-item .file-type-label {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .media-item .filename {
            font-size: 12px;
            color: #374151;
            font-weight: 500;
            text-align: center;
            word-break: break-word;
            line-height: 1.3;
            max-width: 100%;
        }

        /* Enhanced styles for the details panel */
        .attachment-details-panel {
            border-left: 1px solid #e5e7eb;
            background: #f8fafc;
            overflow-y: auto;
            overflow-x: hidden;
            max-height: calc(100vh - 200px);
            border-radius: 0.5rem;
            padding-bottom: 2rem;
        }

        .attachment-preview {
            max-height: 200px;
            object-fit: contain;
        }

        .detail-input {
            resize: vertical;
            min-height: 80px;
        }

        .media-grid-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-width: 0;
        }

        .media-modal-content {
            display: flex;
            height: calc(90vh - 200px);
            overflow-x: hidden;
            min-width: 0;
        }

        /* Grid layout adjustments */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 16px;
            padding: 4px;
            overflow-x: hidden;
            min-width: 0;
            word-break: break-word;
        }

        /* Update button styling */
        .update-media-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 16px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .update-media-btn:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .update-media-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        /* Bottom selection area styling */
        .selection-footer {
            position: sticky;
            bottom: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 16px;
            margin: 0 -24px -24px -24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selection-info {
            font-size: 14px;
            color: #6b7280;
        }

        .set-featured-btn {
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .set-featured-btn:hover {
            background: #0284c7;
        }

        .set-featured-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* Adjust content area to account for footer */
        .content-with-footer {
            height: calc(90vh - 260px);
        }
    </style>
@endpush

<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-[95%] h-[90vh] flex flex-col max-w-7xl relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
            <button type="button" class="media-picker-close text-gray-500 hover:text-gray-700"
                data-modal="{{ $modalId }}">
                <span class="iconify" data-icon="tabler:x" data-width="24"></span>
            </button>
        </div>

        <div class="flex border-b border-gray-200 mb-4">
            @if ($allowView)
                <button type="button"
                    class="media-tab-button px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600"
                    data-tab="library" data-modal="{{ $modalId }}">Media Library</button>
            @endif
            @if ($allowUpload)
                <button type="button"
                    class="media-tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                    data-tab="upload" data-modal="{{ $modalId }}">Upload Files</button>
            @endif
            <button type="button"
                class="media-tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="url"
                data-modal="{{ $modalId }}">From URL</button>
        </div>

        @if ($allowView)
            <div id="{{ $modalId }}-library-tab" class="media-tab-content flex-grow flex flex-col relative">
                <!-- Filter Section -->
                <div class="mb-4 pb-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700">Filter media</label>
                        <div class="flex items-center gap-3">
                            <select id="{{ $modalId }}-type-filter"
                                class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Media</option>
                                <option value="image">Images</option>
                                <option value="video">Videos</option>
                                <option value="audio">Audio</option>
                                <option value="document">Documents</option>
                            </select>
                            <select id="{{ $modalId }}-date-filter"
                                class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All dates</option>
                                <option value="today">Today</option>
                                <option value="week">This week</option>
                                <option value="month">This month</option>
                                <option value="year">This year</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700">Search media</label>
                        <input type="text" id="{{ $modalId }}-search" placeholder="Search media items..."
                            class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                    </div>
                </div>

                <!-- Main Content Area with Grid and Details Panel -->
                <div class="content-with-footer mb-3">
                    <div class="flex h-full">
                        <!-- Media Grid Container -->
                        <div class="media-grid-container pr-4">
                            <div id="{{ $modalId }}-grid" class="media-grid"></div>
                            <div class="text-center mt-4">
                                <button type="button" id="{{ $modalId }}-load-more"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hidden">Load
                                    More</button>
                                <p id="{{ $modalId }}-no-more" class="text-gray-500 text-sm hidden">No more media
                                    to
                                    load.</p>
                            </div>
                        </div>

                        <!-- Attachment Details Panel -->
                        <div id="{{ $modalId }}-details-panel" class="attachment-details-panel w-80 p-4 hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">ATTACHMENT DETAILS</h3>

                            <!-- Media Preview -->
                            <div id="{{ $modalId }}-preview-container" class="mb-6">
                                <div
                                    class="border border-gray-300 rounded-lg p-3 bg-white flex items-center justify-center min-h-[160px] shadow-sm">
                                    <img id="{{ $modalId }}-preview-image" src="" alt="Preview"
                                        class="attachment-preview max-w-full max-h-40 rounded-lg transition-all duration-200">
                                    <div id="{{ $modalId }}-preview-document"
                                        class="hidden h-36 justify-center items-center bg-gray-100 rounded-lg">
                                        <span class="iconify text-gray-400" data-icon="tabler:file"
                                            data-width="48"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- File Details -->
                            <div id="{{ $modalId }}-file-info" class="mb-4 text-sm text-gray-600">
                                <p id="{{ $modalId }}-file-name" class="font-medium pr4 text-gray-900 mb-1"></p>
                                <p id="{{ $modalId }}-file-date" class="text-gray-500 mb-1"></p>
                                <p id="{{ $modalId }}-file-size" class="text-gray-500 mb-1"></p>
                                <p id="{{ $modalId }}-file-dimensions" class="text-gray-500 hidden"></p>
                            </div>

                            <!-- Form Fields -->
                            <form id="{{ $modalId }}-details-form" class="space-y-4">
                                <div>
                                    <label for="{{ $modalId }}-title"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Image Name
                                    </label>
                                    <input type="text" id="{{ $modalId }}-title" name="filename"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                </div>

                                <div>
                                    <label for="{{ $modalId }}-alt-text"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Alt Text
                                    </label>
                                    <input type="text" id="{{ $modalId }}-alt-text" name="alt_text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="Describe the purpose of the image. Leave empty if the image is purely decorative.">

                                </div>


                                <div>
                                    <label for="{{ $modalId }}-description"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea id="{{ $modalId }}-description" name="description_full" rows="4"
                                        class="detail-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">File URL:</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" id="{{ $modalId }}-file-url" readonly
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-600">
                                        <button type="button" id="{{ $modalId }}-copy-url"
                                            class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm flex items-center gap-2">
                                            <span class="iconify" data-icon="tabler:copy" data-width="16"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="{{ $modalId }}-prev-media"
                                            class="flex-shrink-0 p-2 bg-gray-200 hover:bg-gray-300 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Previous Media">
                                            <span class="iconify" data-icon="tabler:chevron-left"
                                                data-width="20"></span>
                                        </button>
                                        <button type="submit" id="{{ $modalId }}-update-btn"
                                            class="update-media-btn flex-1">
                                            <span class="iconify" data-icon="tabler:device-floppy"
                                                data-width="16"></span>
                                            <span id="{{ $modalId }}-update-btn-text">Update Media</span>
                                        </button>
                                        <button type="button" id="{{ $modalId }}-next-media"
                                            class="flex-shrink-0 p-2 bg-gray-200 hover:bg-gray-300 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Next Media">
                                            <span class="iconify" data-icon="tabler:chevron-right"
                                                data-width="20"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bottom Selection Footer -->
                <div class="selection-footer">
                    <div class="selection-info">
                        <span id="{{ $modalId }}-selection-info">1 item selected</span>
                    </div>
                    <button type="button" class="set-featured-btn media-confirm-select"
                        data-modal="{{ $modalId }}" disabled>
                        Set featured image
                    </button>
                </div>
            </div>
        @endif

        @if ($allowUpload)
            <div id="{{ $modalId }}-upload-tab" class="media-tab-content flex-grow p-2 hidden">
                <div class="media-drop-area h-[50%] justify-center flex flex-col items-center border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 cursor-pointer transition-colors"
                    data-modal="{{ $modalId }}">
                    <input type="file" class="media-file-input sr-only" {{ $allowMultiple ? 'multiple' : '' }}
                        accept="{{ $acceptedTypes }}" data-modal="{{ $modalId }}">
                    <span class="iconify text-gray-400 mb-2" data-icon="tabler:cloud-upload" data-width="48"></span>
                    <p class="text-gray-700 font-semibold mb-1">Drag & drop files here or click to upload</p>
                    <p class="text-gray-500 text-sm">Max file size: {{ $maxFileSize }}. Supported types:
                        {{ str_replace(',', ', ', $acceptedTypes) }}.</p>
                </div>
                <div id="{{ $modalId }}-progress-container" class="mt-4 hidden">
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="{{ $modalId }}-progress"
                            class="h-full bg-blue-600 rounded-full transition-all duration-300" style="width: 0%">
                        </div>
                    </div>
                </div>
                <p id="{{ $modalId }}-message" class="mt-4 text-sm hidden"></p>
            </div>
        @endif

        <div id="{{ $modalId }}-url-tab" class="media-tab-content flex-grow p-4 hidden">
            <div class="max-w-md mx-auto">
                <div class="mb-4">
                    <label for="{{ $modalId }}-image-url" class="block text-sm font-medium text-gray-700 mb-2">
                        Image URL
                    </label>
                    <input type="url" id="{{ $modalId }}-image-url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="https://example.com/image.jpg">
                </div>
                <div class="mb-4">
                    <label for="{{ $modalId }}-alt-text-url"
                        class="block text-sm font-medium text-gray-700 mb-2">
                        Alt Text
                    </label>
                    <input type="text" id="{{ $modalId }}-alt-text-url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Description of the image">
                </div>
                <div class="mb-4">
                    <button type="button" id="{{ $modalId }}-download-btn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 font-medium">
                        Download Media
                    </button>
                </div>
                <div id="{{ $modalId }}-url-progress-container" class="mt-4 hidden">
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="{{ $modalId }}-url-progress"
                            class="h-full bg-blue-600 rounded-full transition-all duration-300" style="width: 0%">
                        </div>
                    </div>
                </div>
                <p id="{{ $modalId }}-url-message" class="mt-4 text-sm hidden"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        class MediaPickerClass {
            constructor(modalId, options = {}) {
                this.modalId = modalId;
                this.options = {
                    allowMultiple: {{ $allowMultiple ? 'true' : 'false' }},
                    allowUpload: {{ $allowUpload ? 'true' : 'false' }},
                    allowView: {{ $allowView ? 'true' : 'false' }},
                    onSelect: {!! $onSelect ? "window.{$onSelect}" : 'null' !!},
                    triggerSelector: '{{ $triggerSelector }}',
                    inputTarget: '{{ $inputTarget }}',
                    uploadRoute: '{{ route('dashboard.media.upload') }}',
                    mediaRoute: '{{ route('dashboard.media.index') }}',
                    downloadRoute: '{{ route('dashboard.media.download') }}',
                    updateRoute: '{{ route('dashboard.media.update', ':id') }}',
                    ...options
                };
                this.currentPage = 1;
                this.isLoading = false;
                this.hasMore = true;
                this.selectedMedia = this.options.allowMultiple ? [] : null;
                this.needsRefresh = false;
                this.currentFilters = {
                    type_filter: '',
                    date_filter: '',
                    search: ''
                };
                this.currentMediaData = null;
                this.init();
            }

            init() {
                this.bindEvents();
                if (this.options.triggerSelector) {
                    $(document).on('click', this.options.triggerSelector, () => this.open());
                }
            }

            bindEvents() {
                const modal = `#${this.modalId}`;
                $(document).on('click', `[data-modal="${this.modalId}"].media-picker-close`, () => this.close());
                $(document).on('click', `[data-modal="${this.modalId}"].media-tab-button`, (e) => {
                    const tab = $(e.target).data('tab');
                    this.switchTab(tab);
                });
                $(document).on('click', `${modal}-grid .media-item`, (e) => {
                    this.selectMedia($(e.currentTarget));
                    // Always update preview image in details panel on click
                    const $item = $(e.currentTarget);
                    const isImage = $item.data('media-is-image') === true || $item.data('media-is-image') ===
                        'true';
                    const url = $item.data('media-url');
                    const alt = $item.data('media-alt') || '';
                    if (isImage && url) {
                        $(`${modal}-preview-image`).attr('src', url).attr('alt', alt).removeClass('hidden');
                        $(`${modal}-preview-document`).addClass('hidden');
                    } else {
                        $(`${modal}-preview-image`).addClass('hidden');
                        $(`${modal}-preview-document`).removeClass('hidden');
                    }
                });
                $(document).on('click', `${modal}-load-more`, () => {
                    this.currentPage++;
                    this.loadMedia(true);
                });
                $(document).on('click', `[data-modal="${this.modalId}"].media-confirm-select`, () => {
                    this.confirmSelection();
                });

                // Filter event handlers
                $(document).on('change', `${modal}-type-filter`, (e) => {
                    this.currentFilters.type_filter = e.target.value;
                    this.applyFilters();
                });

                $(document).on('change', `${modal}-date-filter`, (e) => {
                    this.currentFilters.date_filter = e.target.value;
                    this.applyFilters();
                });

                $(document).on('input', `${modal}-search`, (e) => {
                    this.currentFilters.search = e.target.value;
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.applyFilters();
                    }, 300);
                });

                // Details panel events
                $(document).on('submit', `${modal}-details-form`, (e) => {
                    e.preventDefault();
                    this.updateMediaDetails();
                });

                $(document).on('click', `${modal}-copy-url`, () => {
                    this.copyUrlToClipboard();
                });

                // Navigation buttons events
                $(document).on('click', `${modal}-prev-media`, () => {
                    this.navigateMedia('prev');
                });

                $(document).on('click', `${modal}-next-media`, () => {
                    this.navigateMedia('next');
                });

                // Keyboard navigation support
                $(document).on('keydown', (e) => {
                    if ($(`#${this.modalId}`).is(':visible') && $(`${modal}-details-panel`).is(':visible')) {
                        if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            this.navigateMedia('prev');
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            this.navigateMedia('next');
                        }
                    }
                });

                if (this.options.allowUpload) {
                    this.bindUploadEvents();
                }
                this.bindUrlEvents();
            }

            createMediaItemHtml(media) {
                let thumbnailHtml = '';
                const isImage = media.is_image;

                if (isImage) {
                    thumbnailHtml = `<img src="${media.url}" alt="${media.alt_text || ''}" loading="lazy">`;
                } else {
                    let iconClass = 'tabler:file';
                    let iconColor = 'text-gray-600';
                    const ext = media.extension ? media.extension.toLowerCase() : '';

                    switch (ext) {
                        case 'pdf':
                            iconClass = 'tabler:file-type-pdf';
                            iconColor = 'text-red-600';
                            break;
                        case 'doc':
                        case 'docx':
                            iconClass = 'tabler:file-type-doc';
                            iconColor = 'text-blue-600';
                            break;
                        case 'xls':
                        case 'xlsx':
                            iconClass = 'tabler:file-type-xls';
                            iconColor = 'text-green-600';
                            break;
                        case 'ppt':
                        case 'pptx':
                            iconClass = 'tabler:file-type-ppt';
                            iconColor = 'text-orange-600';
                            break;
                        case 'txt':
                            iconClass = 'tabler:file-type-txt';
                            iconColor = 'text-gray-600';
                            break;
                        case 'mp3':
                        case 'wav':
                        case 'ogg':
                            iconClass = 'tabler:file-music';
                            iconColor = 'text-purple-600';
                            break;
                        case 'mp4':
                        case 'avi':
                        case 'mov':
                        case 'wmv':
                            iconClass = 'tabler:video';
                            iconColor = 'text-indigo-600';
                            break;
                    }

                    thumbnailHtml = `
                        <div class="document-thumbnail">
                            <span class="iconify file-icon ${iconColor}" data-icon="${iconClass}" data-width="32"></span>
                        </div>
                        ${media.extension ? `<div class="file-type-label">${media.extension.toUpperCase()}</div>` : ''}
                    `;
                }

                return `<div class="media-item"
                    data-media-id="${media.id}"
                    data-media-url="${media.url}"
                    data-media-alt="${media.alt_text || ''}"
                    data-media-filename="${media.filename || ''}"
                    data-media-description="${media.description || ''}"
                    data-media-mime="${media.mime_type || ''}"
                    data-media-filesize="${media.filesize || ''}"
                    data-media-created="${media.created_at || ''}"
                    data-media-formatted-date="${media.formatted_date || ''}"
                    data-media-extension="${media.extension || ''}"
                    data-media-is-image="${isImage}"
                    data-media-is-document="${media.is_document || false}"
                    data-media-dimensions="${media.dimensions || ''}">
                    ${thumbnailHtml}
                    <div class="filename">${media.filename || 'Untitled'}</div>
                </div>`;
            }

            selectMedia($item) {
                const mediaId = $item.data('media-id');
                const modal = `#${this.modalId}`;

                // Remove previous selections if not multiple
                if (!this.options.allowMultiple) {
                    $(`${modal}-grid .media-item`).removeClass('selected');
                }

                $item.addClass('selected');
                this.selectedMedia = mediaId;

                // Load media details from data attributes
                this.loadMediaDetailsFromItem($item);
                this.showDetailsPanel();
                this.addSelectionFeedback($item);
                this.updateSelectionCounter();
                this.updateNavigationButtons();
            }

            updateSelectionCounter() {
                const modal = `#${this.modalId}`;
                const confirmBtn = $(`.media-confirm-select[data-modal="${this.modalId}"]`);
                const infoText = $(`#${this.modalId}-selection-info`);

                if (this.selectedMedia) {
                    confirmBtn.prop('disabled', false);
                    infoText.text('1 item selected');
                } else {
                    confirmBtn.prop('disabled', true);
                    infoText.text('Click images to select');
                }
            }

            // Include all other methods from the previous implementation...
            loadMediaDetailsFromItem($item) {
                const mediaData = {
                    id: $item.data('media-id'),
                    url: $item.data('media-url'),
                    alt_text: $item.data('media-alt') || '',
                    filename: $item.data('media-filename') || '',
                    description: $item.data('media-description') || '',
                    mime_type: $item.data('media-mime') || '',
                    filesize: parseInt($item.data('media-filesize')) || 0,
                    created_at: $item.data('media-created') || '',
                    formatted_date: $item.data('media-formatted-date') || '',
                    extension: $item.data('media-extension') || '',
                    is_image: $item.data('media-is-image') === true || $item.data('media-is-image') === 'true',
                    is_document: $item.data('media-is-document') === true || $item.data('media-is-document') ===
                        'true',
                    dimensions: $item.data('media-dimensions') || ''
                };

                console.log('Loading media details:', mediaData); // Debug log
                this.currentMediaData = mediaData;
                this.populateDetailsPanel(mediaData);
            }

            populateDetailsPanel(media) {
                const modal = `#${this.modalId}`;

                console.log('Populating details panel:', media); // Debug log

                // Update preview with improved image loading
                if (media.is_image && media.url) {
                    const $previewImg = $(`${modal}-preview-image`);
                    const $previewDoc = $(`${modal}-preview-document`);

                    // Force image reload by setting src to empty first, then to the new URL
                    $previewImg.attr('src', '').off('load error').on('load', function() {
                        console.log('Image loaded successfully:', media.url);
                        $(this).removeClass('hidden');
                        $previewDoc.addClass('hidden');
                    }).on('error', function() {
                        console.error('Failed to load image:', media.url);
                        $(this).addClass('hidden');
                        $previewDoc.removeClass('hidden').addClass('flex');
                    }).attr('src', media.url).attr('alt', media.alt_text || '');
                } else {
                    console.log('Not an image or no URL:', media);
                    $(`${modal}-preview-image`).addClass('hidden');
                    $(`${modal}-preview-document`).removeClass('hidden').addClass('flex');
                    this.updateDocumentIcon(media.extension);
                }

                // Update file info with dynamic data
                $(`${modal}-file-name`).text(media.filename || 'Unknown filename');
                $(`${modal}-file-date`).text(media.formatted_date || this.formatDate(media.created_at));
                $(`${modal}-file-size`).text(this.formatFileSize(media.filesize));

                // Add dimensions for images
                if (media.is_image && media.dimensions) {
                    $(`${modal}-file-dimensions`).text(media.dimensions).show();
                } else {
                    $(`${modal}-file-dimensions`).hide();
                }

                // Populate form fields with actual data
                $(`${modal}-alt-text`).val(media.alt_text || '');
                $(`${modal}-title`).val(media.filename || '');
                $(`${modal}-description`).val(media.description || '');
                $(`${modal}-file-url`).val(media.url || '');
            }

            // Continue with all other methods from the previous implementation
            // (loadMedia, showDetailsPanel, hideDetailsPanel, updateDocumentIcon, updateMediaDetails, etc.)
            // I'll include the essential ones here and you can add the rest...

            loadMedia(append = false) {
                if (!this.options.allowView || this.isLoading) return;

                if (!append && !this.hasMore && this.currentPage > 1) {
                    return;
                }

                this.isLoading = true;
                const modal = `#${this.modalId}`;
                const loadMoreBtn = $(`${modal}-load-more`);
                const noMoreMsg = $(`${modal}-no-more`);

                if (loadMoreBtn.length) {
                    loadMoreBtn.prop('disabled', true).html(
                        '<span class="iconify animate-spin" data-icon="tabler:loader" data-width="20"></span> Loading...'
                    );
                }

                const requestData = {
                    page: this.currentPage,
                    trashed: false,
                    picker: true
                };

                if (this.currentFilters.type_filter && this.currentFilters.type_filter.trim() !== '') {
                    requestData.type_filter = this.currentFilters.type_filter;
                }
                if (this.currentFilters.date_filter && this.currentFilters.date_filter.trim() !== '') {
                    requestData.date_filter = this.currentFilters.date_filter;
                }
                if (this.currentFilters.search && this.currentFilters.search.trim() !== '') {
                    requestData.search = this.currentFilters.search;
                }

                $.ajax({
                    url: this.options.mediaRoute,
                    method: 'GET',
                    data: requestData,
                    success: (response) => {
                        const container = $(`${modal}-grid`);

                        if (append) {
                            if (response.medias && response.medias.length > 0) {
                                response.medias.forEach(media => {
                                    container.append(this.createMediaItemHtml(media));
                                });
                            }
                        } else {
                            container.empty();
                            if (response.medias && response.medias.length > 0) {
                                response.medias.forEach(media => {
                                    container.append(this.createMediaItemHtml(media));
                                });
                            }
                            this.hideDetailsPanel();
                            this.selectedMedia = null;
                            this.currentMediaData = null;
                        }

                        this.restoreSelections();
                        this.hasMore = response.has_more;
                        this.updateNavigationButtons();

                        if (loadMoreBtn.length) {
                            if (this.hasMore) {
                                loadMoreBtn.show().prop('disabled', false).html('Load More');
                                noMoreMsg.hide();
                            } else {
                                loadMoreBtn.hide();
                                const hasContent = response.medias && response.medias.length > 0;
                                if (hasContent || this.currentPage > 1) {
                                    if (noMoreMsg.length) noMoreMsg.text('No more media to load.').show();
                                } else {
                                    const hasFilters = this.currentFilters.type_filter || this
                                        .currentFilters.date_filter || this.currentFilters.search;
                                    if (hasFilters) {
                                        if (noMoreMsg.length) noMoreMsg.text(
                                            'No media found matching your criteria.').show();
                                    } else {
                                        if (noMoreMsg.length) noMoreMsg.text('No media available.').show();
                                    }
                                }
                            }
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('Failed to load media:', xhr, status, error);
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', 'Failed to load media.');
                        }
                        if (loadMoreBtn.length) loadMoreBtn.hide();
                        if (noMoreMsg.length) noMoreMsg.text('Failed to load media.').show();
                    },
                    complete: () => {
                        this.isLoading = false;
                    }
                });
            }

            showDetailsPanel() {
                const modal = `#${this.modalId}`;
                $(`${modal}-details-panel`).removeClass('hidden');
                this.updateNavigationButtons();
            }

            hideDetailsPanel() {
                const modal = `#${this.modalId}`;
                $(`${modal}-details-panel`).addClass('hidden');
            }

            navigateMedia(direction) {
                const modal = `#${this.modalId}`;
                const mediaItems = $(`${modal}-grid .media-item`);

                if (mediaItems.length === 0) return;

                // Save current media details before navigating
                this.saveCurrentMediaSilently(() => {
                    let currentIndex = -1;
                    let targetMediaId = null;

                    if (this.selectedMedia) {
                        currentIndex = mediaItems.index($(
                            `${modal}-grid .media-item[data-media-id="${this.selectedMedia}"]`));
                    }

                    let nextIndex;
                    if (direction === 'next') {
                        nextIndex = currentIndex < mediaItems.length - 1 ? currentIndex + 1 : 0;
                    } else {
                        nextIndex = currentIndex > 0 ? currentIndex - 1 : mediaItems.length - 1;
                    }

                    const $nextItem = mediaItems.eq(nextIndex);
                    if ($nextItem.length) {
                        targetMediaId = $nextItem.data('media-id');
                    }

                    // Refresh media data from API and then select the target item
                    this.refreshMediaAndSelect(targetMediaId);
                });
            }

            refreshMediaAndSelect(targetMediaId) {
                // Refresh the media library to get fresh data
                const modal = `#${this.modalId}`;

                // Show loading state
                const prevBtn = $(`${modal}-prev-media`);
                const nextBtn = $(`${modal}-next-media`);
                prevBtn.prop('disabled', true);
                nextBtn.prop('disabled', true);

                // Reset to first page and reload media
                this.currentPage = 1;
                this.hasMore = true;

                if (!this.options.allowView || this.isLoading) return;

                this.isLoading = true;
                const loadMoreBtn = $(`${modal}-load-more`);
                const noMoreMsg = $(`${modal}-no-more`);

                const requestData = {
                    page: this.currentPage,
                    trashed: false,
                    picker: true
                };

                // Apply current filters
                if (this.currentFilters.type_filter && this.currentFilters.type_filter.trim() !== '') {
                    requestData.type_filter = this.currentFilters.type_filter;
                }
                if (this.currentFilters.date_filter && this.currentFilters.date_filter.trim() !== '') {
                    requestData.date_filter = this.currentFilters.date_filter;
                }
                if (this.currentFilters.search && this.currentFilters.search.trim() !== '') {
                    requestData.search = this.currentFilters.search;
                }

                $.ajax({
                    url: this.options.mediaRoute,
                    method: 'GET',
                    data: requestData,
                    success: (response) => {
                        const container = $(`${modal}-grid`);

                        // Clear and repopulate the grid
                        container.empty();
                        if (response.medias && response.medias.length > 0) {
                            response.medias.forEach(media => {
                                container.append(this.createMediaItemHtml(media));
                            });
                        }

                        this.hasMore = response.has_more;

                        // Try to select the target media item
                        if (targetMediaId) {
                            const $targetItem = $(
                                `${modal}-grid .media-item[data-media-id="${targetMediaId}"]`);
                            if ($targetItem.length) {
                                // Remove current selection and select the target item
                                $(`${modal}-grid .media-item`).removeClass('selected');
                                this.selectMedia($targetItem);

                                // Scroll into view
                                $targetItem[0].scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            } else {
                                // If target item not found, select the first available item
                                const $firstItem = $(`${modal}-grid .media-item`).first();
                                if ($firstItem.length) {
                                    this.selectMedia($firstItem);
                                }
                            }
                        }

                        // Update navigation buttons
                        this.updateNavigationButtons();
                    },
                    error: (xhr, status, error) => {
                        console.error('Failed to refresh media:', xhr, status, error);
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', 'Failed to refresh media.');
                        }

                        // Re-enable navigation buttons even on error
                        prevBtn.prop('disabled', false);
                        nextBtn.prop('disabled', false);
                    },
                    complete: () => {
                        this.isLoading = false;

                        // Re-enable navigation buttons
                        prevBtn.prop('disabled', false);
                        nextBtn.prop('disabled', false);
                    }
                });
            }

            updateNavigationButtons() {
                const modal = `#${this.modalId}`;
                const mediaItems = $(`${modal}-grid .media-item`);
                const prevBtn = $(`${modal}-prev-media`);
                const nextBtn = $(`${modal}-next-media`);

                if (mediaItems.length <= 1 || !this.selectedMedia) {
                    prevBtn.prop('disabled', true);
                    nextBtn.prop('disabled', true);
                    return;
                }

                const currentIndex = mediaItems.index($(
                    `${modal}-grid .media-item[data-media-id="${this.selectedMedia}"]`));

                // Enable/disable buttons based on position
                prevBtn.prop('disabled', false);
                nextBtn.prop('disabled', false);
            }

            updateDocumentIcon(extension) {
                const modal = `#${this.modalId}`;
                const iconContainer = $(`${modal}-preview-document span.iconify`);

                let iconClass = 'tabler:file';
                let iconColor = 'text-gray-400';

                if (extension) {
                    const ext = extension.toLowerCase();
                    switch (ext) {
                        case 'pdf':
                            iconClass = 'tabler:file-type-pdf';
                            iconColor = 'text-red-500';
                            break;
                        case 'doc':
                        case 'docx':
                            iconClass = 'tabler:file-type-doc';
                            iconColor = 'text-blue-500';
                            break;
                        case 'xls':
                        case 'xlsx':
                            iconClass = 'tabler:file-type-xls';
                            iconColor = 'text-green-500';
                            break;
                        case 'ppt':
                        case 'pptx':
                            iconClass = 'tabler:file-type-ppt';
                            iconColor = 'text-orange-500';
                            break;
                        case 'mp3':
                        case 'wav':
                        case 'ogg':
                            iconClass = 'tabler:file-music';
                            iconColor = 'text-purple-500';
                            break;
                        case 'mp4':
                        case 'avi':
                        case 'mov':
                            iconClass = 'tabler:video';
                            iconColor = 'text-indigo-500';
                            break;
                    }
                }

                if (iconContainer.length) {
                    iconContainer.attr('data-icon', iconClass).removeClass().addClass(`iconify ${iconColor}`);
                }
            }

            updateMediaDetails() {
                if (!this.currentMediaData) return;

                const modal = `#${this.modalId}`;
                const formData = {
                    alt_text: $(`${modal}-alt-text`).val(),
                    description: $(`${modal}-description`).val(),
                    filename: $(`${modal}-title`).val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                const updateUrl = this.options.updateRoute.replace(':id', this.currentMediaData.id);

                $.ajax({
                    url: updateUrl,
                    method: 'PATCH',
                    data: formData,
                    success: (response) => {
                        if (response.success) {
                            if (typeof nepalToast !== 'undefined') {
                                nepalToast.success('Success', response.message);
                            } else {
                                alert(response.message);
                            }
                            this.currentMediaData = {
                                ...this.currentMediaData,
                                ...response.media
                            };
                            this.updateGridItemData(response.media);
                        }
                    },
                    error: (xhr) => {
                        const errorMessage = xhr.responseJSON?.message || 'Failed to update media details';
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', errorMessage);
                        } else {
                            alert(errorMessage);
                        }
                    }
                });
            }

            saveCurrentMediaSilently(callback) {
                if (!this.currentMediaData) {
                    if (callback) callback();
                    return;
                }

                const modal = `#${this.modalId}`;
                const formData = {
                    alt_text: $(`${modal}-alt-text`).val(),
                    description: $(`${modal}-description`).val(),
                    filename: $(`${modal}-title`).val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                const updateUrl = this.options.updateRoute.replace(':id', this.currentMediaData.id);

                $.ajax({
                    url: updateUrl,
                    method: 'PATCH',
                    data: formData,
                    success: (response) => {
                        if (response.success) {
                            this.currentMediaData = {
                                ...this.currentMediaData,
                                ...response.media
                            };
                            this.updateGridItemData(response.media);
                        }
                        if (callback) callback();
                    },
                    error: (xhr) => {
                        // Silently handle errors during navigation saves
                        console.warn('Failed to auto-save media details:', xhr.responseJSON?.message);
                        if (callback) callback();
                    }
                });
            }

            updateGridItemData(media) {
                const modal = `#${this.modalId}`;
                const $item = $(`${modal}-grid .media-item[data-media-id="${media.id}"]`);
                if ($item.length) {
                    $item.attr('data-media-alt', media.alt_text);
                    $item.attr('data-media-filename', media.filename);
                    $item.attr('data-media-description', media.description);
                    // Update filename display
                    $item.find('.filename').text(media.filename);
                    // Update image alt if it's an image
                    const $img = $item.find('img');
                    if ($img.length) {
                        $img.attr('alt', media.alt_text);
                    }
                }
            }

            copyUrlToClipboard() {
                const modal = `#${this.modalId}`;
                const urlField = $(`${modal}-file-url`)[0];

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(urlField.value).then(() => {
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.success('Success', 'URL copied to clipboard!');
                        } else {
                            alert('URL copied to clipboard!');
                        }
                    });
                } else {
                    urlField.select();
                    document.execCommand('copy');
                    if (typeof nepalToast !== 'undefined') {
                        nepalToast.success('Success', 'URL copied to clipboard!');
                    } else {
                        alert('URL copied to clipboard!');
                    }
                }
            }

            formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            formatFileSize(bytes) {
                if (!bytes) return '';
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                if (bytes === 0) return '0 Bytes';
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
            }

            applyFilters() {
                this.currentPage = 1;
                this.hasMore = true;
                this.loadMedia(false);
            }

            addSelectionFeedback($item) {
                $item.addClass('animate-pulse');
                setTimeout(() => {
                    $item.removeClass('animate-pulse');
                }, 200);
            }

            restoreSelections() {
                const modal = `#${this.modalId}`;
                if (!this.options.allowMultiple && this.selectedMedia) {
                    const $item = $(`${modal}-grid .media-item[data-media-id="${this.selectedMedia}"]`);
                    if ($item.length) {
                        $item.addClass('selected');
                        this.loadMediaDetailsFromItem($item);
                        this.showDetailsPanel();
                    }
                }
            }

            refreshMediaLibrary() {
                if (!this.options.allowView) return;
                this.currentPage = 1;
                this.hasMore = true;
                this.loadMedia(false);
            }

            bindUrlEvents() {
                const downloadBtn = $(`#${this.modalId}-download-btn`);
                downloadBtn.off('click');
                downloadBtn.on('click', () => {
                    this.downloadFromUrl();
                });
            }

            bindUploadEvents() {
                const modal = `#${this.modalId}`;
                const dropArea = $(`.media-drop-area[data-modal="${this.modalId}"]`);
                const fileInput = $(`.media-file-input[data-modal="${this.modalId}"]`);

                dropArea.off('click dragover dragleave drop');
                fileInput.off('change');

                let fileDialogOpen = false;
                dropArea.on('click', () => {
                    if (!fileDialogOpen) {
                        fileDialogOpen = true;
                        fileInput[0].click();
                        setTimeout(() => {
                            fileDialogOpen = false;
                        }, 500);
                    }
                });
                dropArea.on('dragover', (e) => {
                    e.preventDefault();
                    dropArea.addClass('drag-over');
                });
                dropArea.on('dragleave', () => {
                    dropArea.removeClass('drag-over');
                });
                dropArea.on('drop', (e) => {
                    e.preventDefault();
                    dropArea.removeClass('drag-over');
                    this.handleFiles(e.originalEvent.dataTransfer.files);
                });
                fileInput.on('change', (e) => {
                    this.handleFiles(e.target.files);
                });
            }

            open() {
                $(`#${this.modalId}`).removeClass('hidden').addClass('flex');
                this.reset();
                if (this.options.allowView) {
                    this.loadMedia(false);
                }
            }

            close() {
                $(`#${this.modalId}`).addClass('hidden').removeClass('flex');
                this.hideDetailsPanel();
            }

            switchTab(tab) {
                const modal = `#${this.modalId}`;
                $(`.media-tab-button[data-modal="${this.modalId}"]`)
                    .removeClass('text-blue-600 border-b-2 border-blue-600').addClass('text-gray-500');
                $(`.media-tab-button[data-modal="${this.modalId}"][data-tab="${tab}"]`)
                    .removeClass('text-gray-500').addClass('text-blue-600 border-b-2 border-blue-600');
                $('.media-tab-content').addClass('hidden');
                $(`${modal}-${tab}-tab`).removeClass('hidden');

                if (tab === 'library' && this.needsRefresh && this.options.allowView) {
                    this.refreshMediaLibrary();
                    this.needsRefresh = false;
                }

                if (tab !== 'library') {
                    this.hideDetailsPanel();
                }
            }

            handleFiles(files) {
                if (!files.length) return;
                const modal = `#${this.modalId}`;
                const progressContainer = $(`${modal}-progress-container`);
                const progressBar = $(`${modal}-progress`);
                const message = $(`${modal}-message`);
                progressContainer.removeClass('hidden');
                progressBar.css('width', '0%');
                message.addClass('hidden').removeClass('text-green-600 text-red-600');
                if (files.length > 1) this.uploadMultipleFiles(files);
                else this.uploadSingleFile(files[0]);
            }

            uploadSingleFile(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                const modal = `#${this.modalId}`;
                const progressContainer = $(`${modal}-progress-container`);
                const progressBar = $(`${modal}-progress`);
                const message = $(`${modal}-message`);
                $.ajax({
                    url: this.options.uploadRoute,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: () => {
                        const xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                const percent = (e.loaded / e.total) * 100;
                                progressBar.css('width', percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: (response) => {
                        if (response.success) {
                            message.removeClass('hidden').addClass('text-green-600').text(response.message);
                            this.refreshMediaLibrary();
                            $(`.media-file-input[data-modal="${this.modalId}"]`).val('');
                            this.needsRefresh = true;
                        } else {
                            message.removeClass('hidden').addClass('text-red-600').text(response.message);
                        }
                    },
                    error: (xhr) => {
                        const errorMessage = xhr.responseJSON?.message || 'Upload failed';
                        message.removeClass('hidden').addClass('text-red-600').text(errorMessage);
                    },
                    complete: () => {
                        setTimeout(() => {
                            progressContainer.addClass('hidden');
                            progressBar.css('width', '0%');
                        }, 2000);
                    }
                });
            }

            uploadMultipleFiles(files) {
                const modal = `#${this.modalId}`;
                const progressContainer = $(`${modal}-progress-container`);
                const progressBar = $(`${modal}-progress`);
                const message = $(`${modal}-message`);
                let completedUploads = 0,
                    successfulUploads = 0,
                    failedUploads = 0,
                    totalFiles = files.length;
                message.removeClass('hidden').addClass('text-blue-600').text(`Uploading ${totalFiles} files...`);
                Array.from(files).forEach((file, index) => {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    $.ajax({
                        url: this.options.uploadRoute,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: (response) => {
                            completedUploads++;
                            if (response.success) {
                                successfulUploads++;
                            } else {
                                failedUploads++;
                            }
                            const percent = (completedUploads / totalFiles) * 100;
                            progressBar.css('width', percent + '%');
                            if (completedUploads < totalFiles) {
                                message.text(
                                    `Uploading... ${completedUploads}/${totalFiles} completed`);
                            } else {
                                this.showUploadResults(successfulUploads, failedUploads,
                                    totalFiles);
                                if (successfulUploads > 0) {
                                    this.refreshMediaLibrary();
                                    this.needsRefresh = true;
                                }
                            }
                        },
                        error: (xhr) => {
                            completedUploads++;
                            failedUploads++;
                            const percent = (completedUploads / totalFiles) * 100;
                            progressBar.css('width', percent + '%');
                            if (completedUploads === totalFiles) {
                                this.showUploadResults(successfulUploads, failedUploads,
                                    totalFiles);
                            }
                        }
                    });
                });
                $(`.media-file-input[data-modal="${this.modalId}"]`).val('');
            }

            downloadFromUrl() {
                const modal = `#${this.modalId}`;
                const imageUrl = $(`${modal}-image-url`).val().trim();
                const altText = $(`${modal}-alt-text-url`).val().trim();
                const downloadBtn = $(`${modal}-download-btn`);
                const progressContainer = $(`${modal}-url-progress-container`);
                const progressBar = $(`${modal}-url-progress`);
                const message = $(`${modal}-url-message`);

                if (!imageUrl) {
                    message.removeClass('hidden text-green-600').addClass('text-red-600').text(
                        'Please enter a valid image URL.');
                    return;
                }

                try {
                    new URL(imageUrl);
                } catch (e) {
                    message.removeClass('hidden text-green-600').addClass('text-red-600').text(
                        'Please enter a valid URL format.');
                    return;
                }

                downloadBtn.prop('disabled', true).html(
                    '<span class="iconify animate-spin" data-icon="tabler:loader" data-width="20"></span> Downloading...'
                );
                progressContainer.removeClass('hidden');
                progressBar.css('width', '50%');
                message.addClass('hidden').removeClass('text-green-600 text-red-600');

                const formData = new FormData();
                formData.append('url', imageUrl);
                formData.append('alt_text', altText);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: this.options.downloadRoute,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (response) => {
                        progressBar.css('width', '100%');
                        if (response.success) {
                            message.removeClass('hidden text-red-600').addClass('text-green-600').text(
                                response.message);
                            $(`${modal}-image-url`).val('');
                            $(`${modal}-alt-text-url`).val('');
                            this.refreshMediaLibrary();
                            this.needsRefresh = true;
                        } else {
                            message.removeClass('hidden text-green-600').addClass('text-red-600').text(
                                response.message || 'Download failed.');
                        }
                    },
                    error: (xhr) => {
                        progressBar.css('width', '100%');
                        const errorMessage = xhr.responseJSON?.message ||
                            'Download failed. Please try again.';
                        message.removeClass('hidden text-green-600').addClass('text-red-600').text(
                            errorMessage);
                    },
                    complete: () => {
                        downloadBtn.prop('disabled', false).html('Download Media');
                        setTimeout(() => {
                            progressContainer.addClass('hidden');
                            progressBar.css('width', '0%');
                        }, 2000);
                    }
                });
            }

            showUploadResults(successful, failed, total) {
                const modal = `#${this.modalId}`;
                const message = $(`${modal}-message`);
                const progressContainer = $(`${modal}-progress-container`);
                const progressBar = $(`${modal}-progress`);
                if (failed === 0) {
                    message.removeClass('text-blue-600 text-red-600').addClass('text-green-600').text(
                        `All ${successful} files uploaded successfully!`);
                } else if (successful === 0) {
                    message.removeClass('text-blue-600 text-green-600').addClass('text-red-600').text(
                        `All ${failed} files failed to upload.`);
                } else {
                    message.removeClass('text-blue-600').addClass('text-yellow-600').text(
                        `${successful} files uploaded, ${failed} failed.`);
                }
                setTimeout(() => {
                    progressContainer.addClass('hidden');
                    progressBar.css('width', '0%');
                }, 3000);
            }


            confirmSelection() {
                if (this.options.onSelect && typeof this.options.onSelect === 'function') {
                    this.options.onSelect(this.selectedMedia, this);
                } else {
                    this.defaultSelection();
                }
                this.close();
            }

            defaultSelection() {
                if (this.options.inputTarget && this.selectedMedia) {
                    $(this.options.inputTarget).val(this.selectedMedia);
                    const selectedItem = $(
                        `#${this.modalId}-grid .media-item[data-media-id="${this.selectedMedia}"]`);
                    if (selectedItem.length && $('#featured-image-preview').length) {
                        const mediaUrl = selectedItem.data('media-url');
                        const mediaAlt = selectedItem.data('media-alt');
                        $('#featured-image-preview').html(
                            `<img src="${mediaUrl}" alt="${mediaAlt}" class="max-w-full h-48 object-contain rounded-md shadow-sm">`
                        ).show();
                        $('#select-featured-image').text('Change Featured Image');
                        $('#remove-featured-image').removeClass('hidden');
                    }
                }
            }

            reset() {
                this.currentPage = 1;
                this.hasMore = true;
                this.selectedMedia = null;
                this.needsRefresh = false;
                this.currentMediaData = null;
                this.currentFilters = {
                    type_filter: '',
                    date_filter: '',
                    search: ''
                };

                const modal = `#${this.modalId}`;

                $(`${modal}-type-filter`).val('');
                $(`${modal}-date-filter`).val('');
                $(`${modal}-search`).val('');

                $(`${modal}-grid .media-item`).removeClass('selected multiple-selection animate-pulse').removeAttr(
                    'data-selection-order');

                const confirmBtn = $(`.media-confirm-select[data-modal="${this.modalId}"]`);
                const infoText = $(`#${this.modalId}-selection-info`);
                confirmBtn.prop('disabled', true);
                infoText.text('Click images to select');

                this.hideDetailsPanel();

                if (this.options.allowView) {
                    this.switchTab('library');
                } else if (this.options.allowUpload) {
                    this.switchTab('upload');
                } else {
                    this.switchTab('url');
                }

                if (this.options.allowUpload) {
                    $(`${modal}-progress-container`).addClass('hidden');
                    $(`${modal}-progress`).css('width', '0%');
                    $(`${modal}-message`).addClass('hidden').removeClass('text-green-600 text-red-600');
                    $(`.media-file-input[data-modal="${this.modalId}"]`).val('');
                }

                $(`${modal}-image-url`).val('');
                $(`${modal}-alt-text-url`).val('');
                $(`${modal}-url-progress-container`).addClass('hidden');
                $(`${modal}-url-progress`).css('width', '0%');
                $(`${modal}-url-message`).addClass('hidden').removeClass('text-green-600 text-red-600');
                $(`${modal}-download-btn`).prop('disabled', false).html('Download Media');
            }
        }

        $(document).ready(function() {
            window.mediaPicker_{{ str_replace('-', '_', $modalId) }} = new MediaPickerClass(
                '{{ $modalId }}');
            @if ($triggerSelector)
                $(document).on('click', '{{ $triggerSelector }}', function() {
                    window.mediaPicker_{{ str_replace('-', '_', $modalId) }}.open();
                });
            @endif
        });
    </script>
@endpush
