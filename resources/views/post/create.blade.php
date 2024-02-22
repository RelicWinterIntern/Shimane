<script>
    function submitFormWithLocation() {
        getLocation();
        return false; // フォームの通常の送信をキャンセルする
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // フォームのhidden input要素に位置情報を設定する
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;

        // フォームを送信する
        document.getElementById('myForm').submit();
    }
</script>

<x-app-layout>
    <x-slot name="header">
        @if(isset($original_post))
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('再投稿') }}
        </h2>
        @else
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新規投稿') }}
        </h2>
        @endif
    </x-slot>

    <div class="max-w-7xl mx-auto mt-10 sm:px-6 lg:px-8">
        <div class="my-4">
            <div class="bg-white shadow p-6 rounded-lg">
                <form id="myForm" action="{{ route('post.store') }}" method="post" enctype="multipart/form-data" onsubmit="return submitFormWithLocation()">
                    @csrf
                    @if(isset($original_post))
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2 text-danger">タイトル　(入力必須)</label>
                            <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" required placeholder="「{{ $original_post->title }}」を引用">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="body" class="block text-gray-700 text-sm font-bold mb-2 text-danger">本文　(入力必須)</label>
                        <textarea name="body" id="body" rows="6" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">画像</label>
                        <input type="file" name="image">
                    </div>
                    <input type="hidden" id="refer" name="refer" value="{{ $original_post->id }}">
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                    <div class="flex justify-end">
                        <button type="submit" class="py-2 px-4 btn btn-primary">再投稿する</button>
                        <a href="{{ route('post.index') }}" class="py-2 px-4 ml-4 btn btn-secondary">キャンセル</a>
                    </div>
                    @else
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2 text-danger">タイトル　(入力必須)</label>
                        <input type="text" placeholder="話の題名" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="body" class="block text-gray-700 text-sm font-bold mb-2 text-danger">本文　(入力必須)</label>
                        <textarea name="body" placeholder="最近の出来事を呟こう" id="body" rows="6" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">画像</label>
                        <input type="file" name="image">
                    </div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                    <div class="flex justify-end">
                        <button type="submit" class="py-2 px-4 btn btn-primary">投稿する</button>
                        <a href="{{ route('post.index') }}" class="py-2 px-4 ml-4 btn btn-secondary">キャンセル</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
