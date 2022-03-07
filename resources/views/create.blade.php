@extends('layouts.app')


@section('content')

</form>
<div class="container">
    <div class="card w-100">
        <div class="card-header">新規メモ作成</div>
        <div class="card-body">
            <form method='POST' action="/store">
                @csrf
                <input type='hidden' name='user_id' value="{{ $user['id'] }}">
                <div class="form-group">
                    <textarea name='content' class="form-control"rows="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="tag">タグ</label>
                    <input name='tag' type="text" class="form-control" id="tag" placeholder="タグを入力">
                </div>
                <div class="form-grouo">
                    <label for="email">宛先のアドレス</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="datetime" >リマインド送信時間</label>
                    <input type="datetime-local" name="send_at" id="send_at" class="form-control">
                </div>
                <br>
                <button type='submit' class="btn btn-primary btn-lg">保存</button>
            </form>
        </div>
    </div>
</div>
@endsection