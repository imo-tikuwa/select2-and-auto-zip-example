<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Laravel'}}</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.css" rel="stylesheet" />
        <style>
            table {
                width: 400px;
            }
            table caption {
                caption-side: inherit;
                padding: 0;
            }
            table tr td {
                width: 300px;
            }
            table tr td input,
            table tr td select {
                width: 100%
            }
            div.buttons {
                width: 400px;
            }
            .form-control-sm + .select2-container--bootstrap4 .select2-selection {
                height: calc(1.85rem + 2px) !important;
                font-size: .9rem;
            }
            .form-control-sm + .select2-container--bootstrap4 .select2-selection .select2-selection__rendered {
                margin-top: -0.2em;
            }
        </style>
    </head>
    <body>
        <a href="{{ route('example', 'object-define-property') }}" class="link-primary">Object.defineProperty</a>
        /
        <a href="{{ route('example', 'mutation-observer') }}" class="link-primary">MutationObserver</a> を使ったパターン
        <form id="main-form" method="POST" >
            @csrf
            <div id="addresses">
                @foreach($datas as $index => $data)
                    @include('example.form', compact('index', 'data'))
                @endforeach
            </div>
            <div class="buttons mt-4">
                <div class="btn-group btn-block">
                    <button type="button" id="add-row" class="btn btn-sm btn-secondary">行追加</button>
                    <button class="btn btn-sm btn-primary ">送信</button>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
        @yield('script')
    </body>
</html>
