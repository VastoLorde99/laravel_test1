<main class="bg-black p-3">
    <div class="container text-center">
        <h2 class="text-light">Напишите запись</h2>
        <div>
            <form id="msg">
                <textarea style="outline: none; height: 100px;" class="w-100 p-3" name="text" cols="30" rows="10"
                    placeholder="Напишите что-нибудь"></textarea>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>

    <div class="container">
        <h2 class="text-center text-light">Все записи</h2>
        <div id="list" class="list">
            @foreach ($posts as $post)
                <div data-id="{{ $post->id }}" class="list_item bg-dark row mb-3 p-1">
                    @php
                        if ($post->created_at != $post->updated_at) { $edit = '(Ред.)';}
                        else { $edit = ''; }
                    @endphp
                    <div class="info text-warning">{{ date('d.m.Y H:i', strtotime($post->created_at)) }} <span class="status">{{ $edit }}</span></div>
                    @php
                        $diff_hour = (time() - strtotime($post->created_at)) / (60 * 60);
                    @endphp
                    @if (($post->user_id == session('user.id') && $post->user_id != null) || session('user.role') == 'admin')
                        @if ((session('user.role') == 'user' && $diff_hour <= 2) || session('user.role') == 'admin')
                            <div class="options d-flex text-light">
                                <div class="delete">удалить</div>
                            </div>
                            <div contenteditable="true" class="text text-light">{{ $post->text }}</div>
                        @else
                            <div class="text text-light">{{ $post->text }}</div>
                        @endif
                    @else
                        <div class="text text-light">{{ $post->text }}</div>
                    @endif
                </div>
            @endforeach
            <div id="pagination">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</main>
