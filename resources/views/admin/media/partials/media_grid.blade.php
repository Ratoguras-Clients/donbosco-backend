@forelse($medias as $media)
    <div class="media-item relative flex flex-col items-center p-3 border border-gray-200 rounded-md cursor-pointer {{ isset($trashed) && $trashed ? 'opacity-75 border-red-200 bg-red-50' : '' }}"
        data-media-id="{{ $media->id }}" data-media-url="{{ $media->url }}" data-media-alt="{{ $media->alt_text }}"
        data-media-type="{{ $media->isImage() ? 'image' : ($media->isDocument() ? 'document' : 'other') }}"
        data-media-extension="{{ $media->extension }}">

        @if(isset($trashed) && $trashed)
            <!-- Trashed indicator badge -->
            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10">
                Trashed
            </div>
        @endif

        <!-- Action buttons -->
        <div class="absolute top-2 right-2 flex gap-1 z-10">
            @if(isset($trashed) && $trashed)
                <!-- Restore button for trashed media -->
                <button onclick="confirmRestoreMedia({{ $media->id }}, '{{ $media->filename }}')"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-1 shadow-sm transition-all duration-200"
                    title="Restore media">
                    <span class="iconify text-gray-600 hover:text-green-600" data-icon="tabler:restore" data-width="16"></span>
                </button>

                <!-- Permanent delete button for trashed media -->
                <button onclick="confirmDeleteMedia({{ $media->id }}, '{{ $media->filename }}')"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-1 shadow-sm transition-all duration-200"
                    title="Delete permanently">
                    <span class="iconify text-gray-600 hover:text-red-600" data-icon="tabler:trash-x" data-width="16"></span>
                </button>
            @else
                <!-- Eye button to view media in new tab -->
                <button onclick="window.open('{{ $media->url }}', '_blank')"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-1 shadow-sm transition-all duration-200"
                    title="View in new tab">
                    <span class="iconify text-gray-600 hover:text-blue-600" data-icon="tabler:eye" data-width="16"></span>
                </button>

                <!-- Delete button -->
                <button onclick="confirmDeleteMedia({{ $media->id }}, '{{ $media->filename }}')"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-1 shadow-sm transition-all duration-200"
                    title="Move to trash">
                    <span class="iconify text-gray-600 hover:text-red-600" data-icon="tabler:trash" data-width="16"></span>
                </button>
            @endif
        </div>

        @if ($media->isImage())
            <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="media-thumbnail mb-2">
        @else
            <div class="media-thumbnail mb-2 flex items-center justify-center bg-gray-100 border border-gray-200 rounded"
                style="width: 80px; height: 60px;">
                @if (in_array(strtolower($media->extension), ['pdf']))
                    <span class="iconify text-red-600" data-icon="tabler:file-type-pdf" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['doc', 'docx']))
                    <span class="iconify text-blue-600" data-icon="tabler:file-type-doc" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['xls', 'xlsx']))
                    <span class="iconify text-green-600" data-icon="tabler:file-type-xls" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['ppt', 'pptx']))
                    <span class="iconify text-orange-600" data-icon="tabler:file-type-ppt" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['txt']))
                    <span class="iconify text-gray-600" data-icon="tabler:file-type-txt" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['mp3', 'wav', 'ogg']))
                    <span class="iconify text-purple-600" data-icon="tabler:file-music" data-width="32"></span>
                @elseif (in_array(strtolower($media->extension), ['mp4', 'avi', 'mov', 'wmv']))
                    <span class="iconify text-indigo-600" data-icon="tabler:video" data-width="32"></span>
                @else
                    <span class="iconify text-gray-600" data-icon="tabler:file" data-width="32"></span>
                @endif
            </div>
        @endif

        <p class="text-xs text-gray-600 text-center truncate w-full">{{ $media->filename }}</p>
        @if (!$media->isImage())
            <p class="text-xs text-gray-400 text-center">{{ strtoupper($media->extension) }}</p>
        @endif
    </div>
@empty
    <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
        @if(isset($trashed) && $trashed)
            <span class="iconify mb-4" data-icon="tabler:trash" data-width="64"></span>
            <p class="text-lg font-medium">No trashed media found</p>
            <p class="text-sm">Deleted media will appear here</p>
        @else
            <span class="iconify mb-4" data-icon="tabler:photo-off" data-width="64"></span>
            <p class="text-lg font-medium">No media found</p>
            <p class="text-sm">Upload some files to get started</p>
        @endif
    </div>
@endforelse
