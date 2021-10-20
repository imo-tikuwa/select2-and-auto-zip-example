<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

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
        </style>
    </head>
    <body>
        <form id="main-form" method="POST" action='/'>
            @csrf
            <div id="addresses">
                @foreach($datas as $index => $data)
                    @include('example.form', compact('index', 'data'))
                @endforeach
            </div>
            <div class="buttons mt-4">
                <div class="btn-group btn-block">
                    <button type="button" id="add-row" class="btn btn-secondary">行追加</button>
                    <button class="btn btn-primary ">送信</button>
                </div>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
        <script type="text/javascript">
            $(() => {
                console.info('DOM変更の検知にObject.definePropertyを使ったパターン');

                // K:都道府県名 V:都道府県コード
                const prefs = @json(array_flip(config('const.Prefs'))),
                // フォームに割り当てるイベント
                attachFormEvents = table => {
                    $('[id^=pref], [id^=city]', table).select2({
                        theme: 'bootstrap4',
                    });

                    $('[id^=zip]', table).keyup(e => {
                        if ($(e.target).val().length !== 7) {
                            $('[id^=pref], [id^=city]', table).val('').trigger('change');
                        }
                    });

                    $('[id^=search]', table).click(e => {
                        let index = $(e.currentTarget).data('index');
                        document.querySelector('#zip' + index).dispatchEvent(new KeyboardEvent("keyup"));
                    });

                    @php
                    // Yubinbangoでchangeなどのイベントは取得できないためhidden要素へのget、setアクセサーでselect2の制御を行う
                    // Yubinbangoの変更は都道府県→市区町村→町域の順番で呼ばれる模様
                    // 都道府県と市区町村の動的なselect2の変更は市区町村のset時に行う
                    // 参考:https://teratail.com/questions/94990?sip=n0070000_019
                    @endphp
                    ($target => {
                        let target = $target.get(0), index = $target.data('index');
                        Object.defineProperty(target, 'value', {
                            get: () => {
                                return Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').get.call(target)
                            },
                            set: city => {
                                if (city) {
                                    let pref_code = prefs[$target.prev('.p-region').val()];
                                    $('#pref' + index).val(pref_code).trigger('change');
                                    $('#city' + index).empty().append(new Option('選択してください', '', false, false));
                                    $.getJSON(`/json_data/${pref_code}.json`, json_cities => {
                                        json_cities.forEach(json_city => {
                                            let selected = json_city === city;
                                            $('#city' + index).append(new Option(json_city, json_city, selected, selected));
                                        });
                                    });
                                    $('#city' + index).trigger('change');
                                }
                                Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').set.call(target, city);
                            }
                        })
                    })($('.p-locality', table));
                };

                // 初期表示処理
                $('#addresses table').each((idx, table) => {
                    attachFormEvents(table);
                });

                // 行追加ボタン
                $('#add-row').click(e => {
                    let next_index = $('#addresses table').length;
                    $.get(`/add-row?index=${next_index}`, html => {
                        // レスポンスのhtmlテキスト→jqueryオブジェクト→DOMエレメントに変換
                        html = $(html).get(0);
                        attachFormEvents(html);
                        $('.buttons').before(html);
                        // DOM追加後、yubinbango.jsのDOMContentLoadedで実行してる処理を再度実行することで動的に追加したフォームに対応
                        // ↓issue探したらあった
                        // https://github.com/yubinbango/yubinbango/issues/6
                        new YubinBango.MicroformatDom();
                    });
                });
            });
        </script>
    </body>
</html>
