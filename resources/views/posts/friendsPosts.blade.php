<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Posts de mes amis
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            @if($posts->count() > 0)
            @foreach($posts as $post)
            <div class="mb-6 bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                <div class="p-6 ">
                    <div class="w-1/2 mx-auto ">
                        <div class="flex justify-start space-x-3 ">
                            <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}"
                                class="h-12 w-12 rounded-full border-2 border-gray-100">
                            <div>
                                <h3 class="font-bold text-gray-900">
                                    {{ $post->user->name . ' ' . $post->user->last_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-start">
                            <p class="text-gray-800 leading-relaxed">{{ $post->content }}</p>
                        </div>
                    </div>

                    @if($post->image_path)
                    <div class="mt-4 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $post->image_path) }}"
                            class="w-1/2 max-w-md mx-auto object-cover rounded" alt="Image du post">
                    </div>
                    @endif

                    <div class="mt-6 pt-4 w-1/2 mx-auto border-t border-gray-100 flex items-center justify-between">
                        <div class="flex space-x-4 text-sm">
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="flex items-center {{ $post->isLikedBy() ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }} transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                        fill="{{ $post->isLikedBy() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                    </svg>
                                    J'aime ({{ $post->likes->count() }})
                                </button>
                            </form>
                            <button class="flex items-center text-gray-500 hover:text-blue-600 transition"
                                onclick="toggleComments{{ $post->id }}()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Commenter ({{ $post->comments->count() }})
                            </button>
                        </div>

                        <div class="flex space-x-2">
                            @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 transition flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </a>
                            @endcan

                            @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-sm text-red-600 hover:text-red-800 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>

                    <!-- Section de commentaires (initialement cachée) -->
                    <div id="comments-section-{{ $post->id }}" class="mt-4 w-1/2 mx-auto hidden">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <!-- Formulaire de commentaire -->
                            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="flex items-start space-x-2">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="h-8 w-8 rounded-full">
                                    <div class="flex-1">
                                        <textarea name="content" rows="2" placeholder="Écrire un commentaire..."
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-blue-400"></textarea>
                                        <button type="submit"
                                            class="mt-2 px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            Commenter
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Liste des commentaires -->
                            <div class="space-y-3">
                                @foreach($post->comments()->with('user')->latest()->get() as $comment)
                                <div class="flex items-start space-x-2 p-2 bg-white rounded-lg">
                                    <img src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}"
                                        class="h-8 w-8 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="text-sm font-semibold">{{ $comment->user->name }}
                                                    {{ $comment->user->last_name }}</h5>
                                                <p class="text-xs text-gray-500">
                                                    {{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(Auth::id() === $comment->user_id)
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700 mt-1">{{ $comment->content }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            function toggleComments{{$post->id}}() {
                const commentsSection = document.getElementById('comments-section-{{ $post->id }}');
                if (commentsSection.classList.contains('hidden')) {
                    commentsSection.classList.remove('hidden');
                } else {
                    commentsSection.classList.add('hidden');
                }
            }
            </script>
            @endforeach
            @else
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <p class="text-gray-600">Aucun post à afficher. Ajoutez des amis pour voir leurs publications !</p>
            </div>
            @endif

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>