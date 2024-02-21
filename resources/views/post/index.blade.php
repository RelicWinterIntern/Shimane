<script>
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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isNear)
                {{ __('近くの投稿一覧') }}
            @else
                {{ __('投稿一覧') }}
            @endif
        </h2>
    </x-slot>

    <form id="myForm" action="{{ route('post.near') }}" method="post">
        @csrf
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
    </form>

    <div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="my-4">
            <a href="{{ route('post.create') }}" class="inline-block py-2 px-4 btn btn-primary text-decoration-none">
                {{ __('投稿する') }}
            </a>

            <a href="{{ route('myposts') }}" class="inline-block ml-4 py-2 px-4 btn btn-secondary text-decoration-none">
                {{ __('自分の投稿を確認する') }}
            </a>

            @if($isNear)
                <a href="{{ route('post.index') }}" class="inline-block py-2 px-4 btn btn-secondary text-decoration-none">
                    {{ __('全ての投稿を確認する') }}
                </a>
            @else
                <a href="javascript:void(0);" onclick="getLocation();" class="inline-block py-2 px-4 btn btn-secondary text-decoration-none">
                    {{ __('近くの投稿を確認する') }}
                </a>
            @endif
        </div>

        <div class="my-4">
            @if (!empty($posts))
                <ul>
                    @foreach ($posts as $post)
                        <li class="mb-6 bg-white border rounded-lg p-4">
                            <h3 class="text-lg font-bold mb-2 border-bottom">{{ $post->title }}</h3>
                            <p class="text-gray-1000 mt-4">{!! nl2br($post->makeLink(e($post->body))) !!}</p>
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" width="300px">
                            @endif

                            @if($post->getRefer())
                            <div class="mb-6 bg-white border rounded-lg p-4">
                                @php 
                                $refer = $post->getRefer();
                                @endphp
                                <h3 class="text-lg font-bold mb-2 border-bottom">{{ $refer->title }}</h3>
                                <p class="text-gray-1000 mt-4">{!! nl2br($post->makeLink(e($refer->body))) !!}</p>
                                @if ($refer->image)
                                    <img src="{{ asset('storage/' . $refer->image) }}" alt="{{ $refer->title }}" width="300px">
                                @endif
                                <div class="flex justify-between mt-8">
                                    <p class="text-gray-600">
                                        {{ $refer->user->name }}
                                    </p>
                                    <p class="text-gray-600">いいね {{ $refer->likes->count() }}</p>
                                    <p class="text-gray-600">{{ $refer->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex justify-between mt-8">
                                <p class="text-gray-600">
                                    {{ $post->user->name }}
                                    @if ($post->distance)
                                        ({{ floor($post->distance * 10) / 10 }} km)
                                    @endif
                                </p>
                                <p class="text-gray-600">{{ $post->updated_at->diffForHumans() }}</p>
                                <div>
                                    @if($post->is_liked_by_auth_user())
                                        <a href="{{ route('post.unlike', ['id' => $post->id]) }}" class="btn btn-success btn-sm" >いいね<span class="badge">{{ $post->likes->count() }}</span></a>
                                    @else
                                        <a href="{{ route('post.like', ['id' => $post->id]) }}" class="btn btn-secondary btn-sm">いいね<span class="badge">{{ $post->likes->count() }}</span></a>
                                    @endif
                                </div>
                                <a href="{{ route('post.create', $post->id) }}" class="inline-block py-2 px-4 btn btn-primary text-decoration-none float-end">
                                    {{ __('再投稿する') }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex justify-center items-center h-full">
                    <p class="text-lg text-gray-600">投稿はありません。</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
